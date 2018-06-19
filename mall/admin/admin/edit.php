<?php
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkLogin()){
    msg(2,'请登录！','../login.php');
}
$admin = $_SESSION['admin'];

//校验id
$adminId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';

if(!$adminId){
    msg(2, '非法参数！', 'list.php');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_error();
    exit;
}
//检索管理员信息
$sql = "SELECT * FROM `nt_admin` WHERE `admin_id` = {$adminId}";
$obj = mysql_query($sql);
$admins = mysql_fetch_assoc($obj);
if(!$admins){
    msg(2, '管理员不存在！', 'list.php');
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
        <i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="list.php">管理员管理</a> &raquo; 编辑管理员
    </div>
    <!--面包屑导航 结束-->

	<!--结果集标题与导航组件 开始-->
	<div class="result_wrap">
        <div class="result_title">
            <h3>编辑管理员</h3>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="do_edit.php" method="post" name="register" id="register-form">
            <table class="add_tab">
                <tbody>
                <tr>
                    <th><i class="require">*</i>管理员名称：</th>
                    <td>
                        <input type="text" name="user_name" placeholder="管理员名称" id="user_name" value="<?php echo $admins['admin_name'];?>">
                        <span><i class="fa fa-exclamation-circle yellow"></i>管理员名称必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>管理员手机号：</th>
                    <td>
                        <input type="text" name="user_phone" placeholder="管理员手机号" id="user_phone" value="<?php echo $admins['admin_phone'];?>">
                        <span><i class="fa fa-exclamation-circle yellow"></i>管理员手机号必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>旧密码：</th>
                    <td>
                        <input type="password" name="user_oldpass" placeholder="管理员旧密码" id="user_oldpass">
                        <span><i class="fa fa-exclamation-circle yellow"></i>旧密码必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>新密码：</th>
                    <td>
                        <input type="password" name="user_newpass" placeholder="管理员新密码" id="user_newpass">
                        <span><i class="fa fa-exclamation-circle yellow"></i>管理员密码必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>确认新密码：</th>
                    <td>
                        <input type="password" name="user_repass" placeholder="确认新密码" id="user_repass">
                        <span><i class="fa fa-exclamation-circle yellow"></i>再次输入管理员新密码</span>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $admins['admin_id'];?>">
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
<script src="../style/js/md5.js"></script>
<script src="../style/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var username = $('#user_name').val(),
                oldpassword = $('#user_oldpass').val(),
                password = $('#user_newpass').val(),
                repassword = $('#user_repass').val(),
                phone = $('#user_phone').val();
            var real_oldpassword,oldpassword1,oldpassword2,oldpassword3;
                real_oldpassword = '<?php echo $admins['admin_password']?>';
                oldpassword1 = hex_md5(oldpassword)+'MALL';
                oldpassword2 = hex_md5(oldpassword1);
            if (username == '' || username.length <= 0) {
                layer.tips('管理员名称不能为空', '#user_name', {time: 2000, tips: 2});
                $('#user_name').focus();
                return false;
            }
            if(phone == '' || phone.length != 11){
                layer.tips('管理员手机号为空或者手机号位数不是11位！', '#user_phone', {time:2000, tips:2});
                $('#user_phone').focus();
                return false;
            }
            if (oldpassword2 != real_oldpassword){
                layer.tips('旧密码错误，请重输！', '#user_oldpass', {time:2000, tips: 2});
                $('#user_oldpass').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('管理员新密码不能为空', '#user_newpass', {time: 2000, tips: 2});
                $('#user_newpass').focus();
                return false;
            }
            if ( password.length <= 5 || password.length > 30) {
                layer.tips('管理员新密码过短，应在6-30个字符之间', '#user_newpass', {time: 2000, tips: 2});
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