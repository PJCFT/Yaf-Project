<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2
 * Time: 8:43
 */
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkUser_login()){
    msg(2,'请登录！','../login.php');
}
$user = $_SESSION['user'];
$username1 = $user['user_name'];

if(!empty($_POST['good_name'])){
    $con = mysqlInit($host, $Username, $Password, $dbName);
    if(!$con){
        echo mysql_error();
        exit;
    }
    $goodId = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']):'';

    if(!$goodId){
        msg(2, '参数非法！');
    }
    $sql = "SELECT * FROM `nt_goods` WHERE `id`={$goodId}";
    $obj = mysql_query($sql);
    $goods = mysql_fetch_assoc($obj);

    if(!$goods){
        msg(2, '该商品不存在！');
    }
    $goodname = mysql_real_escape_string(trim($_POST['good_name']));
    $goodprice = intval($_POST['good_price']);
    $goodauthor = mysql_real_escape_string(trim($_POST['good_author']));
    $gooddes = mysql_real_escape_string(trim($_POST['good_description']));
    $goodcontent = mysql_real_escape_string(trim($_POST['good_content']));

    $goodnameLength = mb_strlen($goodname, 'utf-8');
    if($goodnameLength <= 0 || $goodnameLength > 51){
        msg(2, '商品名应该在1到50个字以内，请重输！');
    }

    if($goodprice <= 0 || $goodprice > 9999999){
        msg(2, '商品价格应小于9999999');
    }
    if(empty($goodauthor) || $goodauthor != $user['user_name']){
        msg(2, '发布者不能为空,或者发布者被修改！');
    }

    $gooddesLength = mb_strlen($gooddes, 'utf-8');
    if($gooddesLength <= 0 || $gooddesLength > 201){
        msg(2, '商品简介应该在200字以内！');
    }

    if(empty($goodcontent)){
        msg(2, '商品详情不能为空！');
    }

    //查询该发布者的id
    $sql = "SELECT `user_id`FROM `nt_user` WHERE `user_name` = '{$username1}' LIMIT 1";
    $obj = mysql_query($sql);
    $result = mysql_fetch_assoc($obj);
    $userId = $result['user_id'];

    unset($sql, $obj, $result);

    $update = array(
        'name' =>$goodname,
        'price' =>$goodprice,
        'des' => $gooddes,
        'content'=>$goodcontent,
        'user_id'=>$userId,
        'update_time' => $_SERVER['REQUEST_TIME']
    );
    //仅当发布者选择上传图片 才进行图片上传处理
    if($_FILES['good_pic']['size'] > 0)
    {
        $pic = imgUpload($_FILES['good_pic']);
        $update['pic'] = $pic;
    }
    foreach ($update as $k =>$v){
        if($goods[$k] == $v){
            unset($update[$k]);
        }
    }
    //没有修改更新的话
    if(empty($update)){
        msg(1, '修改成功！','user_goods_list.php');
    }
    //对需要更改的字段进行拼接
    $updateSql = '';
    foreach($update as $k => $v)
    {
        $updateSql .= "`{$k}` = '{$v}' ,";
    }
    //对用户名进行唯一性验证
    if(isset($update['name'])){
        $a = $update['name'];
        $sql_1= "SELECT COUNT(  `id` ) as total FROM  `nt_goods` WHERE  `name` =  '{$a}'";
        $obj_1 = mysql_query($sql_1);
        $result = mysql_fetch_assoc($obj_1);
        if(isset($result['total']) && $result['total'] > 0){
            msg(2,'该商品名称已存在，请重新输入！');
        }
    }
    //去除多余的,
    $updateSql = rtrim($updateSql, ',');
    unset($sql, $obj, $result);
    $sql = "UPDATE `nt_goods` SET {$updateSql} WHERE `id` = {$goodId}";
    $result = mysql_query($sql);
    if($result){
        msg(1, '商品修改成功！','user_goods_list.php');
    }else{
        msg(2, ' 商品修改失败！', 'user_goods_list.php');
    }
}
else{
    msg(2, '路由非法！','../index.php');
}