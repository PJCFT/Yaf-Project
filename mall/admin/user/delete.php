<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/14
 * Time: 17:46
 * 这里的发布者的删除不应只删除发布者的信息，同时也要删除掉该发布者所发布的商品信息。
 */
include_once '../lib/fun.php';
if (!checkLogin()){
    msg(2,'请登录','../login.php');
}
//检验传过来的id-
$userId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';
if (!$userId){
    msg(2, '参数非法！','list.php');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if (!$con){
    echo mysql_error();
    exit;
}
$sql = "SELECT 'user_id' FROM `nt_user` WHERE `user_id` = {$userId}";
$result = mysql_query($sql);

if(!$result){
    msg(2, '该商品发布者不存在！', 'list.php');
}

unset($sql, $result);
$sql = "DELETE FROM `nt_user` where `user_id` = {$userId} LIMIT 1";
$result = mysql_query($sql);

$sql1 = "DELETE FROM `nt_goods` where `user_id` = {$userId}";
$result1 = mysql_query($sql1);

if($result && $result1){
    msg(1, '商品发布者删除成功！','list.php');
}else{
    msg(2, '商品发布者删除失败！', 'list.php');
}


