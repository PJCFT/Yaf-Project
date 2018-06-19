<?php
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
include_once '../lib/fun.php';
if(!checkUser_login()){
    msg(2,'请登录！','../login.php');
}

$user = $_SESSION['user'];
$username = $user['user_name'];

//数据库连接
$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}
//查询发布者的id
$sql = "SELECT `user_id`FROM `nt_user` WHERE `user_name` = '{$username}' LIMIT 1";
$obj = mysql_query($sql);
$userId = mysql_fetch_assoc($obj);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../style/css/ch-ui.admin.css">
	<link rel="stylesheet" href="../style/font/css/font-awesome.min.css">
	<script type="text/javascript" src="../style/js/jquery.js"></script>
    <script type="text/javascript" src="../style/js/ch-ui.admin.js"></script>
</head>
<body>
	<!--头部 开始-->
	<div class="top_box">
		<div class="top_left">
			<div class="logo">商城发布者管理系统</div>
			<ul>
				<li><a href="#" class="active">首页</a></li>
			</ul>
		</div>
		<div class="top_right">
			<ul>
				<li>你好，商品发布者：<?php echo $user['user_name'];?></li>
				<li><a href="pass.php?id=<?php echo $userId['user_id'];?>" target="main">修改密码</a></li>
				<li><a href="user_logout.php">退出</a></li>
			</ul>
		</div>
	</div>
	<!--头部 结束-->

	<!--左侧导航 开始-->
	<div class="menu_box">
		<ul>
            <li>
            	<h3><i class="fa fa-fw fa-clipboard"></i>基本操作</h3>
                <ul class="sub_menu">
                    <li><a href="user_common.php?id=<?php echo $userId['user_id'];?>" target="main"><i class="fa fa-fw fa-plus-square"></i>基本信息修改</a></li>
                    <li><a href="user_publish.php" target="main"><i class="fa fa-fw fa-list-ul"></i>发布商品</a></li>
                    <li><a href="user_goods_list.php" target="main"><i class="fa fa-fw fa-list-ul"></i>商品列表管理</a></li>

                </ul>
            </li>
        </ul>
	</div>
	<!--左侧导航 结束-->

	<!--主体部分 开始-->
	<div class="main_box">
		<iframe src="../info.php" frameborder="0" width="100%" height="100%" name="main"></iframe>
	</div>
	<!--主体部分 结束-->

	<!--底部 开始-->
	<div class="bottom_box">
		CopyRight © 2018. Powered By <a href="#">PJC</a>.
	</div>
	<!--底部 结束-->
</body>
</html>