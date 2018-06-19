<?php
/**
 * Created by PhpStorm.
 * User: Administrator
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

if(!empty($_POST['good_name'])){
    //数据库连接
    $con = mysqlInit($host, $Username, $Password, $dbName);
    if(!$con){
        echo mysql_errno();
        exit;
    }
    $goodname = mysql_real_escape_string(trim($_POST['good_name']));
    $goodprice = intval($_POST['good_price']);
    $goodauthor = mysql_real_escape_string(trim($_POST['good_author']));
    $gooddes = mysql_real_escape_string(trim($_POST['good_description']));
    $goodcontent = mysql_real_escape_string(trim($_POST['good_content']));

    $goodnameLength = mb_strlen($goodname, 'utf-8');
    if($goodnameLength <= 0 || $goodnameLength > 51){
        msg(2, '商品名应该在1到50个字以内，请重输！');
    }

    if($goodprice <= 0 || $goodprice > 9999999){
        msg(2, '商品价格应小于9999999');
    }
    if(empty($goodauthor) || $goodauthor != $user['user_name']){
        msg(2, '发布者不能为空,或者发布者被修改！');
    }

    $gooddesLength = mb_strlen($gooddes, 'utf-8');
    if($gooddesLength <= 0 || $gooddesLength > 201){
        msg(2, '商品简介应该在200字以内！');
    }

    if(empty($goodcontent)){
        msg(2, '商品详情不能为空！');
    }

    //建议大家做商品名称唯一性验证处理，通过在数据库中查询name的数量，然后进行判断处理
    $sql = "SELECT COUNT(  `name` ) as total FROM  `nt_goods` WHERE  `name` =  '{$goodname}'";
    $obj = mysql_query($sql);
    $result = mysql_fetch_assoc($obj);

    //验证商品已存在数据库
    if(isset($result['total']) && $result['total'] > 0){
        msg(2,'该商品已存在，请重新输入！');
    }
    //释放变量
    unset($sql,$obj,$result);
    //查询该发布者的id
    $sql = "SELECT `user_id`FROM `nt_user` WHERE `user_name` = '{$username1}' LIMIT 1";
    $obj = mysql_query($sql);
    $result = mysql_fetch_assoc($obj);
    $userId = $result['user_id'];

    unset($sql, $obj, $result);

    //图片上传处理
    $goodpic = imgUpload($_FILES['good_pic']);

    //入库处理
    $sql = "INSERT `nt_goods`(`name`,`price`,`des`,`content`,`pic`,`user_id`,`create_time`,`update_time`,`view`) values('{$goodname}','{$goodprice}','{$gooddes}','{$goodcontent}','{$goodpic}','{$userId}','{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_TIME']}',0)";
    if($obj = mysql_query($sql)){
        msg(1,'商品发布成功','user_goods_list.php');
    }else{
        msg(2, mysql_errno(), 'user_goods_list.php');
    }


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
    <i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="#">商品管理</a> &raquo; 商品
</div>
<!--面包屑导航 结束-->

<!--结果集标题与导航组件 开始-->
<div class="result_wrap">
    <div class="result_title">
        <h3>商品发布</h3>
    </div>
</div>
<!--结果集标题与导航组件 结束-->

<div class="result_wrap">
    <form action="user_publish.php" method="post" name="register" id="register-form" enctype="multipart/form-data">
        <table class="add_tab">
            <tbody>
            <tr>
                <th><i class="require">*</i>商品名称：</th>
                <td>
                    <input type="text" name="good_name" placeholder="输入商品名称" id="good_name">
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品名称必须填写</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品价格：</th>
                <td>
                <input type="text" name="good_price" placeholder="输入商品价格" id="good_price">
                <span><i class="fa fa-exclamation-circle yellow"></i>商品名称必须填写</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品：</th>
                <td>
                    <input type="text" name="good_author" id="good_author" value="<?php echo $user['user_name'];?>" readonly>
                    <span><i class="fa fa-exclamation-circle yellow"></i>该信息来自自身信息，此页面不支持更改！</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品图片：</th>
                <td>
                    <input type="file" name="good_pic" id="good_pic" accept="image/png,image/gif,image/jpeg">
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品图片必须有！</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品简介：</th>
                <td>
                    <textarea name="good_description" placeholder="输入商品简介" id="good_description"></textarea>
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品简介必须填写</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>商品详情：</th>
                <td>
                    <textarea name="good_content"  id="good_content" placeholder=""></textarea>
                    <span><i class="fa fa-exclamation-circle yellow"></i>商品详情必须填写</span>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
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
                gooddescription = $('#good_description').val(),
                goodpic = $('#good_pic').val();

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
            if (goodpic.length <= 0 || goodpic == '') {
                layer.tips('商品图片不能为空', '#good_pic', {time: 2000, tips: 2});
                $('#good_pic').focus();
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