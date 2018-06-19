<?php
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkLogin()){
    msg(2,'请登录！','../login.php');
}
$admin = $_SESSION['admin'];

//校验id
$userId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';

if(!$userId){
    msg(2, '非法参数！', 'list.php');
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
    msg(2, '该商品发布者不存在！', 'list.php');
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
        <i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="list.php">商品发布者管理</a> &raquo; 编辑商品发布者
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
        <form action="do_edit.php" method="post" name="register" id="register-form">
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
                    <th><i class="require">*</i>商品发布者性别：</th>
                    <td>
                        <input type="radio" name="user_sex" id="user_sex" value="0" <?php if ($users['user_sex'] == '0'): ?>checked="checked"<?php endif;?>>男
                        <input type="radio" name="user_sex" id="user_sex" value="1" <?php if ($users['user_sex'] == '1'):?>checked = "checked"<?php endif; ?>>女
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>商品发布者手机号：</th>
                    <td>
                        <input type="text" name="user_phone" placeholder="商品发布者手机号" id="user_phone" value="<?php echo $users['user_phone'];?>">
                        <span><i class="fa fa-exclamation-circle yellow"></i>商品发布者手机号必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>商品发布者邮编：</th>
                    <td>
                        <input type="text" name="user_postcode" placeholder="商品发布者邮编" id="user_postcode" value="<?php echo $users['user_postcode'];?>">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>商品发布者地址：</th>
                    <td>
                        <input type="text" name="user_address" placeholder="商品发布者地址" id="user_address" style="width: 250px" value="<?php echo $users['user_address'];?>">
                        <span><i class="fa fa-exclamation-circle yellow"></i>商品发布者地址必须填写</span>
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
                phone = $('#user_phone').val(),
                userpostcode = $('#user_postcode').val(),
                useraddress = $('#user_address').val();
            if (username == '' || username.length <= 0) {
                layer.tips('商品发布者名称不能为空', '#user_name', {time: 2000, tips: 2});
                $('#user_name').focus();
                return false;
            }
            if(phone == '' || phone.length != 11){
                layer.tips('商品发布者手机号为空或者手机号位数不是11位！', '#user_phone', {time:2000, tips:2});
                $('#user_phone').focus();
                return false;
            }
            if(userpostcode == '' || userpostcode.length != 6){
                layer.tips('商品发布者邮编不对，请输入六位邮编！', '#user_postcode', {time:2000, tips:2});
                $('#user_postcode').focus();
                return false;
            }
            if(useraddress == ''){
                layer.tips('商品发布者地址不能为空！','#user_address', {time:2000, tips:2});
                $('#user_address').focus();
                return false;
            }
            return true;
        })
    })
</script>
</html>