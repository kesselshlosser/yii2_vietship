<?php
namespace app\models;
use yii\easyii\components\ActiveRecord;

class Log extends ActiveRecord
{
    public static function tableName()
    {
        return 'log';
    }
    
    public function rules()
    {
        return [
            ['log', 'safe']
        ];
    }
}