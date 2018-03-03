<?php
namespace app\modules\khuvuc\models;

use Yii;
use yii\helpers\StringHelper;

class Khuvuc extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'khu_vuc';
    }

    public function rules()
    {
        return [
            ['ten_khu_vuc', 'required', 'message' => 'Tên khu vực không được để trống'],
            ['ten_khu_vuc', 'unique', 'message' => 'Tên khu vực đã tồn tại']
        ];
    }

    public function attributeLabels()
    {
        return [
            'ten_khu_vuc' => "Tên khu vực",
        ];
    }
}