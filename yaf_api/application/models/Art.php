<?php
/**
 *  Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19
 * Time: 15:16
 * @name ArtModel
 * @desc 文章操作类
 * @author Administrator
 */
class ArtModel {
    public $errno = 0;
    public $errmsg = "";
    private $_db;

    public function __construct() {
        $conf = Yaf_Application::app()->getConfig();
        if(!empty($conf)){
            $Hostname = $conf->database->params->hostname;
            $Dbname = $conf->database->params->database;
            $Username = $conf->database->params->username;
            $Password = $conf->database->params->password;
            $dns = "mysql:host="."$Hostname".";"."dbname="."$Dbname".";";
            $this->_db = new PDO($dns, $Username, $Password);
            if (!$this->_db){
                $this->errno = -1000;
                $this->errmsg = "数据库连接失败";
                return false;
            }
            //不设置下面这行的话，PDO会在拼SQL时候，把int 0转成string 0
            $this->_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }else{
            $this->errno = -1000;
            $this->errmsg = "数据库连接失败";
            return false;
        }
    }
    //文章添加
    public function add($title, $contents, $author, $cate, $artId = 0){
        $isEdit = false;
        if($artId != 0 && is_numeric($artId)){
            $query = $this->_db->prepare("SELECT COUNT(*) FROM `art` WHERE `id` = ?");
            $query->execute(array($artId));
            $ret = $query->fetchAll();
            if (!$ret || $ret[0][0] == 0){
                $this->errno = -2000;
                $this->errmsg = "你所要编辑的文章不存在！";
                return false;
            }
            $isEdit = true;
        }else{
            //检查cate是否存在，如果之前已经创建过的话，就不需要检验
            $query = $this->_db->prepare("SELECT COUNT(*) FROM `cate` WHERE `id` = ?");
            $query->execute(array($cate));
            $ret = $query->fetchAll();
            if(!$ret || $ret[0][0] == 0){
                $this->errno = -2001;
                $this->errmsg = "找不到对应ID的分类信息，cate id：".$cate."请先创建该分类！";
                return false;
            }

            //不能添加相同的文章
            $query = $this->_db->prepare("SELECT COUNT(*) FROM `art` WHERE `title` = ?");
            $query->execute(array($title));
            $ret = $query->fetchAll();
            if(!$ret || $ret[0][0] != 0){
                $this->errno = -2010;
                $this->errmsg = "该文章已存在，文章添加失败！";
                return false;
            }
        }
        //插入或者更新文章内容
        $data = array($title, $contents, $author, intval($cate));

        if(!$isEdit){
            $query = $this->_db->prepare("INSERT INTO `art`(`title`,`contents`,`author`,`cate`) VALUES (?, ?, ?, ?)");
        }else{
            $query = $this->_db->prepare("UPDATE `art` SET `title` = ?, `contents` = ?, `author` = ?, `cate` = ? WHERE `id`= ?");
            $data[] = $artId;
        }
        $ret = $query->execute($data);
        if(!$ret){
            $this->errno = -2002;
            $this->errmsg = "操作文章数据表失败,ErrInfo:".end($query->errorInfo());
            return false;
        }
        //是否处于编辑的状态和返回对应的结果
        if (!$isEdit){
            return intval($this->_db->lastInsertId());
        }else{
            return intval($artId);
        }

    }
    //文章删除
    public function del($artId){
        $query = $this->_db->prepare("DELETE FROM `art` WHERE `id` = ?");
        $ret = $query->execute(array(intval($artId)));
        if(!$ret){
            $this->errno = -2003;
            $this->errmsg = "文章删除失败，ErrInfo:".end($query->errorInfo());
            return false;
        }
        return true;
    }
    //文章状态
    public function status($artId, $status = "offline"){
        //查找要修改的文章状态的id是否存在
        $query = $this->_db->prepare("SELECT COUNT(*) FROM `art` WHERE `id` = ?");
        $query->execute(array($artId));
        $ret = $query->fetchAll();
        if (!$ret || $ret[0][0] == 0){
            $this->errno = -2012;
            $this->errmsg = "该文章不存在，文章状态修改失败！";
            return false;
        }

        $query = $this->_db->prepare("UPDATE `art` SET `status` = ? WHERE `id` = ?");
        $ret = $query->execute(array($status, intval($artId)));
        if (!$ret){
            $this->errno = -2004;
            $this->errmsg = "更新文章状态失败，ErrInfo：".end($query->errorInfo());
            return false;
        }
        return true;
    }
    //获取文章
    public function get($artId){
        $query = $this->_db->prepare("SELECT `title`, `contents`, `author`, `cate`, `ctime`, `mtime`, `status` FROM `art` WHERE `id` = ?");
        $status = $query->execute(array(intval($artId)));
        $ret = $query->fetchAll();
        if(!$ret || !$status){
            $this->errno = -2005;
            $this->errmsg = "查询失败，ErrInfo：".end($query->errorInfo());
            return false;
        }
        $artInfo = $ret[0];
        //获取分类信息
        $query = $this->_db->prepare("SELECT `name` FROM `cate` WHERE `id` = ?");
        $query->execute(array(intval($artInfo['cate'])));
        $ret = $query->fetchAll();
        if(!$ret){
            $this->errno = - 2006;
            $this->errmsg = "获取分类信息失败，ErrInfo：".end($query->errorInfo());
            return false;
        }
        $artInfo['cateName'] = $ret[0]['name'];
        $data = array(
            'id'=> intval($artId),
            'title'=>$artInfo['title'],
            'contents'=>$artInfo['contents'],
            'author'=>$artInfo['aurhor'],
            'cateName'=>$artInfo['cateName'],
            'cateId'=>intval($artInfo['cate']),
            'ctime'=>$artInfo['ctime'],
            'mtime'=>$artInfo['mtime'],
            'status'=>$artInfo['status']
        );
        return $data;
    }

    //文章列表,分页
    public function lists($pageNo = 0, $pageSize = 10, $cate = 0, $status = 'online'){
        $start = $pageNo * $pageSize + ($pageNo == 0 ? 0:1);

        if($cate == 0){
            $filter = array($status, intval($start), intval($pageSize));
            $query = $this->_db->prepare("select `id`, `title`,`contents`,`author`,`cate`,`ctime`,`mtime`,`status` from `art` where `status`=? order by `ctime` desc limit ?,?  ");
        }else{
            $filter = array(intval($cate), $status, intval($start));
            $query = $this->_db->prepare("select `id`, `title`,`contents`,`author`,`cate`,`ctime`,`mtime`,`status` from `art` where `cate`=? and `status`=? order by `ctime` desc limit ?,?  ");
        }
        $stat = $query->execute($filter);
        $ret = $query->fetchAll();
        if(!$ret){
            $this->errno = -2007;
            $this->errmsg = "获取文章列表失败";
            return false;
        }

        $data = array();
        $cateInfo = array();
        foreach ($ret as $item){
            //获取分类信息
            if(isset($cateInfo[$item['cate']])){
                $cateName = $cateInfo[$item['cate']];
            }else{
                $query = $this->_db->prepare("SELECT `name` FROM `cate` WHERE `id` = ?");
                $query->execute(array($item['cate']));
                $retCate = $query->fetchAll();
                if(!$retCate){
                    $this->errno = -2006;
                    $this->errmsg = "获取分类信息失败，ErrInfo：".end($query->errorInfo());
                    return false;
                }
                $cateName = $cateInfo[$item['cate']] = $retCate[0]['name'];
            }
            //正文太长则剪切
            $contents = mb_strlen($item['contents']) > 30 ? mb_strcut($item['contents'], 0, 30)."..." : $item['contents'];

            $data[] = array(
                'id'=>intval($item['id']),
                'title'=>$item['title'],
                'contents'=>$item['contents'],
                'author'=>$item['author'],
                'cateName'=>$cateName,
                'cateId'=>intval($item['cate']),
                'ctime'=>$item['ctime'],
                'mtime'=>$item['mtime'],
                'status'=>$item['status']
            );
        }
        return $data;
    }






}