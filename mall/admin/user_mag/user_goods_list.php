<?php
/**
 * Created by PhpStorm.
 * User: PJC
 * Date: 2018/3/31
 * Time: 15:27
 */
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkUser_login()){
    msg(2,'请登录！','../login.php');
}
$user = $_SESSION['user'];
$username1 = $user['user_name'];

$page = isset($_GET['page'])?intval($_GET['page']) : 1;

$page = max($page, 1);

$pageSize = 5;

$offset = ($page - 1)*$pageSize;

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}
//查询对应的发布者id
$sql = "SELECT `user_id` FROM `nt_user` WHERE `user_name` = '{$username1}' LIMIT 1";
$obj = mysql_query($sql);
$result = mysql_fetch_assoc($obj);
$userId = $result['user_id'];

unset($sql, $obj, $result);

$sql = "SELECT COUNT(`id`) as total from `nt_goods` WHERE `user_id` = '{$userId}'";
$obj = mysql_query($sql);
$result = mysql_fetch_assoc($obj);

$total = isset($result['total'])?$result['total']:0;

unset($sql, $obj, $result);

$sql = "SELECT `id`,`name`,`price`,`pic`,`user_id`,`update_time` FROM `nt_goods` WHERE `user_id` = '{$userId}' ORDER BY `create_time` DESC limit {$offset},{$pageSize} ";

$obj = mysql_query($sql);

$goods = array();
while($result = mysql_fetch_assoc($obj))
{
    $goods[] = $result;
}
//var_dump($total, $page, $pageSize);die;
$pages = pages($total, $page, $pageSize, 6);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style/css/ch-ui.admin.css">
    <link rel="stylesheet" href="../style/font/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/css/common.css">
    <link rel="stylesheet" href="../style/css/index.css">
    <script type="text/javascript" src="../style/js/jquery.js"></script>
    <script type="text/javascript" src="../style/js/ch-ui.admin.js"></script>
    <script type="text/javascript" src="../style/js/jquery-1.10.2.min.js"></script>
</head>
<body>
<!--面包屑导航 开始-->
<div class="crumb_warp">
    <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
    <i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="#">商品管理</a> &raquo; 商品列表
</div>
<!--面包屑导航 结束-->

<!--搜索结果页面 列表 开始-->
<form action="#" method="post">
    <div class="result_wrap">
        <h3>商品发布列表</h3>
    </div>

    <div class="result_wrap">

        <div class="result_content">
            <table class="list_tab">
                <tr>
                    <th class="tc">ID</th>
                    <th>商品名称</th>
                    <th>商品价格</th>
                    <th>商品缩略图</th>
                    <th>商品发布者</th>
                    <th>商品最新发布时间</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($goods as $g):?>
                    <tr>
                        <td class="tc"><?php echo $g['id'];?></td>
                        <td>
                            <?php echo $g['name'];?>
                        </td>
                        <td>
                            <?php echo $g['price'];?>
                        </td>
                        <td>
                            <img src="<?php echo $g['pic'];?>" style="height: 40px;">
                        </td>
                        <td>
                            <?php echo $username1;?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s', $g['update_time'])?>
                        </td>
                        <td>
                            <a href="user_goods_edit.php?id=<?php echo $g['id']?>">修改</a>
                            <a href="user_delete.php?id=<?php echo $g['id']?>" class="del">删除</a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>
        <?php echo $pages;?>
    </div>
</form>
<!--搜索结果页面 列表 结束-->



</body>
<script>
    $(function () {
        $('.del').on('click',function () {
            if(confirm('确认删除该商品吗？')){
                window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>
</html>