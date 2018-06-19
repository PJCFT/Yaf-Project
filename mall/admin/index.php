<?php
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
include_once './lib/fun.php';
if(!checkLogin()){
    msg(2,'请登录！','login.php');
}
////商品发布者进行评定
//if(!checkUser_login()){
//    msg(2, '请登录', 'login.php');
//}
$admin = $_SESSION['admin'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style/css/ch-ui.admin.css">
	<link rel="stylesheet" href="style/font/css/font-awesome.min.css">
	<script type="text/javascript" src="style/js/jquery.js"></script>
    <script type="text/javascript" src="style/js/ch-ui.admin.js"></script>
</head>
<body>
	<!--头部 开始-->
	<div class="top_box">
		<div class="top_left">
			<div class="logo">商城管理系统</div>
			<ul>
				<li><a href="#" class="active">首页</a></li>
			</ul>
		</div>
		<div class="top_right">
			<ul>
				<li>你好，商品管理员：<?php echo $admin['admin_name'];?></li>
				<li><a href="#" target="main">修改密码</a></li>
				<li><a href="log_out.php">退出</a></li>
			</ul>
		</div>
	</div>
	<!--头部 结束-->

	<!--左侧导航 开始-->
	<div class="menu_box">
		<ul>
            <li>
            	<h3><i class="fa fa-fw fa-clipboard"></i>管理员管理</h3>
                <ul class="sub_menu">
                    <li><a href="admin/add.php" target="main"><i class="fa fa-fw fa-plus-square"></i>添加管理员</a></li>
                    <li><a href="admin/list.php" target="main"><i class="fa fa-fw fa-list-ul"></i>管理员列表</a></li>

                </ul>
            </li>
            <li>
                <h3><i class="fa fa-fw fa-clipboard"></i>发布者管理</h3>
                <ul class="sub_menu">
                    <li><a href="user/add.php" target="main"><i class="fa fa-fw fa-plus-square"></i>添加发布者</a></li>
                    <li><a href="user/list.php" target="main"><i class="fa fa-fw fa-list-ul"></i>发布者列表</a></li>

                </ul>
            </li>
            <li>
                <h3><i class="fa fa-fw fa-clipboard"></i>普通用户管理</h3>
                <ul class="sub_menu">
                    <li><a href="vistor/add.php" target="main"><i class="fa fa-fw fa-plus-square"></i>添加普通用户</a></li>
                    <li><a href="vistor/list.php" target="main"><i class="fa fa-fw fa-list-ul"></i>普通用户列表</a></li>

                </ul>
            </li>
            <li>
                <h3><i class="fa fa-fw fa-clipboard"></i>商品管理</h3>
                <ul class="sub_menu">
                    <li><a href="goods/list.php" target="main"><i class="fa fa-fw fa-list-ul"></i>商品列表</a></li>

                </ul>
            </li>
        </ul>
	</div>
	<!--左侧导航 结束-->

	<!--主体部分 开始-->
	<div class="main_box">
		<iframe src="info.php" frameborder="0" width="100%" height="100%" name="main"></iframe>
	</div>
	<!--主体部分 结束-->

	<!--底部 开始-->
	<div class="bottom_box">
		CopyRight © 2018. Powered By <a href="http://www.houdunwang.com">http://www.houdunwang.com</a>.
	</div>
	<!--底部 结束-->
</body>
</html>