<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Room;
use app\models\Userinfo;
use yii\helpers\Url;


class HelloController extends Controller
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
        $room = Room::find()
                ->alias('r')
                ->select('r.roomid,r.roomtitle,u.username,r.room_cover')
                ->leftJoin('userinfo as u','u.userid=r.userid')
                 ->where('is_formal = 1 and is_deleted = 0')
                ->asArray()
                ->all();
       
               
        $view = Yii::$app->view;
        $view->params['userinfo']= $userinfo;
        return $this->render('index',[
            'room'=>$room,
            'userinfo'=>$userinfo
        ]);
    }
    
    /*
     * 用户搜索房间
     * create by sjy
     */
    public function actionSearch($keyword)
    {
        $room = Room::find()
                ->alias('r')
                ->select('r.roomid,r.roomtitle,u.username,r.room_cover')
                ->leftJoin('userinfo as u','u.userid=r.userid')
                 ->where("is_formal = 1 and is_deleted = 0 and roomtitle like '%$keyword%'")
                ->asArray()
                ->all();
       
        return $this->render('search',[
            'room'=>$room
        ]);
        
    }
    
    /*
     * 房间类型
     * create by sjy
     */
    public function actionRoomType($roomtype)
    {
       $room = Room::find()
                ->alias('r')
                ->select('r.roomid,r.roomtitle,u.username,r.room_cover')
                ->leftJoin('userinfo as u','u.userid=r.userid')
                 ->where("is_formal = 1 and is_deleted = 0 and roomtype = :roomtype",[
                     ':roomtype'=>$roomtype
                 ])
                ->asArray()
                ->all();
       
        return $this->render('roomtype',[
            'room'=>$room
        ]); 
    }

    public function actionRun() {
//        $post_data = array(
//            'mobile' => '15940277230',
//            'templateid' => '3059487',
//            'codeLen'=>6
//        );
//         $data = array();  
//    $data['templateid'] = "3059487";  
//    $data['mobiles'] = "15940277230";  
//    $data['params'] = "6";  
//        $url = "https://api.netease.im/sms/sendcode.action";
//        $postdata = http_build_query($post_data);
//        $Nonce = mt_rand(1,1000);
//        $CurTime = time()+28800;
//        $AppKey = "0154bdce25f88fed13fbc150a36183c2";
//        $AppSecret = "5b29d5cfb09c";
//          $CheckSum = strtolower(sha1($AppSecret.$Nonce.$CurTime));  
//
//       $head_arr = array();  
//       $head_arr[] = 'Content-Type: application/x-www-form-urlencoded';  
//       $head_arr[] = 'charset: utf-8';  
//       $head_arr[] = 'AppKey:'.$AppKey;  
//       $head_arr[] = 'Nonce:'.$Nonce;  
//       $head_arr[] = 'CurTime:'.$CurTime;  
//       $head_arr[] = 'CheckSum:'.$CheckSum;  
//        $options = array(
//            'http' => array(
//                'method' => 'POST',
//                'header' => $head_arr,
//                'content' => $postdata,
//                'timeout' => 15 * 60 // 超时时间（单位:s）
//            )
//        );
//        $context = stream_context_create($options);
//        $result = file_get_contents($url, false, $context);
//        var_dump($result);
        

        if(is_numeric("1233")){
            echo "12";
        }else{
            echo "34";
        }
       echo Url::toRoute('hello/index');
        $pwd = md5('sunjingyu0509sunjingyu');
        var_dump($pwd);
        //背包承重上限
        $limit = 57;
        //物品种类
        $total = 5;
        //物品
        $array = array(
            array("栗子", 4, 4500),
            array("苹果", 5, 5700),
            array("橘子", 2, 2250),
            array("草莓", 1, 1100),
            array("甜瓜", 6, 6700)
        );
        //存放物品的数组
        $item = array_fill(0, $limit + 1, 0);
        //存放价值的数组
        $value = array_fill(0, $limit + 1, 0);
        $p = $newvalue = 0;
        for ($i = 0; $i < $total; $i++) {
            for ($j = $array[$i][1]; $j <= $limit; $j++) {
                $p = $j - $array[$i][1];
                $newvalue = $value[$p] + $array[$i][2];
                //找到最优解的阶段
                if ($newvalue > $value[$j]) {
                    $value[$j] = $newvalue;
                    $item[$j] = $i;
                }
            }
        }
        echo "物品  价格<br />";
        for ($i = $limit; 1 <= $i; $i = $i - $array[$item[$i]][1]) {
            echo $array[$item[$i]][0] . "  " . $array[$item[$i]][2] . "<br />";
        }
        echo "合计  " . $value[$limit];
    }

}
