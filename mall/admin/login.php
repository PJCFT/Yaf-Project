<?php
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
include_once './lib/fun.php';
require_once './lib/code/Code.class.php';
if (checkLogin()){
    msg(1,'您已登录！','index.php');
}

if(!empty($_POST['username'])){
    /*
     * 数据处理
     * **/
    $role = trim($_POST['login_role']);
    $code = trim($_POST['code']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(!$code){
        msg(2,'验证码不能为空！');

    }
    if(!$username){
        msg(2,'用户名不能为空！');
    }
    if(!$password){
        msg(2,'密码不能为空！');
    }
    //数据库连接
    $con = mysqlInit('127.0.0.1', 'root', 'root', 'native_mall');
    if(!$con){
        echo mysql_errno();
        exit;
    }
    $_code = new \Code;
    $code1 = $_code->get();
    if(strtoupper($code) != $code1){
        msg(2, '验证码不正确！');
    }

    //对登录角色处理登录 '0':是管理员，'1'是商品发布者
    if($role == '0'){
        //根据用户名查询用户
        $sql = "SELECT * FROM `nt_admin` WHERE `admin_name` = '{$username}' LIMIT 1";
        $obj = mysql_query($sql);
        $result = mysql_fetch_assoc($obj);


        if(is_array($result) && !empty($result)){
            if(createPassword($password) === $result['admin_password']){
                $_SESSION['admin'] = $result;
                msg(1, '登录成功！','index.php');
                exit;
            }else{
                msg(2,'密码不正确，请重新输入！');
            }
        }else{
            msg(2,'管理员不存在，请重新输入！');
        }
    }
    else if($role == '1'){
        //根据用户名查询用户
        $sql = "SELECT * FROM `nt_user` WHERE `user_name` = '{$username}' LIMIT 1";
        $obj = mysql_query($sql);
        $result = mysql_fetch_assoc($obj);


        if(is_array($result) && !empty($result)){
            if(createPassword($password) === $result['user_password']){
                $_SESSION['user'] = $result;
                msg(1, '登录成功！','user_mag/user_index.php');
                exit;
            }else{
                msg(2,'密码不正确，请重新输入！');
            }
        }else{
            msg(2,'该商品发布者不存在，请重新输入！');
        }
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style/css/ch-ui.admin.css">
	<link rel="stylesheet" href="style/font/css/font-awesome.min.css">
</head>
<body style="background:#F3F3F4;">
	<div class="login_box">
		<h1>Shop</h1>
		<h2>欢迎使用商品管理平台</h2>
		<div class="form">
			<form action="login.php" method="post">
				<ul>
					<li>
					<input type="text" name="username" class="text"/>
						<span><i class="fa fa-user"></i></span>
					</li>
					<li>
						<input type="password" name="password" class="text"/>
						<span><i class="fa fa-lock"></i></span>
					</li>
					<li>
						<input type="text" class="code" name="code"/>
						<span><i class="fa fa-check-square-o"></i></span>
                        <img src="./lib/code/code.php" alt="" onclick="this.src='./lib/code/code.php?'+Math.random()" style="cursor: pointer">
                    </li>
                    <li style="margin-top: 15px;margin-bottom: 15px">
                        <input type="radio" name="login_role" id="login_role" value="0" checked="checked"/>商品管理员
                        <input type="radio" name="login_role" id="login_role" value="1"/>商品发布者
                    </li>
					<li>
						<input type="submit" value="立即登陆"/>
					</li>
                    <li>
                        <a href="user_mag/user_register.php" style="float: right;margin-top: 7px;">注册商品发布者</a><br/>
                    </li>
				</ul>
			</form>
			<p><a href="#">返回首页</a> &copy; 2018 Powered by PJC</p>
		</div>
	</div>
</body>
</html>