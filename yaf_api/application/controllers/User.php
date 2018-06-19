<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 20:48
 */
class UserController extends Yaf_Controller_Abstract{
    public function indexAction(){
        $this->loginAction();

    }

    public function loginAction(){
        //若submit没有传的话，赋值为0，有传的话为传入值
        $submit = $this->getRequest()->getQuery("submit", 0);
        if($submit != 1){
            echo json_encode(
                array(
                    "errno" => -1010,
                    "errmsg" => "请通过正确渠道提交"
                )
            );
            return false;
        }
        $uname = $this->getRequest()->getPost("uname", false);
        $pwd = $this->getRequest()->getPost("pwd", false);
        if(!$uname || !$pwd){
            echo json_encode(
                array(
                    "errno" => -1011,
                    "errmsg" => "用户名和密码不能为空，请重新提交"
                )
            );
            return false;
        }
        //调用model进行验证
        $model = new UserModel();
        $uid = $model->login(trim($uname), trim($pwd));
        if($uid){
            //写入session
            session_start();
            $_SESSION['user_token'] = md5("salt".$_SERVER['REQUEST_TIME'].$uid);
            $_SESSION['user_time'] = $_SERVER['REQUEST_TIME'];
            $_SESSION['user_id'] = $uid;
            echo json_encode(
                array(
                    "errno" => 0,
                    "errmsg" => "",
                    "data" => array("name" => $uname),
                )
            );
        }else{
            echo json_encode(
                array(
                    "errno"=>$model->errno,
                    "errmsg" =>$model->errmsg
                )
            );
        }
        return true;
    }

    public function registerAction(){
        $uname = $this->getRequest()->getPost("uname", false);
        $pwd = $this->getRequest()->getPost("pwd", false);
        $phone = $this->getRequest()->getPost("phone", false);
        $email = $this->getRequest()->getPost("email", false);
        if(!$uname || !$pwd){
            echo json_encode(
                array(
                    "error" => -1002,
                    "errmsg" => "用户名和密码必须传递"
                )
            );
            return false;
        }
        //调用model进行注册
        $model = new  UserModel();
        if($model->register(trim($uname), trim($pwd), trim($phone), trim($email))){
            echo json_encode(array(
               "errno" => 0,
                "errmsg" => "",
                "data" => array("name" => $uname)
            ));
        }else{
            echo json_encode(array(
               "errno" => $model->errno,
                "errmsg" => $model->errmsg
            ));
        }

        return true;
    }
}