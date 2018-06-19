<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/5
 * Time: 8:17
 */
include_once './lib/fun.php';
session_start();
unset($_SESSION['visitor']);
msg(1, '退出登录成功！','../index.php');