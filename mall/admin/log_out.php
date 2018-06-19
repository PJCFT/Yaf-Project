<?php
include_once './lib/fun.php';
session_start();
//释放user
unset($_SESSION['admin']);
msg(1,'退出登录成功！','index.php');