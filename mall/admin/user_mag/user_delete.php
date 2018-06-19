<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2
 * Time: 8:03
 */
include_once '../lib/fun.php';
if (!checkUser_login()){
    msg(2,'请登录','../login.php');
}
//检验传过来的id
$goodId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';
if (!$goodId){
    msg(2, '参数非法！');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if (!$con){
    echo mysql_error();
    exit;
}
$sql = "SELECT 'id' FROM `nt_goods` WHERE `id` = {$goodId}";
$result = mysql_query($sql);

if(!$result){
    msg(2, '该商品不存在！');
}

unset($sql, $result);
$sql = "DELETE FROM `nt_goods` where `id` = {$goodId} LIMIT 1";
$result = mysql_query($sql);
if($result){
    msg(1, '操作成功！');
}else{
    msg(2, '操作失败！');
}