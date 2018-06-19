<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4
 * Time: 20:45
 */
include_once '../lib/fun.php';
if (!checkLogin()){
    msg(2,'请登录','../login.php');
}
//检验传过来的id
$goodId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';
if (!$goodId){
    msg(2, '参数非法！','list.php');
}
$con = mysqlInit($host, $Username, $Password, $dbName);
if (!$con){
    echo mysql_error();
    exit;
}
$sql = "SELECT 'id' FROM `nt_goods` WHERE `id` = {$goodId}";
$result = mysql_query($sql);

if(!$result){
    msg(2, '该商品不存在！', 'list.php');
}

unset($sql, $result);
$sql = "DELETE FROM `nt_goods` where `id` = {$goodId} LIMIT 1";
$result = mysql_query($sql);
if($result){
    msg(1, '商品删除成功！','list.php');
}else{
    msg(2, '商品删除失败！', 'list.php');
}