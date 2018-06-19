<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/5
 * Time: 9:33
 */
include_once './lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkVisitor_Login()){
    msg(2,'请登录！','login.php');
}
//校验id
$visitorId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';

if(!$visitorId){
    msg(2, '非法参数！');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_error();
    exit;
}
//检索普通用户信息
$sql = "SELECT * FROM `nt_visitor` WHERE `visitor_id` = {$visitorId}";
$obj = mysql_query($sql);
$visitors = mysql_fetch_assoc($obj);
if(!$visitors){
    msg(2, '该用户不存在！');
}


?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>

    <meta charset="UTF-8">
    <title>Mall|用户密码修改</title>
    <link type="text/css" rel="stylesheet" href="../static/css/common.css">
    <link type="text/css" rel="stylesheet" href="../static/css/add.css">
    <link rel="stylesheet" type="text/css" href="../static/css/login.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="../static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="../static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>用户修改</p>
                    </div>
                    <form class="login-table" name="register" id="register-form" action="do_pass.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名：</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username" value="<?php echo $visitors['visitor_name'];?>">
                        </div>
                        <div class="login-left">
                            <label class="username">新密码：</label>
                            <input type="password" class="yhmiput" name="newpassword" placeholder="Newpassword" id="newpassword">
                        </div>
                        <div class="login-left">
                            <label class="username">确认密码</label>
                            <input type="password" class="yhmiput" name="repassword" placeholder="Repassword" id="repassword">
                        </div>
                        <div class="login-btn">
                            <input type="hidden" name="id" value="<?php echo $visitors['visitor_id'];?>">
                            <button type="submit">提交修改</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>Mall</span> ©2018 POWERED BY PJC</p>
</div>

</body>
<script src="../static/js/jquery-1.10.2.min.js"></script>
<script src="../static/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var visitorname = $('#username').val(),
                newpassword = $('#newpassword').val(),
                repassword = $('#repassword').val();
            if (visitorname == '' || visitorname.length <= 0) {
                layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }
            if (newpassword == '' || newpassword.length <= 5) {
                layer.tips('密码过短，至少六位！', '#newpassword', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }
            if (repassword == '' || repassword.length <= 0 || (newpassword != repassword)) {
                layer.tips('两次密码输入不一致', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }

            return true;
        })

    })
</script>
</html>
