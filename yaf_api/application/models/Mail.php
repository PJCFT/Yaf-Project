<?php
/**
 * @name MailModel
 * @desc Mail 邮件操作
 * @author desktop-v0f8uqi\administrator
 */
require __DIR__.'/../../vendor/autoload.php';
use Nette\Mail\Message;

class MailModel {
    public $errno = 0;
    public $errmsg = "";
    private $_db;
    public function __construct() {
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
    public function send($uid, $title, $contents){
        $query = $this->_db->prepare("SELECT `email` FROM `user` WHERE `id` = ?");
        $query->execute(array(intval($uid)));
        $ret = $query->fetchAll();
        //验证邮箱是否存在
        if(!$ret || empty($ret[0]['email'])){
            $this->errno = -3001;
            $this->errmsg = "用户邮箱信息查询失败！";
            return false;
        }
        $userEmail = $ret[0]['email'];
        //验证邮箱是否符合标准
        if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)){
            $this->errno = -3002;
            $this->errmsg = "用户邮箱信息不符合标准，邮箱地址为：".$userEmail;
            return false;
        }
        //设置邮箱发送信息
        $mail = new Message();
        $mail->setFrom('2398720780@qq.com')->addTo($userEmail)->setSubject($title)->setBody($contents);
        //邮件发送者信息配置
        $mailer = new Nette\Mail\SmtpMailer([
            'host'=>'smtp.qq.com',
            'username'=>'2398720780@qq.com',//smtp username
            'password'=>'omxblkixwvuudhif',//smtp password
            'secure'=>'ssl'
        ]);
        $mailer->send($mail);
        return true;
    }
}
