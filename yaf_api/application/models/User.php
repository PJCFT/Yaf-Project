<?php

/**
 * @uname post过来的用户名
 * @pwd post过来的密码
 * Class UserModel
 */
class UserModel {
    public $errno = 0;
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
                $this->errno = -1000;
                $this->errmsg = "数据库连接失败";
                return false;
            }
            //不设置下面这行的话，PDO会在拼SQL时候，把int 0转成string 0
            $this->_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }else{
            $this->errno = -1000;
            $this->errmsg = "数据库连接失败";
            return false;
        }
    }
    public function login($uname, $pwd){
        //对登录用户的查找验证
        $query = $this->_db->prepare("SELECT `pwd` FROM `user` WHERE `name` = ?");
        $query->execute( array($uname) );
        $ret = $query->fetchAll();
        if (!$ret || count($ret) != 1){
            $this->errno = -1003;
            $this->errmsg = "用户查找失败";
            return false;
        }
        $userInfo = $ret[0];
        if( $this->_password_generate($pwd) != $userInfo['pwd'] ) {
            $this->errno = -1004;
            $this->errmsg = "密码错误";
            return false;
        }
        return true;
    }

    public function register($uname, $pwd, $phone, $email){
        $chenckmail = "/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//邮箱验证的正则表达式
        $query = $this->_db->prepare("SELECT COUNT(*) as c FROM `user` WHERE `name` = ?");
        $query->execute(array($uname));
        $count = $query->fetchAll();
        //判断注册用户是否已经存在
        if($count[0]['c'] != 0){
            $this->errno = -1005;
            $this->errmsg = "用户名已经存在";
            return false;
        }
        //对密码进行验证
        if(strlen($pwd) < 8){
            $this->errno = -1006;
            $this->errmsg = "密码长度过短，请设置至少8位密码";
            return false;
        }else{
            $password = $this->_password_generate($pwd);
        }
        //对手机号位数进行验证
        if(strlen($phone) <= 0 || strlen($phone) >= 12){
            $this->errno = -1007;
            $this->errmsg = "手机位数不对，请输入11位手机号码";
            return false;
        }
        //对邮箱格式进行验证
        if (!preg_match($chenckmail, $email)){
            $this->errno = -1008;
            $this->errmsg = "邮箱格式不对，请重新输入正确的邮箱";
            return false;
        }

        $query = $this->_db->prepare("INSERT INTO `user` (`id`, `name`, `pwd`, `email`, `mobile`, `reg_time`, `update_time`) VALUES (null, ?, ?, ?, ?, ?, ?)");
        $ret = $query->execute(array($uname, $password, $email, $phone, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));

        if(!$ret){
            $this->errno = -1009;
            $this->errmsg = "注册失败，数据写入失败";
            return false;
        }
        return true;
    }

    public function _password_generate($password){
        $pwd = md5("salt-yaf_api".$password);
        return $pwd;
    }

}