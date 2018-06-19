<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/5
 * Time: 9:13
 */
include_once './lib/fun.php';
if(!checkVisitor_Login()){
    msg(2,'请登录进行购买！','login.php');
}
echo 'buying... please wait';