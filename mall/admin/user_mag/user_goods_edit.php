<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2
 * Time: 8:23
 */
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkUser_login()){
    msg(2,'请登录！','../login.php');
}
$user = $_SESSION['user'];

//校验id
$goodId = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';

if(!$goodId){
    msg(2, '非法参数！');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_error();
    exit;
}

//检索商品信息
$sql = "SELECT * FROM `nt_goods` WHERE `id` = {$goodId}";
$obj = mysql_query($sql);
$goods = mysql_fetch_assoc($obj);
if(!$goods){
    msg(2, '该商品不存在！');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style/css/ch-ui.admin.css">
    <link rel="stylesheet" href="../style/font/css/font-awesome.min.css">

    <!--    导入ueditorjs-->
    <script type="text/javascript" src="../style/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="../style/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" src="../style/ueditor/lang/zh-cn/zh-cn.js"></script>

</head>
<body>
<!--面包屑导航 开始-->
<div class="crumb_warp">
    <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
    <i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="#">商品发布者管理</a> &raquo; 商品发布者
</div>
<!--面包屑导航 结束-->

<!--结果集标题与导航组件 开始-->
<div class="result_wrap">
    <div class="result_title">
        <h3>商品编辑</h3>
    </div>
</div>
<!--结果集标题与导航组件 结束-->

<div class="result_wrap">
    <form action="user_goods_do_edit.php" method="post" name="register" id="register-form" enctype="multipart/form-data">
        <table class="add_tab">
            <tbody>
            <tr>
                <th><i class="require">*</i>商品名称：</th>
                <td>
                    <input type="text" name="good_name" placeholder="输入商品名称" id="good_name" value="<?php echo $goods['name'];?>">
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品名称必须填写</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品价格：</th>
                <td>
                    <input type="text" name="good_price" placeholder="输入商品价格" id="good_price" value="<?php echo $goods['price'];?>">
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品名称必须填写</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品发布者：</th>
                <td>
                    <input type="text" name="good_author" id="good_author" value="<?php echo $user['user_name'];?>" readonly>
                    <span><i class="fa fa-exclamation-circle yellow"></i>该信息来自自身信息，此页面不支持更改！</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品图片：</th>
                <td>
                    <img src="<?php echo $goods['pic'];?>" style="height:50px;"/><br>
                    <input type="file" name="good_pic" id="good_pic" accept="image/png,image/gif,image/jpeg">
<!--                    <span><i class="fa fa-exclamation-circle yellow"></i>商品图片必须有！</span>-->
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品简介：</th>
                <td>
                    <textarea name="good_description" placeholder="输入商品简介" id="good_description"><?php echo $goods['des'];?></textarea>
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品简介必须填写</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品详情：</th>
                <td>
                    <textarea name="good_content"  id="good_content"placeholder=""><?php echo $goods['content'];?></textarea>
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品详情必须填写</span>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <input type="hidden" name="id" value="<?php echo $goodId;?>">
                    <input type="submit" value="提交发布">
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
<script type="text/javascript">
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    UE.getEditor('good_content',{initialFrameWidth:800,initialFrameHeight:400,});

</script>

<script>
    $(function () {
        $('#register-form').submit(function () {
            var goodname = $('#good_name').val(),
                goodprice = $('#good_price').val(),
                gooddescription = $('#good_description').val();

            if (goodname.length <= 0 || goodname > 51) {
                layer.tips('商品名称不能为空', '#good_name', {time: 2000, tips: 2});
                $('#good_name').focus();
                return false;
            }
            if(goodprice =='' || goodprice <= 0 || goodprice > 9999999){
                layer.tips('商品价格应该在1--9999999之间！', '#good_price', {time:2000, tips:2});
                $('#good_price').focus();
                return false;
            }
            if(gooddescription == ''|| gooddescription.length <=0 || gooddescription.length > 201){
                layer.tips('商品简介应在1--200字之间！','#good_description', {time:2000, tips:2});
                $('#good_description').focus();
                return false;
            }
            return true;
        })
    })
</script>
</html>