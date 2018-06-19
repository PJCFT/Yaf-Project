<?php
include_once './lib/fun.php';
if(!checkLogin()){
    msg(2,'请登录','login.php');
}
$user = $_SESSION['user'];//获取管理员信息

//表单进行了提交
if(!empty($_POST['name'])){
    //数据库连接
    $con = mysqlInit('127.0.0.1', 'root', 'root', 'native_mall');
    if(!$con){
        echo mysql_errno();
        exit;
    }
    //商品名称
    $name = mysql_real_escape_string(trim($_POST['name']));
    //商品价格
    $price = intval($_POST['price']);
    //商品简介
    $des = mysql_real_escape_string(trim($_POST['des']));
    $content = mysql_real_escape_string(trim($_POST['content']));

    //建议大家做商品名称唯一性验证处理，通过在数据库中查询name的数量，然后进行判断处理
    $sql = "SELECT COUNT(  `name` ) as total FROM  `nt_goods` WHERE  `name` =  '{$name}'";
    $obj = mysql_query($sql);
    $result = mysql_fetch_assoc($obj);

    //验证商品已存在数据库
    if(isset($result['total']) && $result['total'] > 0){
        msg(2,'商品名已存在，请重新输入！');
    }
    //释放变量
    unset($sql,$obj,$result);

    $nameLength = mb_strlen($name,'utf-8');
    if($nameLength <= 0 || $nameLength > 30){
        msg(2,'商品应在1-30字符之内！');
    }

    if($price <= 0 || $price > 999999999){
        msg(2, '商品价格应小于999999999');
    }

    $desLength = mb_strlen($des,'utf-8');
    if($desLength <= 0 || $desLength > 100){
        msg(2, '商品简介应在1-100字符之内！');
    }

    if(empty($content)){
        msg(2, '商品详情不能为空');
    }

    $userId = $user['user_id'];
    $now = $_SERVER['REQUEST_TIME'];
    //图像上传
    $pic = imgUpload($_FILES['file']);
    //入库处理
    $sql = "INSERT `nt_goods`(`name`,`price`,`des`,`content`,`pic`,`user_id`,`create_time`,`update_time`,`view`) values('{$name}','{$price}','{$des}','{$content}','{$pic}','{$userId}','{$now}','{$now}',0)";
    if($obj = mysql_query($sql)){
        msg(1,'操作成功','index.php');
    }else{
        echo mysql_error();exit;
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|发步商品</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><span>管理员: <?php echo $user['user_name'];?></span></li>
            <li><a href="login_out.php">退出</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="addwrap">
        <div class="addl fl">
            <header>发布商品</header>
            <form name="publish-form" id="publish-form" action="publish.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">商品名称</label><input type="text" name="name" id="name" placeholder="请输入商品名称">
                </div>
                <div class="additem">
                    <label id="for-price">价值</label><input type="text" name="price" id="price" placeholder="请输入商品价值">
                </div>
                <div class="additem">
                    <!-- 使用accept html5属性 声明仅接受png gif jpeg格式的文件                -->
                    <label id="for-file">商品</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file"
                                                          name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">商品简介</label><textarea id="des" name="des"
                                                                           placeholder="请输入商品简介"></textarea>
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-content">商品详情</label>
                    <div style="margin-left: 120px" id="container">
                        <textarea id="content" name="content"></textarea>
                    </div>

                </div>
                <div style="margin-top: 20px">
                    <button type="submit">发布</button>
                </div>

            </form>
        </div>
        <div class="addr fr">
            <img src="./static/image/index_banner.png">
        </div>
    </div>

</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script src="./static/js/kindeditor/kindeditor-all-min.js"></script>
<script src="./static/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var K = KindEditor;
    K.create('#content', {
        width      : '475px',
        height     : '400px',
        minWidth   : '30px',
        minHeight  : '50px',
        items      : [
            'undo', 'redo', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'clearhtml',
            'fontsize', 'forecolor', 'bold',
            'italic', 'underline', 'link', 'unlink', '|'
            , 'fullscreen'
        ],
        afterCreate: function () {
            this.sync();
        },
        afterChange: function () {
            //编辑器失去焦点时直接同步，可以取到值
            this.sync();
        }
    });
</script>

<script>
    $(function () {
        $('#publish-form').submit(function () {
            var name = $('#name').val(),
                price = $('#price').val(),
                file = $('#file').val(),
                des = $('#des').val(),
                content = $('#content').val();
            if (name.length <= 0 || name.length > 30) {
                layer.tips('商品名应在1-30字符之内', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            //验证为正整数
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('请输入最多9位正整数', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }

            if (file == '' || file.length <= 0) {
                layer.tips('请选择图片', '#file', {time: 2000, tips: 2});
                $('#file').focus();
                return false;

            }

            if (des.length <= 0 || des.length >= 100) {
                layer.tips('商品简介应在1-100字符之内', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }

            if (content.length <= 0) {
                layer.tips('请输入商品详情信息', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;

        })
    })
</script>
</html>
