<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/14
 * Time: 17:46
 */
include_once '../lib/fun.php';
if (!checkLogin()){
    msg(2,'请登录','../login.php');
}
//检验传过来的id
$visitorId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';
if (!$visitorId){
    msg(2, '参数非法！','list.php');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if (!$con){
    echo mysql_error();
    exit;
}
$sql = "SELECT 'visitor_id' FROM `nt_visitor` WHERE `visitor_id` = {$visitorId}";
$result = mysql_query($sql);

if(!$result){
    msg(2, '该普通用户不存在！', 'list.php');
}

unset($sql, $result);
$sql = "DELETE FROM `nt_visitor` where `visitor_id` = {$visitorId} LIMIT 1";
$result = mysql_query($sql);
if($result){
    msg(1, '操作成功！','list.php');
}else{
    msg(2, '操作失败！', 'list.php');
}


