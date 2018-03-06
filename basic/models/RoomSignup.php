<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 16/12/14
 * Time: 上午9:51
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class RoomSignup extends ActiveRecord {

    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function tableName()
    {
        return 'room_signup';
    }
}