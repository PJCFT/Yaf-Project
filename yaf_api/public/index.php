<?php

define('APP_PATH', dirname(__FILE__).'/../');/* 指向public的上一级*/

$application = new Yaf_Application( APP_PATH . "/conf/application.ini", ini_get('yaf.environ'));


$application->bootstrap()->run();
?>
