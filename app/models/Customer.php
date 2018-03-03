<?php
namespace app\models;
use yii\easyii\components\ActiveRecord;

class Customer extends ActiveRecord
{
    public static function tableName()
    {
        return 'customer';
    }
    
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'string']
        ];
    }
}