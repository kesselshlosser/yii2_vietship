<?php
namespace app\modules\donhang\models;

use Yii;
use yii\helpers\StringHelper;
use yii\easyii\models\Diachilayhang;

class Donhang extends \yii\easyii\components\ActiveRecord
{
    public $dvpt;
    public $nguoi_nhan_ten;                 //Là 1 thành phần tạo thành json của trường nguoi_nhan
    public $nguoi_nhan_dia_chi_giao_hang;   //Là 1 thành phần tạo thành json của trường nguoi_nhan
    public $nguoi_nhan_so_dien_thoai;       //Là 1 thành phần tạo thành json của trường nguoi_nhan
    
    public $san_pham_ten;                   //Là 1 thành phần tạo thành json của trường san_pham
    public $san_pham_so_luong;              //Là 1 thành phần của tạo thành json của trường nguoi_nhan
    
    public $dvpt1_ghi_chu;
    public $dvpt2_gio_giao;
    public $dvpt4_dai;
    public $dvpt4_rong;
    public $dvpt4_cao;
    public $dvpt4_can_nang;
    
    public static function tableName()
    {
        return 'don_hang';
    }

    public function rules()
    {
        return [
            [
                [
                    'nguoi_nhan_dia_chi_giao_hang', 'nguoi_nhan_so_dien_thoai', 'pho_giao_hang', 'gdv_id',
                    'hinh_thuc_thanh_toan', 'kh_id', 'dia_chi_lay_hang'
                ],
                'required',
                'message' => '{attribute} không được để trống'
            ],
            [
                [
                    'ma_don_hang', 'trang_thai', 'nguoi_nhan_dia_chi_giao_hang', 'nguoi_nhan_ten',
                    'san_pham_ten', 'dich_vu_phu_troi', 'ghi_chu', 'ly_do_khong_duyet', 'pham_vi_don_hang',
                    'hoan_hang', 'nguoi_nhan', 'san_pham', 'dia_chi_lay_hang'
                ],
                'string',
                'message' => '{attribute phải là kiểu chuỗi}'
            ],
            [
                [
                    'kh_id', 'tong_tien', 'cp_id', 'dclh_id', 'te',
                    'tien_thu_ho', 'ung_tien', 'nhan_vien_lay_hang',
                    'nhan_vien_giao_hang', 'nhan_vien_hoan_hang',
                    'time', 'lay_hang_ve', 'dvpt4_dai', 'dvpt4_rong', 'dvpt4_cao', 'dvpt4_can_nang'
                ],
                'integer',
                'message' => "{attribute} phải là kiểu số"
            ],
            ['time', 'default', 'value' => time()],
            [
                'tien_thu_ho', 'required',
                'whenClient' => "function(attribute, value) {
                    var formId = attribute.\$form[0].id;
                    return ($('#'+formId+'-hinh_thuc_thanh_toan').val() == 'Thanh toán sau COD');
                }",
                'message' => 'Tiền thu hộ phải có đối với hình thức thanh toán sau COD'
            ],
            [
                'tien_thu_ho', 'compare', 'compareAttribute' => 'tong_tien', 'operator' => '>=',
                'whenClient' => "function(attribute, value) {
                    var formId = attribute.\$form[0].id;
                    return ($('#'+formId+'-hinh_thuc_thanh_toan').val() == 'Thanh toán sau COD' && $('#'+formId+'-tien_thu_ho').val() < $('#'+formId+'-tong-tien').val());
                }",
                'message' => 'Tiền thu hộ không được nhỏ hơn tổng tiền ship'
            ],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'nguoi_nhan_dia_chi_giao_hang' => 'Địa chỉ giao hàng',
            'dia_chi_lay_hang' => 'Địa chỉ lấy hàng',
            'nguoi_nhan_so_dien_thoai' => 'Số điện thoại',
            'pho_giao_hang' => 'Phố giao hàng',
            'gdv_id' => 'Gói dịch vụ',
            'hinh_thuc_thanh_toan' => 'Hình thức thanh toán',
            'tien_thu_ho' => 'Tiền thu hộ',
            'ung_tien' => "Ứng tiền",
            'lay_hang_ve' => 'Lấy hàng về',
            'tong_tien' => 'Tổng tiền cước (VNĐ)',
            'dvpt4_dai' => 'Dài(cm)',
            'dvpt4_rong' => 'Rộng(cm)',
            'dvpt4_cao' => 'Cao(cm)',
            'dvpt4_can_nang' => 'Nặng(kg)',
        ];
    }
    
    public static function getStock($kh_id)
    {
        $data = \yii\easyii\models\Diachilayhang::find()
            ->where(['kh_id' => $kh_id])
            ->select(['kh_id','ten_goi_nho','dia_chi_text','dp_id', 'so_dien_thoai', 'dclh_id'])->asArray()->all();
        return $data;
    }
}