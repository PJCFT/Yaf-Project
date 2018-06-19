<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19
 * Time: 16:06
 */
class ArtController extends Yaf_Controller_Abstract{
    public function indexAction(){
        $this->listAction();
    }

    public function addAction($artId = 0){
        if (!$this->_isAdmin()){
            echo json_encode(
              array(
                  "errno"=> -2008,
                  "errmsg"=>"需要管理员权限才可以操作"
              )
            );
            return false;
        }
        $submit = $this->getRequest()->getQuery("submit", 0);
        if ($submit != 1){
            echo json_encode(array(
                "errno"=>-1010,
                "errmsg"=>"请通过正确渠道提交"
            ));
            return false;
        }
        //获取参数
        $title = $this->getRequest()->getPost("title",false);
        $contents = $this->getRequest()->getPost("contents",false);
        $author = $this->getRequest()->getPost("author", false);
        $cate = $this->getRequest()->getPost("cate", false);
        if (!$title || !$contents || !$author || !$cate){
            echo json_encode(array(
                "errno" => -2009,
                "errmsg" => "标题，内容，作者，分类信息为空，不能为空"
            ));
            return false;
        }

        //调用Model验证
        $model = new ArtModel();
        if($lastId = $model->add(trim($title), trim($contents), trim($author), trim($cate),$artId)){
            echo json_encode(array(
                "errno"=> 0,
                "errmsg" => "",
                "data"=>array("lastId"=>$lastId)
            ));
        }else{
            echo json_encode(array(
                "errno"=>$model->errno,
                "errmsg"=>$model->errmsg
            ));
        }
        return true;
    }
    // 文章的编辑
    public function editAction(){
        if (!$this->_isAdmin()){
            echo json_encode(array(
                "errno"=>-2008,
                "errmsg"=>"需要管理员权限才可以操作"
            ));
            return false;
        }
        $artId = $this->getRequest()->getQuery("artId", 0);
        if(is_numeric($artId) && $artId){
            return $this->addAction($artId);
        }else{
            echo json_encode(array(
                "errno"=>-2011,
                "errmsg"=>"缺少必要的文章ID参数"
            ));
        }
        return true;
    }
    //文章的删除
    public function delAction(){
        if(!$this->_isAdmin()){
            echo json_encode(array(
                "errno"=>-2008,
                "errmsg"=>"需要管理员权限才可以操作"
            ));
            return false;
        }
        $artId = $this->getRequest()->getQuery("artId", 0);
        if (is_numeric($artId) && $artId){
            $model = new ArtModel();
            if($model->del($artId)){
                echo json_encode(array(
                    "errno"=> 0,
                    "errmsg"=>""
                ));
            }else{
                echo json_encode(array(
                    "errno"=>$model->errno,
                    "errmsg"=>$model->errmsg
                ));
            }
        }else{
            echo json_encode(array(
                "errno"=>-2011,
                "errmsg"=>"缺少必要的文章ID参数"
            ));
        }
        return true;
    }

    //文章状态
    public function statusAction(){
        if(!$this->_isAdmin()){
            echo json_encode(array(
                "errno"=>-2008,
                "errmsg"=>"需要管理员权限才可以操作"
            ));
            return false;
        }
        $artId = $this->getRequest()->getQuery("artId", 0);
        $status = $this->getRequest()->getQuery("status", "offline");

        if(is_numeric($artId) && $artId){
            $model = new ArtModel();
            if($model->status($artId, $status)){
                echo json_encode(array(
                    "errno"=>0,
                    "errmsg"=>""
                ));
            }else{
                echo json_encode(array(
                    "errno"=>$model->errno,
                    "errmsg"=>$model->errmsg
                ));
            }
        }else{
            echo json_encode(array(
                "errno"=>-2011,
                "errmsg"=>"缺少必要的文章ID参数"
            ));
        }
    }
    public function getAction(){
        $artId = $this->getRequest()->getQuery("artId", 0);
        if(is_numeric($artId) && $artId){
            $model = new ArtModel();
            if ($data = $model->get($artId)){
                echo json_encode(array(
                    "errno"=>0,
                    "errmsg"=>"",
                    "data"=>$data
                ));
            }else{
                echo json_encode(array(
                    "errno"=>-2013,
                    "errmsg"=>"获取文章信息失败"
                ));
            }
        }else{
            echo json_encode(array(
                "errno"=>-2011,
                "errmsg"=>"缺少必要的文章ID参数"
            ));
        }
        return true;
    }
    public function listAction(){
        $pageNo = $this->getRequest()->getQuery("pageNo",0);
        $pageSize = $this->getRequest()->getQuery("pageSize", 10);
        $cate = $this->getRequest()->getQuery("cate", 0);
        $status = $this->getRequest()->getQuery("status", "online");

        $model = new ArtModel();
        //$data = $model->lists($pageNo, $pageSize, $cate, $status);
        if ($data = $model->lists($pageNo, $pageSize, $cate, $status)){
            echo json_encode(array(
                "errno"=>0,
                "errmsg"=>"",
                "data"=>$data
            ));
        }else{
            echo json_encode(array(
                "errno"=>-2007,
                "errmsg"=>"获取文章列表失败"
            ));
        }
        return true;
    }
    private function _isAdmin(){
        return true;
    }


}