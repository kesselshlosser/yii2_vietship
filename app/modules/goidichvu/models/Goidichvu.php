<?php
namespace app\modules\goidichvu\models;

use Yii;
use yii\helpers\StringHelper;

class Goidichvu extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'goi_dich_vu';
    }

    public function rules()
    {
        return [
            ['ten_goi_dich_vu', 'required', 'message' => 'Tên gói dịch vụ không được để trống'],
            ['ten_goi_dich_vu', 'unique', 'message' => 'Tên gói dịch vụ đã tồn tại']
        ];
    }

    public function attributeLabels()
    {
        return [
            'ten_goi_dich_vu' => "Tên gói dịch vụ",
        ];
    }
}