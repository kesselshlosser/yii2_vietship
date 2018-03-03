<?php
namespace app\modules\goikhachhang\models;

use Yii;
use yii\helpers\StringHelper;
use app\modules\goidichvu\models\Goidichvu;

class Goikhachhang extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    
    public $new_gkh;
    public $thoi_gian_ap_dung = '';
    public $dvpt = [];
    public $kv = [];
    public static function tableName()
    {
        return 'goi_khach_hang';
    }

    public function rules()
    {
        return [
            ['ten_goi', 'required', 'message' => 'Tên gói khách hàng không được để trống'],
            ['thoi_gian_ap_dung', 'required', 'message' => 'Bạn chưa chọn thời gian áp dụng gói khuyến mãi'],
            ['hinh_thuc', 'required', 'message' => 'Hình thức khuyến mại không được để trống'],
            ['gdv_id', 'required', 'message' => 'Gói dịch vụ áp dụng không được để trống'],
            ['gia_tri', 'required', 'message' => 'Giá trị áp dụng không được để trống'],
            [['ten_goi', 'hinh_thuc', 'dich_vu_phu_troi', 'khu_vuc'], 'string'],
            [['gia_tri'], 'integer', 'message' => "Giá trị áp dụng phải là kiểu số"],
            ['hour_gio_ap_dung', 'integer', 'message' => "Giờ áp dụng khuyến mại phải là kiểu số"],
            [['new_ngay_bat_dau', 'new_ngay_ket_thuc', 'day_ngay_bat_dau', 'day_ngay_ket_thuc', 'hour_thoi_gian_ap_dung', 'chi_giam_dich_vu_phu_troi'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
            [['status', 'muc_do_uu_tien'], 'integer', 'message' => '{attribute} phải là kiểu số'],
            [['mo_ta'], 'safe'],
            [['muc_do_uu_tien'], 'required', 'message' => '{attribute} không được để trống'],
            [['muc_do_uu_tien'], 'integer', 'min' => 1, 'tooSmall' => 'Mức độ ưu tiên thấp nhất là 1']
        ];
    }

    public function attributeLabels()
    {
        return [
            'ten_goi' => "Tên gói",
            "hinh_thuc" => "Hình thức khuyến mại",
            "gdv_id" => "Gói dịch vụ áp dụng",
            'gia_tri' => "Giá trị áp dụng",
            'chi_giam_dich_vu_phu_troi' => "Chỉ giảm dịch vụ phụ trội",
            'dich_vu_phu_troi' => "Dịch vụ phụ trội",
            'khu_vuc' => 'Khu vực',
            'day_ngay_bat_dau' => "Ngày bắt đầu",
            'day_ngay_ket_thuc' => "Ngày kết thúc",
            'hour_gio_ap_dung' => "Giờ áp dụng",
            'new_ngay_bat_dau' => "Ngày bắt đầu",
            'new_ngay_ket_thuc' => "Ngày kết thúc", 
            "hour_gio_ap_dung" => "Giờ áp dụng",
            'new_gkh' => "Áp dụng cho thành viên đăng ký mới",
            'muc_do_uu_tien' => "Mức độ ưu tiên",
//            'ap_dung_cung_coupon' => 'Áp dụng cùng coupon'
        ];
    }
    
    public function getGoidichvu()
    {
        return $this->hasOne(Goidichvu::className(), ['gdv_id' => 'gdv_id']);
    }
}