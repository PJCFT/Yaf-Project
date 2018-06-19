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
    $adminId = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']):'';

    if(!$adminId){
        msg(2, '参数非法！');
    }
    $sql = "SELECT * FROM `nt_admin` WHERE `admin_id`={$adminId}";
    $obj = mysql_query($sql);
    $admins = mysql_fetch_assoc($obj);

    if(!$admins){
        msg(2, '该管理员不存在！','list.php');
    }
    $adminname = mysql_real_escape_string(trim($_POST['user_name']));
    $phone = mysql_real_escape_string(trim($_POST['user_phone']));
    $oldpassword = mysql_real_escape_string(trim($_POST['user_oldpass']));
    $newpassword = mysql_real_escape_string(trim($_POST['user_newpass']));
    $repassword = mysql_real_escape_string(trim($_POST['user_repass']));
    $newpasswordlength = strlen($newpassword);

    if($adminname == ''){
        msg(2, '管理员名称不能为空！');
    }
    if(strlen($phone) == 0 || strlen($phone) != 11){
        msg(2, '管理员手机号为空或者手机位数不是11位！');
    }
    if(createPassword($oldpassword) != $admins['admin_password']){
        msg(2, '旧密码错误，请重试！');
    }
    if($newpasswordlength <=5 || $newpasswordlength > 30){
        msg(2, '密码长度应在6到30位之间');
    }
    if($newpassword != $repassword){
        msg(2, '两次输入密码不正确，请重试！');
    }

    $update = array(
        'admin_name' =>$adminname,
        'admin_password' =>createPassword($newpassword),
        'admin_phone' => $phone,
        'update_time' => $_SERVER['REQUEST_TIME']
    );

    foreach ($update as $k =>$v){
        if($admins[$k] == $v){
            unset($update[$k]);
        }
    }
    //没有修改更新的话
    if(empty($update)){
        msg(1, '修改成功！','edit.php?='.$adminId);
    }
    //对需要更改的字段进行拼接
    $updateSql = '';
    foreach($update as $k => $v)
    {
        $updateSql .= "`{$k}` = '{$v}' ,";
    }
    //对用户名进行唯一性验证
    if(isset($update['admin_name'])){
        $a = $update['admin_name'];
        $sql_1= "SELECT COUNT(  `admin_id` ) as total FROM  `nt_admin` WHERE  `admin_name` =  '{$a}'";
        $obj_1 = mysql_query($sql_1);
        $result = mysql_fetch_assoc($obj_1);
        if(isset($result['total']) && $result['total'] > 0){
            msg(2,'该管理员名称已存在，请重新输入！');
        }
    }
    //去除多余的,
    $updateSql = rtrim($updateSql, ',');
    unset($sql, $obj, $result);
    $sql = "UPDATE `nt_admin` SET {$updateSql} WHERE `admin_id` = {$adminId}";
    $result = mysql_query($sql);
    if($result){
        msg(1, '管理员修改成功！','list.php');
    }else{
        msg(2, ' 管理员修改失败！', 'list.php');
    }
}
else{
    msg(2, '路由非法！','../index.php');
}
