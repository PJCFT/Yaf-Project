<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 8:22
 */
class BaseModel{
    public $error = 0;
    public $errmsg = "";
    private $_db;

    public function __construct(){
        $conf = Yaf_Application::app()->getConfig();
        if(!empty($conf)){
            $Hostname = $conf->database->params->hostname;
            $Dbname = $conf->database->params->database;
            $Username = $conf->database->params->username;
            $Password = $conf->database->params->password;
            $dns = "mysql:host="."$Hostname".";"."dbname="."$Dbname".";";
            $this->_db = new PDO($dns, $Username, $Password);
            if (!$this->_db){
                $this->error = 1000;
                $this->errmsg = "数据库连接失败";
                return false;
            }
        }else{
            $this->error = 1000;
            $this->errmsg = "数据库连接失败";
            return false;
        }
    }


}