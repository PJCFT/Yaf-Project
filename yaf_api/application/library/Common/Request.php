<?php
/**
 * Created by PhpStorm.
 * User: pjc
 * Date: 2018/7/22
 * Time: 21:43
 */
class Common_Request{
    static  public function request($key, $default = null, $type = null){
        if($type == 'get'){
            $result = isset($_GET[$key])?trim($_GET[$key]):null;
        }else if($type == 'post'){
            $result = isset($_POST[$key])?trim($_POST[$key]):null;
        }else{
            $result = $default;
        }
        return $result;
    }

    static  public function getRequest($key, $defalut = null){
        return self::request($key, $defalut, 'get');
    }

    static  public function postRequest($key, $defalut = null){
        return self::request($key,$defalut,'post');
    }

    static public function response($errno, $errmsg = "", $data = null){
        $rep = array(
            'errno'=>$errno,
            'errmsg'=>$errmsg
        );
        if ($data != null){
            $rep['data'] = $data;
        }
        return $rep;
    }


}