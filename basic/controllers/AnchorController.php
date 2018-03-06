<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Userinfo;
use app\models\RoomSignup;
use app\models\Room;
use app\models\MassageBoard;

class AnchorController extends Controller
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
    
    
    
    public function actionIndex()
    {
        $this->layout = "main"; 
        $userinfo = Yii::$app->session->get('userinfo');
      
        $view = Yii::$app->view;
        $view->params['userinfo']= $userinfo;

        if(empty($userinfo)){
            return $this->redirect('/account/login');
        }
        $is_have = RoomSignup::find()
                ->select('id')
                ->where('phone = :phone and is_deleted = 0 and is_formal=1', [
                    ':phone' => $userinfo["phone"]
                ])
                ->asArray()
                ->one();
        
         if (!empty($is_have)) {
            return $this->render('successed');
        }
        $sign_info = RoomSignup::find()
                ->where('phone = :phone and is_deleted = 0 ', [
                    ':phone' => $userinfo["phone"]
                ])
                ->asArray()
                ->one();
        if(!empty($sign_info)){
            $update =1;
        }else{
            $update =0; 
        }
        
        return $this->render('index',[
            'sign_info'=>$sign_info,
            'update'=>$update
        ]);
    }
    
    public function actionAnchorApply()
    {
        $request = Yii::$app->request->post();
        $userinfo = Yii::$app->session->get('userinfo');

        if (!empty($request["update"])) {
           
            $update_sign = RoomSignup::find()
                    ->where('phone = :phone and is_deleted = 0 ', [
                ':phone' => $userinfo["phone"]
            ])->one();
            $update_sign->identity_card = $request["identity_card"];
            $update_sign->mail_box = $request["mail_box"];
            $update_sign->live_type = $request["live_type"];
            $update_sign->real_name = $request["real_name"];
            $update_sign->apply_reason = $request["apply_reason"];
            $update_sign->identity_card_font_url = $request["identity_card_font_url"];
            $update_sign->identity_card_back_url = $request["identity_card_back_url"];
            $update_sign->rejection_reason="";
            $update_sign->is_formal=0;
            $update_sign->apply_status=0;
            $update_sign->update_time=time();
            $resu = $update_sign->update();
            if($resu){
                return "修改成功";
            }
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            //先创建一个房间
            $room = new Room();
            $room->userid = $userinfo["userid"];
            $room->roomtype = $request["live_type"];
            $roomdata = $room->save();
            if ($roomdata) {
                $roomid = Room::find()
                        ->select('roomid')
                        ->where('userid = :userid', [
                            ':userid' => $userinfo["userid"]
                        ])
                        ->asArray()
                        ->one();
                if ($roomid) {
                    $result = new RoomSignup();
                    $result->phone = $userinfo["phone"];
                    $result->roomid = $roomid["roomid"];
                    $result->identity_card = $request["identity_card"];
                    $result->mail_box = $request["mail_box"];
                    $result->live_type = $request["live_type"];
                    $result->real_name = $request["real_name"];
                    $result->apply_reason = $request["apply_reason"];
                    $result->identity_card_font_url = $request["identity_card_font_url"];
                    $result->identity_card_back_url = $request["identity_card_back_url"];
                    $result->create_time=time();
                    $result->update_time=time();
                    $result->save();
                }
            }

            $transaction->commit();
            return 0;
        } catch (Exception $e) {
            $transaction->rollBack();
            return "申请失败";
        }
    }
   

}
