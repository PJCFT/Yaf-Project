<?php
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkLogin()){
    msg(2,'请登录！','../login.php');
}

$page = isset($_GET['page'])?intval($_GET['page']) : 1;

$page = max($page, 1);

$pageSize = 5;

$offset = ($page - 1)*$pageSize;

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}

$sql = "SELECT COUNT(`user_id`) as total from `nt_user`";
$obj = mysql_query($sql);
$result = mysql_fetch_assoc($obj);

$total = isset($result['total'])?$result['total']:0;

unset($sql, $obj, $result);

$sql = "SELECT * FROM `nt_user` ORDER BY `user_id` asc limit {$offset},{$pageSize} ";

$obj = mysql_query($sql);

$users = array();
while($result = mysql_fetch_assoc($obj))
{
    $users[] = $result;
}
//var_dump($total, $page, $pageSize);die;
$pages = pages($total, $page, $pageSize, 6);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../style/css/ch-ui.admin.css">
	<link rel="stylesheet" href="../style/font/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/css/common.css">
    <link rel="stylesheet" href="../style/css/index.css">
    <script type="text/javascript" src="../style/js/jquery.js"></script>
    <script type="text/javascript" src="../style/js/ch-ui.admin.js"></script>
    <script type="text/javascript" src="../style/js/jquery-1.10.2.min.js"></script>
</head>
<body>
<!--面包屑导航 开始-->
<div class="crumb_warp">
<!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
<i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="list.php">商品发布者管理</a> &raquo; 商品发布者列表
</div>
<!--面包屑导航 结束-->

<!--搜索结果页面 列表 开始-->
<form action="#" method="post">
<div class="result_wrap">
    <!--快捷导航 开始-->
    <div class="result_content">
        <div class="short_wrap">
            <a href="list.php"><i class="fa fa-recycle"></i>全部商品发布者</a>
            <a href="add.php"><i class="fa fa-plus"></i>添加商品发布者</a>
        </div>
    </div>
    <!--快捷导航 结束-->
</div>

<div class="result_wrap">

    <div class="result_content">
        <table class="list_tab">
            <tr>
                <th class="tc">ID</th>
                <th>商品发布者名称</th>
                <th>性别</th>
                <th>商品发布者手机号</th>
                <td>商品发布者邮编</td>
                <th>商品发布者地址</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            <?php foreach ($users as $u):?>
            <tr>
                <td class="tc"><?php echo $u['user_id'];?></td>
                <td>
                    <?php echo $u['user_name'];?>
                </td>
                <td>
                    <?php if($u['user_sex'] == '0'):?>
                        男
                    <?php elseif ($u['user_sex'] == '1'):?>
                        女
                    <?php endif;?>
                </td>
                <td>
                    <?php echo $u['user_phone'];?>
                </td>
                <td>
                    <?php echo $u['user_postcode'];?>
                </td>
                <td>
                    <?php echo $u['user_address'];?>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i:s',$u['create_time']);?>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i:s', $u['update_time'])?>
                </td>
                <td>
                    <a href="edit.php?id=<?php echo $u['user_id']?>">修改</a>
                    <a href="delete.php?id=<?php echo $u['user_id']?>" class="del">删除</a>
                </td>
            </tr>
            <?php endforeach;?>
        </table>
    </div>
    <?php echo $pages;?>
</div>
</form>
    <!--搜索结果页面 列表 结束-->



</body>
<script>
    $(function () {
        $('.del').on('click',function () {
            if(confirm('确认删除该商品发布者吗？')){
                window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>
</html>