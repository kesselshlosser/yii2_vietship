<?php
namespace app\modules\coupon\models;

use Yii;
use yii\helpers\StringHelper;
use app\modules\goidichvu\models\Goidichvu;
use app\modules\khachhang\models\Khachhang;

class Coupon extends \yii\easyii\components\ActiveRecord
{
    public $dvpt = [];
    public $kv = [];
    public static function tableName()
    {
        return 'coupon';
    }

    public function rules()
    {
        return [
            [['hinh_thuc_khuyen_mai', 'gia_tri', 'gdv_id', 'tien_to'], 'required', 'message' => '{attribute} không được để trống'],
            [['dich_vu_phu_troi', 'khu_vuc', 'chi_giam_dich_vu_phu_troi'], 'string'],
            [['gia_tri', 'gioi_han', 'so_luong_coupon'], 'integer', 'message' => "{attribute} phải là kiểu số"],
            [['so_luong_coupon'], 'integer', 'min' => 1, 'max' => 1000, 'tooBig' => 'Số lượng coupon cho phép ít hơn 1000', 'tooSmall' => 'Số lượng coupon cho phép nhiều hơn 0'],
            [['kh_id', 'ma_coupon', 'ten_coupon', 'mo_ta'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            "ma_coupon" => "Mã coupon",
            'gioi_han' => "Số lượt áp dụng",
            'hinh_thuc_khuyen_mai' => "Hình thức khuyến mại*",
            'gia_tri' => 'Giá trị áp dụng*',
            'gdv_id' => "Gói dịch vụ*",
            'so_luong_coupon' => "Số lượng coupon",
            'ten_coupon' => 'Tên coupon',
            'mo_ta' => 'Mô tả',
            'tien_to' => 'Tiền tố*',
            'kh_id' => 'Khách hàng'
        ];
    }
    
    public function getGoidichvu()
    {
        return $this->hasOne(Goidichvu::className(), ['gdv_id' => 'gdv_id']);
    }
    
    public function getKhachhang()
    {
        return $this->hasOne(Khachhang::className(), ['kh_id' => 'kh_id']);
    }
}