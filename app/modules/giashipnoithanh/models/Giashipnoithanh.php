<?php
namespace app\modules\giashipnoithanh\models;

use Yii;
use yii\helpers\StringHelper;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\goidichvu\models\Goidichvu;

class Giashipnoithanh extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'gia_ship_noi_thanh';
    }

    public function rules()
    {
        return [
            ['kvl_id', 'required', 'message' => 'Khu vực lấy không được để trống'],
            ['kvg_id', 'required', 'message' => 'Khu vực giao không được để trống'],
            ['gdv_id', 'required', 'message' => 'Gói dịch vụ không được để trống'],
            ['don_gia', 'required', 'message' => 'Đơn giá không được để trống'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'kvl_id' => "Khu vực lấy",
            'kvg_id' => "Khu vực giao",
            'gdv_id' => "Gói dịch vụ",
            'don_gia' => "Đơn giá",
        ];
    }
}