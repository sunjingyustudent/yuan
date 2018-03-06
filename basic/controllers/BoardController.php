<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Userinfo;
use app\models\MassageBoard;
use yii\web\Cookie;

class BoardController extends Controller {

    /**
     * @inheritdoc
     */
     public function beforeAction($action) 
    {
        return parent::beforeAction($action);
    }
     public function actionMassage()
    {
        $massage = MassageBoard::find()
                ->asArray()
                ->all();
        return json_encode($massage, JSON_UNESCAPED_SLASHES);
    }
}
