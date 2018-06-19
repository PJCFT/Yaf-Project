<?php
/**
 * @name SmsModel
 * @desc sms 短信操作model类，使用sms.cn服务
 * @author desktop-v0f8uqi\administrator
 */
class SmsModel {
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
    
    public function send($uid, $contents) {
        $query = $this->_db->prepare("SELECT `mobile` FROM `user` WHERE `id` = ?");
        $query->execute(array(intval($uid)));
        $ret = $query->fetchAll();
        if(!$ret || empty($ret[0]['mobile'])){
            $this->errno = -4001;
            $this->errmsg = "用户手机号信息查询失败！";
            return false;
        }
        $userMobile = $ret[0]['mobile'];
        if(!$userMobile || is_numeric($userMobile) || strlen($userMobile) != 11){
            $this->errno = -4002;
            $this->errmsg = "用户手机号信息不标准，手机号为：".(!$userMobile?"空":$userMobile);
            return false;
        }

        //sms 信息配置
        $smsUid = "";//sms的账号
        $smsPwd = "";//sms的密码
        //调用sms里的模板
        $sms = new ThirdParty_Sms($smsUid, $smsPwd);
        //产生验证码
        $contentParam = array('code'=>rand(1000, 9999));
        $template = '100006';
        //发送信息
        $result = $sms->send($userMobile, $contentParam, $template);
        if($result['stat'] == '100'){
            //成功记录，用于日后对账
            $query = $this->_db->prepare("INSERT INTO `sms_record`(`uid`,`contents`,`template`) VALUES (?, ?, ?)");
            $ret = $query->execute(array($uid, json_encode($contentParam), $template));
            if(!$ret){
                //TODO 应该返回true还是false，有待商量
                $this->errno = -4003;
                $this->errmsg = "消息发送成功，但是发送记录失败";
                return false;
            }
            return true;
        }else{
            $this->errno = -4004;
            $this->errmsg = '发送失败:'.$result['stat'].'('.$result['message'].')';
            return false;
        }
    }

}
