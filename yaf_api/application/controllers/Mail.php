<?php
/**
 * @name MailController
 * @desc 邮件发送
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author desktop-v0f8uqi\administrator
 */
class MailController extends Yaf_Controller_Abstract {
    public function indexAction(){

    }
    public function sendAction(){
        $submit = $this->getRequest()->getQuery("submit", "0");
        if($submit != "1"){
            echo json_encode(array(
                "errno"=>-1010,
                "errmsg"=>"请通过正确渠道提交"
            ));
            return false;
        }

        $uid = $this->getRequest()->getPost("uid", false);
        $title = $this->getRequest()->getPost("title", false);
        $contents = $this->getRequest()->getPost("contents", false);
        if(!$uid || !$title || !$contents){
            echo json_encode(array(
                "errno"=>-3003,
                "errmsg"=>"用户ID、邮件标题、邮件内容不能为空"
            ));
            return false;
        }
        //调用Model，发送邮件
        $model = new MailModel();
        $ret = $model->send(intval($uid), trim($title), trim($contents));
        if($ret){
            echo json_encode(array(
                "errno"=>0,
                "errmsg"=>""
            ));
        }else{
            echo json_encode(array(
                "errno"=>$model->errno,
                "errmsg"=>$model->errmsg
            ));
        }
        return true;

    }
}
