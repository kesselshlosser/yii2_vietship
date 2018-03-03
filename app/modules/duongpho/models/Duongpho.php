<?php
namespace app\modules\duongpho\models;

use Yii;
use yii\helpers\StringHelper;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\quanhuyen\models\Quanhuyen;

class Duongpho extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'duong_pho';
    }

    public function rules()
    {
        return [
            ['ten_pho', 'required', 'message' => 'Tên đường phố không được để trống'],
            ['kv_id', 'required', 'message' => 'Khu vực không được để trống'],
            ['qh_id', 'required', 'message' => 'Quận huyện không được để trống']
        ];
    }

    public function attributeLabels()
    {
        return [
            'ten_pho' => "Tên đường phố",
            'kv_id' => 'Khu vực',
            'qh_id' => 'Quận huyện'
        ];
    }
    
    public function getKhuvuc()
    {
        return $this->hasOne(Khuvuc::className(), ['kv_id' => 'kv_id']);
    }
    
    public function getQuanhuyen()
    {
        return $this->hasOne(Quanhuyen::className(), ['qh_id' => 'qh_id']);
    }
}