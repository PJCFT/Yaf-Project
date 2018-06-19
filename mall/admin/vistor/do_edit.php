<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/14
 * Time: 20:14
 */
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkLogin()){
    msg(2,'请登录！','../login.php');
}
if(!empty($_POST['user_name'])){
    $con = mysqlInit($host, $Username, $Password, $dbName);
    if(!$con){
        echo mysql_error();
        exit;
    }
    $visitorId = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']):'';

    if(!$visitorId){
        msg(2, '参数非法！');
    }
    $sql = "SELECT * FROM `nt_visitor` WHERE `visitor_id`={$visitorId}";
    $obj = mysql_query($sql);
    $visitors = mysql_fetch_assoc($obj);

    if(!$visitors){
        msg(2, '该普通用户不存在！','list.php');
    }
    $visitorname = mysql_real_escape_string(trim($_POST['user_name']));
    $newpassword = mysql_real_escape_string(trim($_POST['user_newpass']));
    $repassword = mysql_real_escape_string(trim($_POST['user_repass']));
    $newpasswordlength = strlen($newpassword);

    if($visitorname == ''){
        msg(2, '普通用户名称不能为空！');
    }
    if($newpasswordlength <=5 || $newpasswordlength > 30){
        msg(2, '密码长度应在6到30位之间');
    }
    if($newpassword != $repassword){
        msg(2, '两次输入密码不正确，请重试！');
    }

    $update = array(
        'visitor_name' =>$visitorname,
        'visitor_password' =>createPassword($newpassword),
        'update_time' => $_SERVER['REQUEST_TIME']
    );

    foreach ($update as $k =>$v){
        if($visitors[$k] == $v){
            unset($update[$k]);
        }
    }
    //没有修改更新的话
    if(empty($update)){
        msg(1, '修改成功！','edit.php?='.$visitorId);
    }
    //对需要更改的字段进行拼接
    $updateSql = '';
    foreach($update as $k => $v)
    {
        $updateSql .= "`{$k}` = '{$v}' ,";
    }
    //对用户名进行唯一性验证
    if(isset($update['visitor_name'])){
        $a = $update['visitor_name'];
        $sql_1= "SELECT COUNT(  `visitor_id` ) as total FROM  `nt_visitor` WHERE  `visitor_name` =  '{$a}'";
        $obj_1 = mysql_query($sql_1);
        $result = mysql_fetch_assoc($obj_1);
        if(isset($result['total']) && $result['total'] > 0){
            msg(2,'该普通用户名称已存在，请重新输入！');
        }
    }
    //去除多余的,
    $updateSql = rtrim($updateSql, ',');
    unset($sql, $obj, $result);
    $sql = "UPDATE `nt_visitor` SET {$updateSql} WHERE `visitor_id` = {$visitorId}";
    $result = mysql_query($sql);
    if($result){
        msg(1, '普通用户修改成功！','list.php');
    }else{
        msg(2, ' 普通用户修改失败！', 'list.php');
    }
}
else{
    msg(2, '路由非法！','../index.php');
}
