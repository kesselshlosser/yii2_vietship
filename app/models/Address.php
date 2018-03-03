<?php
namespace app\models;
use yii\easyii\components\ActiveRecord;

class Address extends ActiveRecord
{
    public static function tableName()
    {
        return 'address';
    }
    
    public function rules()
    {
        return [
            [['full_name'], 'required'],
            [['full_name', 'address_line1', 'address_line2', 'city', 'state', 'country', 'postal_code'], 'string']
        ];
    }
}