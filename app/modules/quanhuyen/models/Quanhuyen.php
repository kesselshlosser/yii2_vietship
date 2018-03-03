<?php
namespace app\modules\quanhuyen\models;

use Yii;
use yii\helpers\StringHelper;

class Quanhuyen extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'quan_huyen';
    }

    public function rules()
    {
        return [
            ['ten_quan_huyen', 'required', 'message' => 'Tên quận huyện không được để trống'],
            ['ten_quan_huyen', 'unique', 'message' => 'Tên quận huyện đã tồn tại']
        ];
    }

    public function attributeLabels()
    {
        return [
            'ten_quan_huyen' => "Tên quận huyện",
        ];
    }
}