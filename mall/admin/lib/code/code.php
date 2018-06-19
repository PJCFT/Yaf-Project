<?php
session_start();
require_once 'Code.class.php';

$code = new \Code();
$code->make();