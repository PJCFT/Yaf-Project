<?php
include_once './lib/fun.php';
if (!checkLogin()){
    msg(2,'请登录','login.php');
}
//校验url中商品id
$goodsId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';
//跳转到商品列表，如果id不存在
if(!$goodsId){
    msg(2,'参数非法','index.php');
}
//数据库连接
$con = mysqlInit('127.0.0.1', 'root', 'root', 'native_mall');
if(!$con){
    echo mysql_errno();
    exit;
}
//根据商品id查询商品信息
$sql = "SELECT 'id' FROM `nt_goods` WHERE `id` = {$goodsId}";
$obj = mysql_query($sql);

//当根据id查询商品信息为空时，跳转商品列表页
if(!$goods = mysql_fetch_assoc($obj)){
    msg(2,'商品不存在','index.php');
}

//删除处理
$sql = "DELETE FROM `nt_goods` where `id` = {$goodsId} LIMIT 1";

if($result = mysql_query($sql))
{
    //mysql_affected_rows()
    msg(1, '操作成功', 'index.php');
}
else
{
    msg(2, '操作失败', 'index.php');
}
//注意项
//1.项目中 不会真正删除商品 而是更新商品表中的status 1:正常操作 -1:删除操作
//2 增加商品编辑时 update_time