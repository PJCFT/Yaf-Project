<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/31
 * Time: 8:54
 */
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkUser_login()){
    msg(2,'请登录！','../login.php');
}
if(!empty($_POST['user_name'])){
    $con = mysqlInit($host, $Username, $Password, $dbName);
    if(!$con){
        echo mysql_error();
        exit;
    }
    $userId = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']):'';

    if(!$userId){
        msg(2, '参数非法！');
    }
    $sql = "SELECT * FROM `nt_user` WHERE `user_id`={$userId}";
    $obj = mysql_query($sql);
    $users = mysql_fetch_assoc($obj);

    if(!$users){
        msg(2, '该商品发布者不存在！');
    }
    $username = mysql_real_escape_string(trim($_POST['user_name']));
    $newpassword = mysql_real_escape_string(trim($_POST['user_newpass']));
    $repassword = mysql_real_escape_string(trim($_POST['user_repass']));
    $newpasswordlength = strlen($newpassword);

    if($username == ''){
        msg(2, '商品发布者名称不能为空！');
    }
    if($newpasswordlength <=5 || $newpasswordlength > 30){
        msg(2, '密码长度应在6到30位之间');
    }
    if($newpassword != $repassword){
        msg(2, '两次输入密码不正确，请重试！');
    }

    $update = array(
        'user_name' =>$username,
        'user_password' =>createPassword($newpassword),
        'update_time' => $_SERVER['REQUEST_TIME']
    );

    foreach ($update as $k =>$v){
        if($users[$k] == $v){
            unset($update[$k]);
        }
    }
    //没有修改更新的话
    if(empty($update)){
        msg(1, '修改成功！');
    }
    //对需要更改的字段进行拼接
    $updateSql = '';
    foreach($update as $k => $v)
    {
        $updateSql .= "`{$k}` = '{$v}' ,";
    }
    //对用户名进行唯一性验证
    if(isset($update['user_name'])){
        $a = $update['user_name'];
        $sql_1= "SELECT COUNT(  `user_id` ) as total FROM  `nt_user` WHERE  `user_name` =  '{$a}'";
        $obj_1 = mysql_query($sql_1);
        $result = mysql_fetch_assoc($obj_1);
        if(isset($result['total']) && $result['total'] > 0){
            msg(2,'该发布者名称已存在，请重新输入！');
        }
    }
    //去除多余的,
    $updateSql = rtrim($updateSql, ',');
    unset($sql, $obj, $result);
    $sql = "UPDATE `nt_user` SET {$updateSql} WHERE `user_id` = {$userId}";
    $result = mysql_query($sql);
    if($result){
        msg(1, '商品发布者密码修改成功！');
    }else{
        msg(2, '商品发布者密码修改失败！');
    }
}
else{
    msg(2, '路由非法！');
}