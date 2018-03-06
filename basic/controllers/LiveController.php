<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UserFocus;
use app\models\Room;
use app\models\Userinfo;
use app\controllers;


class LiveController extends Controller
{
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
    
    public function actionLive($roomid )
    {
        $this->layout = "live"; 
        $userinfo = Yii::$app->session->get('userinfo');
       
        
        //判断用户是否关注该房间
        $is_focus = UserFocus::find()
                    ->select('id')
                    ->where('is_focus = 1 and roomid = :roomid and userid = :userid and is_focus = :is_focus',[
                        ':roomid'=>$roomid,
                        ':userid'=>$userinfo["userid"],
                        ':is_focus'=>1
                    ])
                    ->asArray()
                    ->one();
        
        if(empty($userinfo)){
            $userinfo["userid"]=0;
            $userinfo["gold"]=0;
        }
        return $this->render('live',[
            'userinfo'=>$userinfo,
            'is_focus'=>$is_focus,
            'roomid'=>$roomid,
            
        ]);
    }

    
    public function actionFocus($data,$roomid)
    {
       $userinfo = Yii::$app->session->get('userinfo');
       if(empty($userinfo["userid"])){
          return "请先登录"; 
       }
       $isExist = UserFocus::find()
                 ->select('is_focus')
                 ->where('userid = :userid and roomid = :roomid',[
                     ':roomid'=>$roomid,
                     ':userid'=>$userinfo["userid"]
                 ])
               ->asArray()
               ->one();
       
       if(empty($isExist))
       {
           $insert = new UserFocus();
           $insert->userid = $userinfo["userid"];
           $insert->roomid = $roomid;
           $insert->createdtime = time();
           $insert->updatetime = time();
           $insert->is_focus = $data;
           $result = $insert->save();
           return $result;
       }else{
        $sql = "update user_focus set is_focus = :is_focus where userid = :userid and roomid = :roomid";
        $result = Yii::$app->db->createCommand($sql)
                ->bindValues([
                   ':is_focus'=>$data,
                    ':userid'=>$userinfo["userid"],
                    ':roomid'=>$roomid
                ])
                ->execute();
         return $result;
       }
    }
   

   
}
