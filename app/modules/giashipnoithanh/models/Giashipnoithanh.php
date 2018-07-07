<?php
namespace app\modules\giashipnoithanh\models;

use Yii;
use yii\helpers\StringHelper;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\goidichvu\models\Goidichvu;

class Giashipnoithanh extends \yii\easyii\components\ActiveRecord
{
    public $noi_lay_id;
    public $noi_giao_id;

    public static function tableName()
    {
        return 'gia_ship_noi_thanh';
    }

    public function rules()
    {
        return [
            [
                [
                    'noi_lay_id', 'noi_giao_id', 'kvl_id', 'kvg_id',
                    'gdv_id', 'don_gia'
                ],
                'required',
                'message' => '{attribute} không được để trống'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'noi_lay_id' => "Nơi lấy",
            'noi_giao_id' => "Nơi giao",
            'kvl_id' => "Khu vực lấy",
            'kvg_id' => "Khu vực giao",
            'gdv_id' => "Gói dịch vụ",
            'don_gia' => "Đơn giá",
        ];
    }
}