<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Userinfo;
class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    
    public function actionConfirmLogin()
    {
        $cookies = Yii::$app->response->cookies;  
        if (($cookie = $cookies->get('username')) !== null) {
                $username = $cookie->value;
        }else{
            $username = Yii::$app->session->get('username');
            if(empty($username))
            {
               $username = 0; 
            }
        }
        return $username;
    }
    
    //重置用户session中的用户信息
    public function actionResetUserinfo($userid)
    {
        $userinfo = Userinfo::find()
                ->where('userid = :userid and isdelete = 0', [
                    ':userid' => $userid
                ])
                ->asArray()
                ->one();
        $session = Yii::$app->session;
        $session->open();
        $session->set('userinfo', $userinfo);
        $userinfo = json_encode($userinfo);
        return $userinfo;
    }
    
   

   

   
}
