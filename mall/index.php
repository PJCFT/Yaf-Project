<?php
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
include_once './home/lib/fun.php';
if($login = checkVisitor_Login()){
    $visitor = $_SESSION['visitor'];
}
//查询商品
//检查page参数
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
//把page与1对比 取中间最大值
$page = max($page, 1);
//每页显示条数
$pageSize = 6;

$offset = ($page - 1) * $pageSize;

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}

$sql = "SELECT COUNT(`id`) as total from `nt_goods`";
$obj = mysql_query($sql);
$result = mysql_fetch_assoc($obj);

$total = isset($result['total'])?$result['total']:0;

unset($sql,$result,$obj);
//只查询需要的数据
$sql = "SELECT `id`,`name`,`pic`,`des` FROM `nt_goods` ORDER BY `create_time` DESC ,`view` desc limit {$offset},{$pageSize} ";

$obj = mysql_query($sql);

$goods = array();
while($result = mysql_fetch_assoc($obj))
{
    $goods[] = $result;
}


$pages = pages($total,$page,$pageSize,6);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mall|首页</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div style="float:left;">
        <form  name="formsearch" action="./home/search.php" method="get">
            <input name="keywords" type="text" style="background-color: #60000000;padding-left: 10px; font-size: 12px;font-family: 'Microsoft Yahei'; color: #999999;height: 45px; width: 500px; border: solid 1px #666666; line-height: 28px;" id="go" value="在这里搜索.....填写完关键字按回车即可..." onfocus="if(this.value=='在这里搜索...'){this.value='';}"  onblur="if(this.value==''){this.value='在这里搜索...';}" />
        </form>
    </div>
    <div class="auth fr">
        <ul>
            <?php if ($login):?>
                <li><span>您好： <?php echo $visitor['visitor_name'];?></span></li>
                <li><a href="./home/pass.php?id=<?php echo $visitor['visitor_id'];?>">用户修改</a></li>
                <li><a href="./home/log_out.php">退出</a></li>
            <?php else: ?>
                <li><a href="./home/login.php">登录</a></li>
                <li><a href="./home/register.php">注册</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="content">
    <div class="banner">
        <img class="banner-img" src="./static/image/welcome.png" width="732px" height="372" alt="图片描述">
    </div>
    <div class="img-content">
        <ul>
            <?php foreach ($goods as $g): ?>
            <li>
                <img class="img-li-fix" src="<?php echo $g['pic'];?>" alt="<?php echo $g['name'];?>" title="<?php echo $g['name'];?>">
                <div class="info">
                    <a href="./home/detail.php?id=<?php echo $g['id'];?>"><h3 class="img_title"><?php echo $g['name'];?></h3></a>
                    <p>
                        <?php echo $g['des'];?>
                    </p>
                    <div class="btn">
                        <a href="./home/detail.php?id=<?php echo $g['id'];?>" class="edit">详情</a>
                        <a href="./home/buy.php?id=<?php echo $g['id'];?>" class="del">联系卖家</a>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php echo $pages; ?>
</div>

<div class="footer">
    <p><span>Mall</span>©2018 POWERED BY PJC</p>
</div>
</body>
</html>
