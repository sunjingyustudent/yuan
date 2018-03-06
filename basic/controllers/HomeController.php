<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Userinfo;
use app\models\UserFocus;
use app\models\Room;
use app\models\RoomSignup;
use app\models\GiftRecord;
use yii\data\Pagination;
use OSS\OssClient;
use OSS\Core\OssException;


class HomeController extends Controller
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
     
    /*
     * 用户个人中心首页
     * create by sjy
     */
    public function actionHome()
    {
        $userinfo = Yii::$app->session->get('userinfo');
        if (empty($userinfo)) {
            return $this->redirect('/account/login');
        }
        $view = Yii::$app->view;
        $view->params['userinfo'] = $userinfo;

        $my_focus = UserFocus::find()
                        ->select('id')
                        ->where('userid = :userid and is_focus = 1', [
                            ':userid' => $userinfo["userid"]
                        ])
                        ->asArray()->all();
        $my_focus = count($my_focus);
        
        $favrite_my = UserFocus::find()
                        ->select('id')
                        ->where('roomid = :roomid and is_focus = 1', [
                            ':roomid' => $userinfo["roomid"]
                        ])
                        ->asArray()->all();
        $favrite_my = count($favrite_my);

        return $this->render('home', [
                    'userinfo' => $userinfo,
                    'my_focus' => $my_focus,
                    'favrite_my' => $favrite_my,
        ]);
    }
    
    /*
     * create by sjy 
     * 用户个人中心
     */
    public function actionPersonCenter()
    {
        $userinfo = Yii::$app->session->get('userinfo');
        if(empty($userinfo["school"])){
            $userinfo["school"] = "未填写";
        }
        if(empty($userinfo["professional"])){
            $userinfo["professional"] = "未填写";
        }
        if(empty($userinfo["sex"])){
            $man = "checked";
            $woman = "";
        }else{
            $man = "";
            $woman = "checked";
        }
        return $this->renderPartial('personcenter',[
            'userinfo'=>$userinfo,
            'man'=>$man,
            'woman'=>$woman,
            
        ]);
    }
    
    /*
     * 修改用户个人信息
     * create by sjy 
     */
    public function actionDoPersonCenter($username,$professional,$school,$sex)
    {
        $userinfo = Yii::$app->session->get('userinfo');
        $sql = "update userinfo set username = :username,professional = :professional,school = :school,sex = :sex ,updatetime = :updatetime where phone = :phone";
        $result = Yii::$app->db->createCommand($sql)
                ->bindValues([
                    ':username' => $username,
                    ':professional' => $professional,
                    ':school' => $school,
                    ':sex' => $sex,
                    ':phone' => $userinfo["phone"],
                    ':updatetime' => time()
                ])
                ->execute();
        if ($result == 1) {
            $userinfo_after = Userinfo::find()
                    ->where('phone = :phone and isdelete = 0', [
                        ':phone' => $userinfo["phone"]
                    ])
                    ->asArray()
                    ->one();
            $session = Yii::$app->session;
            $session->open();
            $session->set('userinfo', $userinfo_after);
        }
        $view = Yii::$app->view;
        $view->params['userinfo']= $userinfo_after;
        return $result;
    }
    
    public function actionHeadUpload() 
    {
       if (!empty($_FILES['icon']['tmp_name'])) {
           $path = '/images/upload/' .time() . "-" ;
            if (move_uploaded_file($_FILES['icon']['tmp_name'], '../web'.$path . $_FILES['icon']['name'])) {
                $userinfo = Yii::$app->session->get('userinfo');
                $sql = "update userinfo set head = :head,updatetime = :updatetime where phone = :phone";
                $result = Yii::$app->db->createCommand($sql)
                        ->bindValues([
                            ':head' => $path.$_FILES['icon']['name'],
                            ':phone' => $userinfo["phone"],
                            ':updatetime' => time()
                        ])
                        ->execute();
                $userinfo = Userinfo::find()
                        ->where('phone = :phone and isdelete = 0', [
                            ':phone' => $userinfo["phone"]
                        ])
                        ->asArray()
                        ->one();
                $session = Yii::$app->session;
                $session->open();
                $session->set('userinfo', $userinfo);
                return $path.$_FILES['icon']['name'];
            } else {
                return "0";
            }
        }
        return "0";
    }
    
    /*
     * 阿里云上传图片
     */
    public function actionUploadPic()
    {
        $accessKeyId = "LTAIXI2jNjs28wG6";
        $accessKeySecret = "FOBMkMk5LVweID4jue8bEvGI9avQ3A";
        $endpoint = "http://oss-cn-shanghai.aliyuncs.com";
        $bucket = "sunjingyu";
        $object = "sunjingyu".time().".jpg";
       
       
        if (!empty($_FILES['icon']['tmp_name'])) {
            $content = $_FILES['icon']['tmp_name'];
            try {
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
                $ossClient->uploadFile($bucket, $object, $content);
       
            } catch (OssException $e) {
                print $e->getMessage();
                return 0;
            }
        }
          $userinfo = Yii::$app->session->get('userinfo');
                $sql = "update userinfo set head = :head,updatetime = :updatetime where phone = :phone";
                $result = Yii::$app->db->createCommand($sql)
                        ->bindValues([
                            ':head' => "http://sunjingyu.oss-cn-shanghai.aliyuncs.com/".$object,
                            ':phone' => $userinfo["phone"],
                            ':updatetime' => time()
                        ])
                        ->execute();
                $userinfo = Userinfo::find()
                        ->where('phone = :phone and isdelete = 0', [
                            ':phone' => $userinfo["phone"]
                        ])
                        ->asArray()
                        ->one();
                $session = Yii::$app->session;
                $session->open();
                $session->set('userinfo', $userinfo);

        return "http://sunjingyu.oss-cn-shanghai.aliyuncs.com/".$object;
    }

    /*
     * 我的收藏
     * create by sjy
     */
    public function actionMyCollect()
    {
        $userinfo = Yii::$app->session->get('userinfo');
        $roominfo = UserFocus::find()
                    ->alias('uf')
                    ->select('r.roomid,r.roomtitle,u.username,r.room_cover')
                    ->leftJoin('userinfo as u','u.userid = uf.userid ')
                    ->leftJoin('room as r','r.roomid = uf.roomid ')
                   ->where('uf.userid = :userid and uf.is_focus = :is_focus',[
                       ':userid'=>$userinfo["userid"],
                       ':is_focus'=>1
                   ])
                ->asArray()
                ->all();
//        var_dump($roominfo);
        return $this->renderPartial('mycollect',[
            'roominfo'=>$roominfo
        ]);
    }
    
    /*
     * 我的消费记录
     * create by sjy
     */
    public function actionMyPayRecord()
    {
        $userinfo = Yii::$app->session->get('userinfo');
        $gift = GiftRecord::find()
                ->alias('gr')
                ->select('gr.roomid,gr.fromuserid,gr.giftname,gr.giftprice,u.username,r.roomtitle')
                ->leftJoin('userinfo as u', 'u.userid = gr.touseid')
                ->leftJoin('room as r', 'r.roomid = gr.roomid')
                ->where('fromuserid = :fromuserid', [
                    ':fromuserid' => $userinfo["userid"]
                ])
                ->orderBy('time desc')
                ->asArray()
                ->all();
        $count = count($gift);
        return $this->renderPartial('mypayrecord',[
            'count'=>$count
        ]);
    }
    public function actionMyPayRecordList($page = 0)
    {
        $userinfo = Yii::$app->session->get('userinfo');
        $gift = GiftRecord::find()
                ->alias('gr')
                ->select('gr.roomid,gr.fromuserid,gr.giftname,gr.giftprice,u.username,r.roomtitle')
                ->leftJoin('userinfo as u', 'u.userid = gr.touseid')
                ->leftJoin('room as r', 'r.roomid = gr.roomid')
                ->where('fromuserid = :fromuserid', [
                    ':fromuserid' => $userinfo["userid"]
                ])
                ->orderBy('time desc')
                ->offset($page)
                ->limit(10)
                ->asArray()
                ->all();
        
        return $this->renderPartial('mypayrecordlist',[
            'gift'=>$gift
        ]);
    }
    
    
    /*
     * 主播相关 
     * create by sjy
     */
    public function actionAnchor()
    {
        $userinfo = Yii::$app->session->get('userinfo');
        
        $roominfo = Room::find()
                ->where('userid = :userid',[
                    ':userid'=>$userinfo["userid"]
                ])
                ->asArray()
                ->one();
        if(empty($roominfo)){
            return $this->redirect('/anchor/index');
        }
        if(empty($roominfo["is_formal"]))
        {
            $applyStatus = RoomSignup::find()
                    ->select('rejection_reason,apply_status')
                    ->where('roomid = :roomid',[
                        ':roomid'=>$roominfo["roomid"]
                    ])
                    ->asArray()
                    ->one();
            return $this->renderPartial('anchorroom_status',[
                'applyStatus'=>$applyStatus
            ]);
        }
       
        return $this->renderPartial('anchorroom',[
            'roominfo'=>$roominfo
        ]);
    }
   
    public function actionUpdateRoominfo()
    {
      $redis = Yii::$app->redis;
      
      $request = Yii::$app->request->post();
      $userinfo = Yii::$app->session->get('userinfo');
      $roominfo = Room::find()
                 ->where('userid = :userid',[
                     ':userid'=>$userinfo["userid"]
                 ])
              ->one();
      $redis->del('sunjingyu1-config-'.$roominfo["roomid"]);
      $roominfo->roomtitle = $request["roomtitle"];
      $roominfo->rooomnotice = $request["rooomnotice"];
      $roominfo->room_cover = $request["room_cover"];
      $roominfo->update_time = time();
      $return = $roominfo->update();
      
      
      
      return $return;
    }

   

   
}
