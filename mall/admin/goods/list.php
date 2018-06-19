<?php
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkLogin()){
    msg(2,'请登录！','../login.php');
}
$admin = $_SESSION['admin'];

$page = isset($_GET['page'])?intval($_GET['page']) : 1;

$page = max($page, 1);

$pageSize = 5;

$offset = ($page - 1)*$pageSize;

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}

$sql = "SELECT COUNT(`id`) as total from `nt_goods`";
$obj = mysql_query($sql);
$result = mysql_fetch_assoc($obj);

$total = isset($result['total'])?$result['total']:0;

unset($sql, $obj, $result);

//查询所有发布者
$sql1 = "SELECT `user_id`,`user_name` FROM `nt_user`";
$obj1 = mysql_query($sql1);
$users1 = array();
while ($result1 = mysql_fetch_assoc($obj1)){
    $users1[] = $result1;
}

$sql = "SELECT `id`,`name`,`price`,`pic`,`user_id`,`update_time` FROM `nt_goods` ORDER BY `create_time` DESC limit {$offset},{$pageSize} ";

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
<i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="list.php">商品管理</a> &raquo; 商品列表
</div>
<!--面包屑导航 结束-->

<!--搜索结果页面 列表 开始-->
<form action="#" method="post">
<div class="result_wrap">
    <!--快捷导航 开始-->
    <div class="result_content">
        <div class="short_wrap">
            <a href="list.php"><i class="fa fa-plus"></i>全部商品</a>
        </div>
    </div>
    <!--快捷导航 结束-->
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
                <th>商品发布时间</th>
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
                    <img src="<?php echo $g['pic'];?>" style="height:45px;">
                </td>
                <td>
<!--                    将对应的发布者显示出来，而不是只显示对应的发布者id-->
                    <?php foreach ($users1 as $u): ?>
                        <?php if ($g['user_id'] == $u['user_id']):?>
                            <?php echo $u['user_name'];?>
                        <?php endif;?>
                    <?php endforeach; ?>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i:s', $g['update_time'])?>
                </td>
                <td>
                    <a href="delete.php?id=<?php echo $g['id']?>" class="del">删除</a>
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