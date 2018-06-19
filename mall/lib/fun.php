<?php
/*
 * 数据库的连接
 * **/
function mysqlInit($host, $username, $password, $dbName){
    $con = @mysql_connect($host, $username, $password);
    if(!$con){
        return false;
    }
    mysql_select_db($dbName);
    //设置字符集
    mysql_set_charset('utf8');

    return $con;
}

/*
 * 对密码进行加密
 * **/
function createPassword($password){
    if(!$password){
        return false;
    }
    return md5(md5($password).'MALL');
}

/**
 * 消息提示
 * @param int $type 1：成功 2：失败
 * @param null $msg
 * @param null $url
 */
function msg($type, $msg = null,$url = null ){
    $toUrl = "Location:msg.php?type= {$type}";
    //当msg为空时，url不写入
    $toUrl .= $msg?"&msg={$msg}":'';
    //当url为空是，toUrl不写入
    $toUrl .=$url?"&url={$url}":'';
    header($toUrl);
    exit;
}

/**
 * 图像上传
 * @param $file
 * @return string
 */
function imgUpload($file){
    //检查上传文件是否合法
    if(!is_uploaded_file($file['tmp_name'])){
        msg(2,'请上传符合规范的图像！');
    }
    //图像类型验证
    $type = $file['type'];
    if(!in_array($type, array("image/png", "image/gif", "image/jpeg")))
    {
        msg(2, '请上传png,gif,jpg的图像');
    }
    //上传目录
    $uploadPath = './static/file/';
    //上传目录访问url
    $uploadUrl = '/static/file/';
    //上传文件夹
    $fileDir = date('Y/md/',$_SERVER['REQUEST_TIME']);

    //检查上传目录是否存在
    if(!is_dir($uploadPath . $fileDir))
    {
        mkdir($uploadPath . $fileDir, 0755, true);//递归创建目录
    }

    //获取文件后缀
    $ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
    //上传图像名称
    $img = uniqid().mt_rand(1000,9999).'.'.$ext;
    //物理地址
    $imgPath = $uploadPath.$fileDir.$img;
    //url地址
    $imgUrl = $_SERVER['SERVER_NAME'] . $uploadUrl.$fileDir.$img;
    //$imgUrl = 'http://mall.hd' . $uploadUrl.$fileDir.$img;//url地址
    //操作失败，查看上传目录的权限
    if(!move_uploaded_file($file['tmp_name'], $imgPath)){
        msg(2, '服务器繁忙，请稍后再试！');
    }
    return $imgUrl;
}
/*检查用户是否登录
 * */
function checkLogin(){
    //开启session
    session_start();
    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
        return false;
    }
    return true;
}

