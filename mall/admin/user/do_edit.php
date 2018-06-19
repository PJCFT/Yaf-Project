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
    $userId = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']):'';

    if(!$userId){
        msg(2, '参数非法！');
    }
    $sql = "SELECT * FROM `nt_user` WHERE `user_id`={$userId}";
    $obj = mysql_query($sql);
    $users = mysql_fetch_assoc($obj);

    if(!$users){
        msg(2, '该商品发布者不存在！','list.php');
    }
    $username = mysql_real_escape_string(trim($_POST['user_name']));
    $usersex = mysql_real_escape_string(trim($_POST['user_sex']));
    $phone = mysql_real_escape_string(trim($_POST['user_phone']));
    $userpostcode = mysql_real_escape_string(trim($_POST['user_postcode']));
    $useraddress = mysql_real_escape_string(trim($_POST['user_address']));

    if($username == ''){
        msg(2, '商品发布者名称不能为空！');
    }
    if(strlen($phone) == 0 || strlen($phone) != 11){
        msg(2, '商品发布者手机号为空或者手机位数不是11位！');
    }
    if($userpostcode == '' || strlen($userpostcode) != 6){
        msg(2, '商品发布者邮编不对，请输入六位邮编！');
    }
    if($useraddress == ''){
        msg(2, '商品发布者地址不能为空！');
    }

    $update = array(
        'user_name' =>$username,
        'user_sex' =>$usersex,
        'user_phone' => $phone,
        'user_postcode'=>$userpostcode,
        'user_address'=>$useraddress,
        'update_time' => $_SERVER['REQUEST_TIME']
    );

    foreach ($update as $k =>$v){
        if($users[$k] == $v){
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
        msg(1, '商品发布者修改成功！','list.php');
    }else{
        msg(2, '商品发布者修改失败！', 'list.php');
    }
}
else{
    msg(2, '路由非法！','../index.php');
}
