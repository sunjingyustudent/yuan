<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Userinfo;
use yii\web\Cookie;

class AccountController extends Controller {

    /**
     * @inheritdoc
     */
     public function beforeAction($action) 
    {
        $userinfo = Yii::$app->session->get('userinfo');
        if(empty($userinfo)){
             $cookies = Yii::$app->response->cookies;  
             if (($cookie = $cookies->get('username')) !== null) {
                $username = $cookie->value;
                $userinfo = Userinfo::find()
                        ->where('phone = :phone and isdelete = 0', [
                            ':phone' => $username
                        ])
                        ->asArray()
                        ->one();
                $session = Yii::$app->session;
                $session->open();
                $session->set('userinfo', $userinfo);
            }
        }
//       if(empty($userinfo)){
//            return $this->redirect('/account/login');
//        }
        return parent::beforeAction($action);
    }
    
    public function actionLogin() 
    {
        $userinfo = Yii::$app->session->get('userinfo');
      
        $view = Yii::$app->view;
        $view->params['userinfo']= $userinfo;
        return $this->render('login',[
            'userinfo'=>$userinfo
        ]);
    }
    
    public function actionDoLogin($username,$pwd,$nextlogin)
    {
        $is_have = Userinfo::find()
                ->where('phone = :phone and isdelete = 0 and userpwd = :userpwd', [
                    ':phone' => $username,
                    ':userpwd' => $pwd
                ])
                ->asArray()
                ->one();
        
        $session = Yii::$app->session;
        $session->open();
        $session->set('userinfo', $is_have);

        if (!empty($is_have)) 
        {
            //用户点击自动登录
            if (!empty($nextlogin)) 
            {
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'username',
                    'value' => $username,
                ]));
            }
            return 1;
        } else {
            return 0;
        }
    }

    /*
     * 注册页面 create by sjy
     */
    public function actionRegister() 
    {
        $userinfo = Yii::$app->session->get('userinfo');
        if(empty($userinfo)){
             $cookies = Yii::$app->response->cookies;  
             if (($cookie = $cookies->get('username')) !== null) {
                $username = $cookie->value;
                $userinfo = Userinfo::find()
                        ->where('phone = :phone and isdelete = 0', [
                            ':phone' => $username
                        ])
                        ->asArray()
                        ->one();
                $session = Yii::$app->session;
                $session->open();
                $session->set('userinfo', $userinfo);
            }
        }
        $view = Yii::$app->view;
        $view->params['userinfo']= $userinfo;
        return $this->render('register');
    }

    public function actionGetPhoneCode($phone) 
    {
        //判断手机号是否存在
        $is_have = Userinfo::find()
                   ->select('userid')
                   ->where('phone = :phone and isdelete = 0',[
                       ':phone'=>$phone
                   ])
                   ->asArray()
                   ->one();
        if(!empty($is_have))
        {
            return "该手机号已注册";
        }
        //获取随机验证码
        $str = $this->randNumber(4);   //验证码
        //将验证码保存在session中
        $session = Yii::$app->session;
        $session->open();
        $session->set('phone_code', $str);
        //发送验证码
//        $uid = "lingjuli";
//        $pwd = "297075b74278a0ac51553522a693201f";
        $uid = "sunjingyu";
        $pwd = "fdfddcd503b89721876e247d07612ecf";
//        $content = '您的验证码是' . $str . '，请提交验证码完成验证。如果有问题请拔打电话：4008950662。【9度财经直播】';
         $content = '您的验证码是' . $str . '，请提交验证码完成验证。如果有问题请拔打电话：15940277230。【good good study】';
        $res = $this->sendSMS($uid, $pwd, $phone, $content);
        if($res['stat'] != '100'){
//            return "验证码发送失败";
            $result = $this->sendPhoneCode($phone);
            $result = json_decode($result);
            if($result->code == 200){
                $session = Yii::$app->session;
                $session->open();
                $session->set('phone_code', $result->obj);
                return 1;
            }else{
                return "验证码发送失败,请联系客服";
            }
        }else{
            return 1;
        }
    }
    
    public function sendPhoneCode($phone) 
    {
        $post_data = array(
            'mobile' => $phone,
            'templateid' => Yii::$app->params['templateid_sms_1'],
            'codeLen' => 6
        );
        $url = "https://api.netease.im/sms/sendcode.action";
        $postdata = http_build_query($post_data);
        $Nonce = mt_rand(1, 1000);
        $CurTime = time() + 28800;
        $AppKey = Yii::$app->params['AppKey_sms'];
        $AppSecret = Yii::$app->params['AppSecret_sms'];
        $CheckSum = strtolower(sha1($AppSecret . $Nonce . $CurTime));

        $head_arr = array();
        $head_arr[] = 'Content-Type: application/x-www-form-urlencoded';
        $head_arr[] = 'charset: utf-8';
        $head_arr[] = 'AppKey:' . $AppKey;
        $head_arr[] = 'Nonce:' . $Nonce;
        $head_arr[] = 'CurTime:' . $CurTime;
        $head_arr[] = 'CheckSum:' . $CheckSum;
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => $head_arr,
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public function actionDoRegister($phone = 0,$pwd,$phone_code) 
    {
        //判断手机号是否存在
        $is_have = Userinfo::find()
                   ->select('userid')
                   ->where('phone = :phone and isdelete = 0',[
                       ':phone'=>$phone
                   ])
                   ->asArray()
                   ->one();
        if(!empty($is_have))
        {
            return "该手机号已注册";
        }
        $code = Yii::$app->session->get('phone_code');
        if($code != $phone_code)
        {
            return "验证码输入不正确";
        }
        $result = new Userinfo();
        $result->phone = $phone;
        $result->userpwd = $pwd;
        $result->username = "000";
        $add = $result->save();
        return $add;
    }

    function sendSMS($uid, $pwd, $mobile, $content, $template = '') 
   {
        $apiUrl = 'http://api.sms.cn/sms/';  //短信接口地址
        $data = array(
            'ac' => 'send',
            'uid' => $uid, //用户账号
            'pwd' => $pwd, //md5($pwd.$uid),					//MD5位32密码,密码和用户名拼接字符
            'mobile' => $mobile, //号码
            'content' => $content, //内容
            'template' => $template, //变量模板ID 全文模板不用填写
            'format' => 'json', //接口返回信息格式 json\xml\txt
        );

        //$result = postSMS($apiUrl,$data);			//POST方式提交
        //JSON数据转为数组
        $result = $this->getSMS($apiUrl, $data);
        $re = $this->json_to_array($result); //GET方式提交
        return $re;
    }

    function getSMS($url, $data = '')
    {
        $get = '';
        while (list($k, $v) = each($data)) {
            $get .= $k . "=" . urlencode($v) . "&"; //转URL标准码
        }
        return file_get_contents($url . '?' . $get);
    }

    function json_to_array($p) 
   {
        if (mb_detect_encoding($p, array('ASCII', 'UTF-8', 'GB2312', 'GBK')) != 'UTF-8') {
            $p = iconv('GBK', 'UTF-8', $p);
        }
        return json_decode($p, true);
    }

    function randNumber($len = 6) 
    {
        $chars = str_repeat('0123456789', 10);
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
        return $str;
    }

    /*
     * create by sjy 
     * 用户退出操作
     */
    public function actionGoOut()
    {
        //清除cookie
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('username');
        //
        $session = Yii::$app->session;
        $session->remove('userinfo');
        return $this->redirect('/hello/index');
    }
}
