<?php
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkUser_login()){
    msg(2,'请登录！','../login.php');
}
$user = $_SESSION['user'];

//校验id
$userId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';

if(!$userId){
    msg(2, '非法参数！');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_error();
    exit;
}
//检索商品发布者信息
$sql = "SELECT * FROM `nt_user` WHERE `user_id` = {$userId}";
$obj = mysql_query($sql);
$users = mysql_fetch_assoc($obj);
if(!$users){
    msg(2, '该商品发布者不存在！');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../style/css/ch-ui.admin.css">
	<link rel="stylesheet" href="../style/font/css/font-awesome.min.css">
</head>
<body>
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="#">商品发布者管理</a> &raquo; 编辑商品发布者
    </div>
    <!--面包屑导航 结束-->

	<!--结果集标题与导航组件 开始-->
	<div class="result_wrap">
        <div class="result_title">
            <h3>编辑商品发布者</h3>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="reset_pass.php" method="post" name="register" id="register-form">
            <table class="add_tab">
                <tbody>
                <tr>
                    <th><i class="require">*</i>商品发布者名称：</th>
                    <td>
                        <input type="text" name="user_name" placeholder="商品发布者名称" id="user_name" value="<?php echo $users['user_name'];?>">
                        <span><i class="fa fa-exclamation-circle yellow"></i>商品发布者名称必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>新密码：</th>
                    <td>
                        <input type="password" name="user_newpass" placeholder="商品发布者新密码" id="user_newpass">
                        <span><i class="fa fa-exclamation-circle yellow"></i>商品发布者密码必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>确认新密码：</th>
                    <td>
                        <input type="password" name="user_repass" placeholder="确认新密码" id="user_repass">
                        <span><i class="fa fa-exclamation-circle yellow"></i>再次输入商品发布者新密码</span>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $users['user_id'];?>">
                        <input type="submit" value="提交" >
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
<script src="../style/js/jquery-1.10.2.min.js"></script>
<script src="../style/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var username = $('#user_name').val(),
                password = $('#user_newpass').val(),
                repassword = $('#user_repass').val();
            if (username == '' || username.length <= 0) {
                layer.tips('商品发布者名称不能为空', '#user_name', {time: 2000, tips: 2});
                $('#user_name').focus();
                return false;
            }
            if (password == '' || password.length <= 0) {
                layer.tips('商品发布者新密码不能为空', '#user_newpass', {time: 2000, tips: 2});
                $('#user_newpass').focus();
                return false;
            }
            if ( password.length <= 5 || password.length > 30) {
                layer.tips('商品发布者新密码过短，应在6-30个字符之间', '#user_newpass', {time: 2000, tips: 2});
                $('#user_newpass').focus();
                return false;
            }

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#user_repass', {time: 2000, tips: 2});
                $('#user_repass').focus();
                return false;
            }
            return true;
        })
    })
</script>
</html>