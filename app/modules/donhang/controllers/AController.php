<?php
namespace app\modules\donhang\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\modules\donhang\models\Donhang;
use yii\helpers\Json;
use app\modules\giashipnoithanh\models\Giashipnoithanh;
use app\modules\goidichvu\models\Goidichvu;
use yii\easyii\models\Khachhangcoupon;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\duongpho\models\Duongpho;
use app\modules\goikhachhang\models\Goikhachhang;
use app\modules\khachhang\models\Khachhang;

class AController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Donhang::find(),
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    public function actionCreate()
    {
        $model = new Donhang;
        //Khởi tạo mảng dịch vụ phụ trội
        $model_dvpt = [];
        $model_dvpt['dvpt1'] = [
            'content' => 'Giao hàng mẫu, đổi hàng',
            'key' => 'dvpt1',
            'value' => 0
        ];
        $model_dvpt['dvpt2'] = [
            'content' => 'Hẹn giờ giao, giao sau giờ hành chính',
            'key' => 'dvpt2',
            'value' => 0
        ];
        $model_dvpt['dvpt3'] = [
            'content' => 'Giao bến xe, văn phòng xe',
            'key' => 'dvpt3',
            'value' => 0
        ];
        $model_dvpt['dvpt4'] = [
            'content' => 'Hàng quá khổ',
            'key' => 'dvpt4',
            'value' => 0
        ];

        $model->dvpt = $model_dvpt;
        
                
        if ($model->load(Yii::$app->request->post())) {
            $dataPost = Yii::$app->request->post();
            $kh_id = $dataPost['kh_id'];
            $hinh_thuc_thanh_toan = $dataPost['Donhang']['hinh_thuc_thanh_toan'];
            $tong_tien = $dataPost['Donhang']['tong_tien'];
            $dia_chi_lay_hang = $dataPost['dia_chi_lay_hang'];
            $model->dclh_id = $dataPost['kvl_id'];
            
            $nguoi_nhan_ten = $dataPost['Donhang']['nguoi_nhan_ten'];
            $nguoi_nhan_dia_chi_giao_hang = $dataPost['Donhang']['nguoi_nhan_dia_chi_giao_hang'];
            $nguoi_nhan_so_dien_thoai = $dataPost['Donhang']['nguoi_nhan_so_dien_thoai'];
            
            $san_pham_ten = $dataPost['Donhang']['san_pham_ten'];
            $san_pham_so_luong = $dataPost['Donhang']['san_pham_so_luong'];
            
            //Xử lý mã đơn hàng = kh_id + 4 chữ số bắt đầu từ 1001
            $checkDonHang = Donhang::find()->where(['kh_id' => $kh_id])->orderBy(['dh_id' => SORT_DESC])->asArray()->one();
            
            if(count($checkDonHang) == 0) //Chưa có đơn hàng cho khách hàng này bắt đầu tạo mới từ 1001
            {
                $model->te = 1001;
                $model->ma_don_hang = $kh_id.$model->te;
                
            }
            else if(count($checkDonHang > 0)) //Lấy ra cái te và cộng thêm 1
            {
                $model->te = $checkDonHang['te'] + 1;
                $model->ma_don_hang = $kh_id.$model->te;
            }
            
            if($hinh_thuc_thanh_toan == 'Người gửi thanh toán')
            {
                $model->ma_don_hang .= 'G'.$tong_tien;
            }
            
            //Xử lý người nhận
            $arr_nguoi_nhan = [
                'ten' => $nguoi_nhan_ten,
                'dia_chi_giao_hang' => $nguoi_nhan_dia_chi_giao_hang,
                'so_dien_thoai' => $nguoi_nhan_so_dien_thoai
            ];
            $model->nguoi_nhan = json_encode($arr_nguoi_nhan, JSON_UNESCAPED_UNICODE);
            
            //Xử lý sản phẩm
            $arr_san_pham = [
                'ten' => $san_pham_ten,
                'so_luong' => $san_pham_so_luong
            ];
            $model->san_pham = json_encode($arr_san_pham, JSON_UNESCAPED_UNICODE);
            
             //Xử lý dịch vụ phụ trội
            $model->dich_vu_phu_troi = $dataPost['dvpt'];
            
            //Phạm vi đơn hàng là nội thành
            $model->pham_vi_don_hang = 'nội thành';
            
            //Địa chỉ lấy hàng
            $model->dia_chi_lay_hang = $dia_chi_lay_hang;
            
            //Các trường mặc định
            $model->trang_thai = 'Đã duyệt,chờ lấy';
            $model->kh_id = $kh_id;
            $model->time = time();
            
            if($model->save(false)){
                
                if($dataPost['Donhang']['cp_id'])
                {
                    //Lưu khách hàng sử dụng coupon nào vào bảng kh_coupon
                    $cp_id = $dataPost['Donhang']['cp_id'];
                    //Kiểm tra xem đã sử dụng chưa
                    $model_kh_cp = Khachhangcoupon::findOne(['cp_id' => $cp_id, 'kh_id' => $kh_id]);
                    if(count($model_kh_cp) > 0) //Cập nhật da_su_dung
                    {
                        $model_kh_cp->da_su_dung = $model_kh_cp->da_su_dung + 1;
                        $model_kh_cp->save();
                    }else
                    {
                        $model_kh_cp = new Khachhangcoupon();
                        $model_kh_cp->cp_id = $cp_id;
                        $model_kh_cp->kh_id = $kh_id;
                        $model_kh_cp->da_su_dung = 1;
                        $model_kh_cp->save();
                    }
                    
                    //cập nhật lần sử dụng này vào bảng coupon
                    $model_cp = \app\modules\coupon\models\Coupon::findOne(['cp_id' => $cp_id]);
                    $model_cp->da_su_dung = $model_cp->da_su_dung + 1;
                    $model_cp->save();
                }
                
                $result = [
                    'message' => 'success',
                    'data' => [
                        'ma_don_hang' =>  $model->ma_don_hang,
                        'id' => $model->dh_id
                    ]
                ];
                return json_encode($result, JSON_UNESCAPED_UNICODE);
            }
            else{
                return [
                    'message' => 'error',
                ];
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionSubcat() {    
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $kh_id = $parents[0];
                $data = Donhang::getStock($kh_id);
                $out = [];
                for($i=0; $i<count($data); $i++)
                {
                    $pho = \app\modules\duongpho\models\Duongpho::find()->where(['dp_id' => $data[$i]['dp_id']])->one()['ten_pho'];
                    $out[$i]['id'] = $data[$i]['dclh_id'];
                    $out[$i]['name'] = $data[$i]['ten_goi_nho'].'/'.$data[$i]['so_dien_thoai'].'/'.$data[$i]['dia_chi_text'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
    
    public function actionSubcatedit($id)
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $kh_id = $parents[0];
                $data = Donhang::getStock($kh_id);
                $out = [];
                for($i=0; $i<count($data); $i++)
                {
                    $pho = \app\modules\duongpho\models\Duongpho::find()->where(['dp_id' => $data[$i]['dp_id']])->one()['ten_pho'];
                    $out[$i]['id'] = $data[$i]['dclh_id'];
                    $out[$i]['name'] = $data[$i]['ten_goi_nho'].'/'.$data[$i]['so_dien_thoai'].'/'.$data[$i]['dia_chi_text'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>$id]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionEdit($id)
    {
        $model = Donhang::findOne($id);
        //Xử lý người nhận và sản phẩm
        $arr_nguoi_nhan = json_decode($model->nguoi_nhan, true);
        $model->nguoi_nhan_ten = $arr_nguoi_nhan['ten'];
        $model->nguoi_nhan_dia_chi_giao_hang = $arr_nguoi_nhan['dia_chi_giao_hang'];
        $model->nguoi_nhan_so_dien_thoai = $arr_nguoi_nhan['so_dien_thoai'];
        
        //Xử lý sản phẩm
        $arr_san_pham = json_decode($model->san_pham, true);
        $model->san_pham_ten = $arr_san_pham['ten'];
        $model->san_pham_so_luong = $arr_san_pham['so_luong'];
        //Xử lý JSON dich_vu_phu_troi;
        if($model->dich_vu_phu_troi)
        {
            $arr_dvpt = json_decode($model->dich_vu_phu_troi, true);
            foreach($arr_dvpt as $item)
            {
                if($item['value'] == 1)
                {
                    if($item['key'] == 'dvpt1') //Giao hàng mẫu, đổi hàng
                    {
                        $model->dvpt1_ghi_chu = $item['note'];
                    }
                    elseif($item['key'] == 'dvpt2') //Hẹn giờ giao, giao sau giờ hành chính
                    {
                        $model->dvpt2_gio_giao = $item['note'];
                    }
                    elseif($item['key'] == 'dvpt4') //Hàng quá khổ
                    {
                        $model->dvpt4_dai = $item['note']['dai'];
                        $model->dvpt4_rong = $item['note']['rong'];
                        $model->dvpt4_cao = $item['note']['cao'];
                        $model->dvpt4_can_nang = $item['note']['nang'];
                    }
                }
            }
        }
        
        $model->dvpt = $arr_dvpt;
        
        if ($model->load(Yii::$app->request->post())) {
            $dataPost = Yii::$app->request->post();
            //Xử lý người nhận
            $arr_nguoi_nhan = [
                'ten' => $dataPost['Donhang']['nguoi_nhan_ten'],
                'dia_chi_giao_hang' => $dataPost['Donhang']['nguoi_nhan_dia_chi_giao_hang'],
                'so_dien_thoai' => $dataPost['Donhang']['nguoi_nhan_so_dien_thoai']
            ];
            $model->nguoi_nhan = json_encode($arr_nguoi_nhan, JSON_UNESCAPED_UNICODE);
            //Xử lý sản phẩm
            $arr_san_pham = [
                'ten' => $dataPost['Donhang']['san_pham_ten'],
                'so_luong' => $dataPost['Donhang']['san_pham_so_luong'],
            ];
            $model->san_pham = json_encode($arr_san_pham, JSON_UNESCAPED_UNICODE);
            //Xử lý dịch vụ phụ trội
            $model->dich_vu_phu_troi = $dataPost['dvpt'];
            
            if($model->save(false))
            {
                $result = [
                    'message' => 'success',
                    'data' => [
                        'ma_don_hang' =>  $model->ma_don_hang,
                        'id' => $model->dh_id
                    ]
                ];
                return json_encode($result, JSON_UNESCAPED_UNICODE);
            }  else {
                return [
                    'message' => 'error'
                ];
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if(($model = Donhang::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Không tìm thấy đơn hàng nào";
        }
        return $this->formatResponse("Xóa đơn hàng thành công");
    }
    
    //Tính tiền tự động ajax
    public function actionTinhTienTuDong()
    {
        $dataPost = Yii::$app->request->post();
        $pho_giao_hang_id = $dataPost['Donhang']['pho_giao_hang'];
        $dclh_id = $dataPost['kvl_id'];
        $pho_lay_hang_id = \yii\easyii\models\Diachilayhang::find()->where(['dclh_id' => $dclh_id])->one()['dp_id'];
        $gdv_id = $dataPost['Donhang']['gdv_id'];
        $arr_dvpt = json_decode($dataPost['dvpt'], true);
        $cp_id = $dataPost['Donhang']['cp_id'];
        $kvl_id = Duongpho::find()->where(['dp_id' => $pho_lay_hang_id])->one()['kv_id'];
        $kvg_id = Duongpho::find()->where(['dp_id' => $pho_giao_hang_id])->one()['kv_id'];
        $kh_id = $dataPost['kh_id'];
        
        $error_message = '';
        $tang_tien_message = '';
        
        $tongtien = Giashipnoithanh::find()->where([
            'kvl_id' => $kvl_id,
            'kvg_id' => $kvg_id,
            'gdv_id' => $gdv_id
        ])->one()['don_gia'];
        
        if(!$tongtien)
        {
            $tongtien = 0;
        }
        
        //Xử lý phần dịch vụ phụ trội
        $phi_dvpt = 0;
        foreach($arr_dvpt as $key => $value)
        {
            if($value['value'] == 1)
            {
                if($key == 'dvpt1')
                {
                    $phi_dvpt += 10000;
                }
                elseif ($key == 'dvpt2') 
                {
                    $phi_dvpt += 10000;
                }
                elseif ($key == 'dvpt3') 
                {
                    $phi_dvpt += 10000;
                }
                elseif($key == 'dvpt4')
                {
                    //Theo kích thước
                    $dai = $value['note']['dai'];
                    $rong = $value['note']['rong'];
                    $cao = $value['note']['cao'];
                    
                    if($dai == 0 || $rong == 0 || $cao == 0)
                    {
                        $kg_kich_thuoc = 0;
                    }elseif($dai != 0 && $rong != 0 && $cao != 0)
                    {
                        $kg_kich_thuoc = ($dai*$rong*$cao)/6000;
                    }
                    
                    //Theo cân nặng
                    $kg_nang = $value['note']['nang'];
                    if($kg_nang >= $kg_kich_thuoc)
                    {
                        $nang = $kg_nang;
                    }elseif($kg_kich_thuoc >= $kg_nang)
                    {
                        $nang = $kg_kich_thuoc;
                    }
                    
                    if($nang >= 5 && $nang < 10) //Cộng 15000
                    {
                        $phi_dvpt += 15000;
                    }elseif($nang >= 10 && $nang < 25)
                    {
                        $phi_dvpt += 25000;
                    }elseif($nang >= 25 && $nang <40)
                    {
                        $phi_dvpt += 40000;
                    }elseif($nang >= 40 && $nang <60)
                    {
                        $phi_dvpt += 60000;
                    }
                }
            }
        }
        
        //Xử lý tổng tiền khi có khách hàng và địa chỉ lấy hàng
        //Lấy ra gói khách hàng mà khách hàng này được hưởng
        $arrGoiKhachHang = $this->GetGoiKhachHang($kh_id, $kvg_id, $gdv_id); //Toàn bộ thông tin gói khách hàng mà người này được hưởng
        if($dataPost['Donhang']['cp_id'])
        {
            $cp_id = $dataPost['Donhang']['cp_id'];
            $cp_details = \app\modules\coupon\models\Coupon::find()->where(['cp_id' => $cp_id])->asArray()->one();
            $ap_dung_cung_gkh = $cp_details['ap_dung_cung_goi_khach_hang'];
        }
        $cp_status = 0;
        if(count($arrGoiKhachHang) > 0) //Áp dụng khuyến mại
        {
//            print_r($arrGoiKhachHang);
//            echo count($arrGoiKhachHang).'<br>';
//            echo $tongtien.'<br>';
//            echo $phi_dvpt.'<br>';
//            exit();
            $tongtien = $this->TinhTienGoiKhachHang($tongtien, $phi_dvpt, $arrGoiKhachHang);
            //Kiểm tra điều kiện áp dụng coupon
            if(isset($ap_dung_cung_gkh))
            {
                if($ap_dung_cung_gkh == 1) //Áp dụng cùng gói khách hàng
                {
                    $cp_status = 1;
                }else
                {
                    $error_message = 'Coupon không áp dụng cùng gói khách hàng';
                }
            }
        }else
        {
            $tongtien += $phi_dvpt;
            //Kiểm tra điều kiện áp dụng coupon
            if(isset($cp_id))
            {
                $cp_status = 1;
            }
        }
        
        //Xử lý tổng tiền khi có mã coupon
        if($dataPost['Donhang']['cp_id'] && $cp_status == 1)
        {
            $arr_check_coupon = $this->CheckCoupon($cp_details, $kh_id, $kvg_id, $gdv_id);
            if($arr_check_coupon['status'] == 0) //Coupon không còn sử dụng được
            {
                $error_message = $arr_check_coupon['errorMessage'];
            }else if($arr_check_coupon['status'] == 1) //Coupon còn sử dụng được
            {
                $hinh_thuc_khuyen_mai = $arr_check_coupon['hinh_thuc_khuyen_mai'];
                $gia_tri = $arr_check_coupon['gia_tri'];
                if($hinh_thuc_khuyen_mai == 'Giảm cước')
                {
                    $tongtien = $tongtien - $gia_tri;
                }
                else if($hinh_thuc_khuyen_mai == 'Giảm theo %')
                {

                    $tongtien = ($tongtien*(100 - $gia_tri))/100;
                }
                else if($hinh_thuc_khuyen_mai == 'Đồng giá')
                {
                    $tongtien = $gia_tri;
                }
                else if($hinh_thuc_khuyen_mai == 'Tặng tiền')
                {
                    $tang_tien_message = 'Bạn được tặng '.$gia_tri.' VNĐ vào số dư trong tài khoản';
                }
            }
            
//            $ngay_bat_dau = $cp_details['ngay_bat_dau'];
//            $ngay_ket_thuc = $cp_details['ngay_ket_thuc'];
//            $current_time = time();
            
            //Kiểm tra coupon này đã hết hạn sử dụng hay chưa
//            $kv_can = 0;
//            $gdv_can = 0;
//            //Kiểm tra xem khu vực có được áp dụng hay không
//            $arr_kv = json_decode($cp_details['khu_vuc'], true);
//            $kv = [];
//            foreach($arr_kv as $item)
//            {
//                if($item['value'] == 1)
//                {
//                    $kv[] = $item['id'];
//                }
//            }
//
//            $khu_vuc_giao_hang = $kvg_id;
////                print_r($kv);
////                echo 'Khu vực giao hàng : '.$khu_vuc_giao_hang;
////                exit();
//            foreach($kv as $item)
//            {
//                if($item == $khu_vuc_giao_hang)
//                {
//                    $kv_can = 1;
//                    break;
//                }
//            }
//
//            //Kiểm tra xem gói dịch vụ có được áp dụng hay không
//            $arr_gdv = json_decode($cp_details['gdv_id'], true);
//            foreach($arr_gdv as $item)
//            {
//                if($item == $gdv_id)
//                {
//                    $gdv_can = 1;
//                    break;
//                }
//            }
//
//            if($gdv_can == 0)
//            {
//                $error_message = 'Mã khuyến mại không áp dụng cho khu vực này';
//            }
//            if($kv_can == 0)
//            {
//                $error_message = 'Mã khuyến mại không áp dụng cho khu vực giao hàng này';
//            }
//
//            if($gdv_can == 1 && $kv_can == 1) //Thực hiện giảm giá theo coupon
//            {
//                if($hinh_thuc_khuyen_mai == 'Giảm cước')
//                {
//                    $tongtien = $tongtien - $gia_tri;
//                }
//                else if($hinh_thuc_khuyen_mai == 'Giảm theo %')
//                {
//
//                    $tongtien = ($tongtien*(100 - $gia_tri))/100;
//                }
//                else if($hinh_thuc_khuyen_mai == 'Đồng giá')
//                {
//                    $tongtien = $gia_tri;
//                }
//                else if($hinh_thuc_khuyen_mai == 'Tặng tiền')
//                {
//                    $tongtien = $tongtien + $gia_tri;
//                }
//            }
        }
        $result = [
            'tongTien' => $tongtien,
            'couponMessage' => $error_message,
            'tangTienMessage' => $tang_tien_message
        ];
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    //Xử lý thông báo giờ lấy hàng và giờ giao hàng ajax thông qua gói dịch vụ
    public function actionThongBaoThoiGianShip()
    {
        $dataPost = Yii::$app->request->post();
        $gdv_id = $dataPost['gdv_id'];
        $gdv = Goidichvu::find()->where(['gdv_id' => $gdv_id])->one()['ten_goi_dich_vu'];
        $currentTimeStamp = time();
        
        $currentHour = (int)date('H', $currentTimeStamp);
        $currentMinute = (int)date('i', $currentTimeStamp);
        $dayOfWeek = (int)date('w', $currentTimeStamp); //0 : Sunday, 6 : Saturday
        
        //Quy đổi hết ra phút và so sánh
        $mocGio = 11*60 + 30;
        $gioHienTai = $currentHour*60 + $currentMinute;
        
        if($gdv == 'Chuyển nhanh')
        {
            if($dayOfWeek != 0) //Không làm ngày chủ nhật
            {
                if($gioHienTai <= $mocGio) //<= 11h30
                {
                    $thoigianship = '-- Thời gian lấy: 8h30 - 12h hôm nay ('.date('d/m/Y', $currentTimeStamp).').<br>';
                    $thoigianship .= '-- Thời gian giao: 13h30 - 17h hôm nay ('.date('d/m/Y', $currentTimeStamp).').<br>.';
                }
                else //>11h30
                {
                    $thoigianship = '-- Thời gian lấy: 13h30 - 17h30 hôm nay ('.date('d/m/Y', $currentTimeStamp).').<br>';
                    if($dayOfWeek == 6) //Lấy vào chiều thứ 7 và giao vào sáng thứ 2 cộng 2 ngày
                    {
                        $ngay_giao = date('d/m/Y', strtotime('+2 days', $currentTimeStamp));
                        $thoigianship .= '-- Thời gian giao: 8h30 - 12h thứ 2 ('.$ngay_giao.').<br>';
                    }else
                    {
                        $ngay_giao = date('d/m/Y', strtotime('+1 days', $currentTimeStamp));
                        $thoigianship .= '-- Thời gian giao: 8h30 - 12h hôm sau ('.$ngay_giao.').<br>';
                    }
                }
            }
        }
        elseif($gdv == 'Tiết kiệm')
        {
            if($dayOfWeek != 0)
            {
                if($gioHienTai <= $mocGio) //<= 11h30
                {
                    $thoigianship = '-- Thời gian lấy: 8h30 - 17h30 hôm nay ('.date('d/m/Y', $currentTimeStamp).').<br>';
                    if($dayOfWeek == 6) //Thứ 7 lấy vào sáng hôm nay và giao sáng thứ 2
                    {
                        $ngay_giao = date('d/m/Y', strtotime('+2 days', $currentTimeStamp));
                        $thoigianship .= '-- Thời gian giao: 8h30 - 12h thứ 2 ('.$ngay_giao.').<br>';
                    }else
                    {
                        $ngay_giao = date('d/m/Y', strtotime('+1 days', $currentTimeStamp));
                        $thoigianship .= '-- Thời gian giao: 8h30 - 12h hôm sau ('.$ngay_giao.').<br>';
                    }
                }
                else //>11h30
                {
                    if($dayOfWeek == 6) //Chiều thứ 7 nhận đơn sáng thứ 2 lấy và giao chiều thứ 2
                    {
                        $ngay_lay = date('d/m/Y', strtotime('+2 days', $currentTimeStamp));
                        $ngay_giao = date('d/m/Y', strtotime('+2 days', $currentTimeStamp));
                        $thoigianship = '-- Thời gian lấy: 8h30 - 12 thứ 2 ('.$ngay_lay.').<br>';
                        $thoigianship .= '-- Thời gian giao: 13h30 - 17h30 thứ 2 ('.$ngay_giao.').<br>';
                    }else
                    {
                        $ngay_lay = date('d/m/Y', strtotime('+1 days', $currentTimeStamp));
                        $ngay_giao = date('d/m/Y', strtotime('+1 days', $currentTimeStamp));
                        $thoigianship = '-- Thời gian lấy: 8h30 - 12 hôm sau ('.$ngay_lay.').<br>';
                        $thoigianship .= '-- Thời gian giao: 13h30 - 17h30 hôm sau ('.$ngay_giao.').<br>';
                    }
                }
            }
        }
        elseif($gdv == 'Hỏa tốc')
        {
            if($dayOfWeek != 0)
            {
                //8h30 - 11h30 : 480 - 690
                //13h30 - 16h30 : 810 - 990
                //TH1 : $gioHienTai < 480 -> status = 0 (Ngoài giờ hành chính) giao từ 8h - 10h hôm nay
                if($gioHienTai < 480)
                {
                    $ngay_lay_giao = date('d/m/Y', $currentTimeStamp);
                    $thoigianship = '-- Thời gian lấy và giao: 8h - 10h hôm nay ('.$ngay_lay_giao.').<br>';
                }
                //TH2 : 690 - 810 -> status = 1 (Ngoài giờ hành chính) giao 13h30 - 15h30 hôm nay
                elseif($gioHienTai > 690 && $gioHienTai < 810)
                {
                    $ngay_lay_giao = date('d/m/Y', $currentTimeStamp);
                    $thoigianship = '-- Thời gian lấy và giao: 13h30 - 15h30 hôm nay ('.$ngay_lay_giao.').<br>';
                }
                //TH3 : >990 -> status = 2 (Ngoài giờ hành chính) giao 8h - 10h hôm sau
                elseif($gioHienTai > 990)
                {
                    $ngay_lay_giao = date('d/m/Y', strtotime('+1 days', $currentTimeStamp));
                    $thoigianship = '-- Thời gian lấy và giao: 8h - 10h hôm sau ('.$ngay_lay_giao.').<br>';
                }
                //TH4 : 480 <=  <= 690 -> status = 3 (Trong giờ hành chính buổi sáng) giao sau 2 tiếng
                elseif(($gioHienTai >= 480 && $gioHienTai <= 690) || ($gioHienTai >= 810 && $gioHienTai <= 990))
                {
                    $ngay_lay_giao = date('d/m/Y', $currentTimeStamp);
                    $lay = $currentHour.'h'.$currentMinute;
                    $giao = ($currentHour+2).'h'.$currentMinute;
                    $strLayGiao = $lay.' - '.$giao;
                    $thoigianship = '-- Thời gian lấy và giao: '.$strLayGiao.' hôm nay ('.$ngay_lay_giao.').<br>';
                }
            }
        }
        
        return $thoigianship;
    }
    
    //Function print order
    public function actionPrint($id)
    {
        $model = Donhang::findOne($id);
        return $this->renderPartial('print', ['model' => $model]);
    }
    
    //Function lấy ra những gói khách hàng được áp dụng đối với khách hàng và có khu vực áp dụng tương ứng với khu vực giao
    public function GetGoiKhachHang($kh_id, $kvg_id, $gdv_id)
    {
        $currentTime = time();
        $arrGoiKhachHang = json_decode(Khachhang::find()->where(['kh_id' => $kh_id])->one()['gkh_id'], true);
        for($i = 0; $i < count($arrGoiKhachHang); $i++)
        {
            $muc_do_uu_tien = Goikhachhang::find()->where(['gkh_id' => $arrGoiKhachHang[$i]])->one()['muc_do_uu_tien'];
            if($i == 0 )
            {
                $muc_do_uu_tien_nho_nhat = $muc_do_uu_tien;
            }else
            {
                if($muc_do_uu_tien < $muc_do_uu_tien_nho_nhat)
                {
                    $muc_do_uu_tien_nho_nhat = $muc_do_uu_tien;
                }
            }
        }
        
        $arrGoiKhachHangApDung = Goikhachhang::find()->where(['muc_do_uu_tien' => $muc_do_uu_tien_nho_nhat])->asArray()->all();
        
        foreach($arrGoiKhachHangApDung as $key => $item)
        {
            $khuvuc = json_decode($item['khu_vuc'], true);
            $delete = 0;
            
            //Kiểm tra khu vực
            foreach($khuvuc as $kv)
            {
                if($kv['value'] == 1)
                {
                    
                    if($kv['id'] == $kvg_id)
                    {
                        $delete++;
                    }
                }
            }
            //Kiểm tra thời gian áp dụng
            $thoi_gian_ap_dung = $this->ApDungTheoNgayHayGio($item);
            if($thoi_gian_ap_dung['type'] == 'day') //Áp dụng theo ngày
            {
                $begin = $thoi_gian_ap_dung['begin'];
                $end = $thoi_gian_ap_dung['end'];
                if($begin <= $currentTime && $end >= $currentTime)
                {
                    
                }else
                {
                    unset($arrGoiKhachHangApDung[$key]);
                    continue;
                }
            }else if($thoi_gian_ap_dung['type'] == 'hour') //Áp dụng theo giờ
            {
                $begin = $thoi_gian_ap_dung['begin'];
                $time = $thoi_gian_ap_dung['time']*3600000;
                $timeCompare = $begin + $time;
                if($timeCompare > $currentTime)
                {
                    unset($arrGoiKhachHangApDung[$key]);
                    continue;
                }
            }
            if($delete == 0)
            {
                unset($arrGoiKhachHangApDung[$key]);
                continue;
            }
            //Kiểm tra gói dịch vụ
            $arr_gdv = json_decode($item['gdv_id'],true);
            $check_gdv = 0;
            foreach($arr_gdv as $gdv)
            {
                if($gdv == $gdv_id)
                {
                    $check_gdv++;
                }
            }
            if($check_gdv == 0)
            {
                unset($arrGoiKhachHangApDung[$key]);
                continue;
            }
        };
        return $arrGoiKhachHangApDung;
    }
    
    public function ApDungTheoNgayHayGio($arrGoiKhachHang)
    {
        if($arrGoiKhachHang['day_ngay_bat_dau'] && $arrGoiKhachHang['day_ngay_ket_thuc'])
        {
            return [
                'type' => 'day',
                'begin'=> $arrGoiKhachHang['day_ngay_bat_dau'],
                'end' => $arrGoiKhachHang['day_ngay_ket_thuc']
            ];
        }
        return [
            'type' => 'hour',
            'begin' => $arrGoiKhachHang['hour_thoi_gian_ap_dung'],
            'time' => $arrGoiKhachHang['hour_gio_ap_dung']
        ];
    }
    
    public function TinhTienGoiKhachHang($tongtien, $phi_dvpt, $arrGoiKhachHangApDung = [])
    {
        $number_gkh = count($arrGoiKhachHangApDung);
        $tongcuoc = 0;
        if($number_gkh == 1) //Áp dụng một gói khách hàng thôi
        {
            foreach($arrGoiKhachHangApDung as $item)
            {
                $hinh_thuc = $item['hinh_thuc'];
                $gia_tri_ap_dung = $item['gia_tri'];
                if($hinh_thuc == 'Đồng giá')
                {
                    $tongcuoc = $gia_tri_ap_dung;
                }else
                {
                    if($item['chi_giam_dich_vu_phu_troi']) //Chỉ tính giảm trên dịch vụ phụ trội
                    {
                        if($hinh_thuc == 'Giảm theo %')
                        {
                            $giam = ($phi_dvpt*(100 - $gia_tri_ap_dung))/100;
                            if($giam < 0)
                            {
                                $giam = 0;
                            }
                            $tongcuoc = $tongtien + $giam;
                        }else if($hinh_thuc == 'Giảm cước')
                        {
                            $giam = $phi_dvpt - $gia_tri_ap_dung;
                            if($giam < 0)
                            {
                                $giam = 0;
                            }
                            $tongcuoc = $tongtien + $giam;
                        }else if($hinh_thuc == 'Tăng cước')
                        {
                            $giam = $phi_dvpt + $gia_tri_ap_dung;
                            $tongcuoc = $tongtien + $giam;
                        }
                    }else //Áp dụng trên tổng tiền cước
                    {
                        $tongcuoc = $tongtien + $phi_dvpt;
                        if($hinh_thuc == 'Giảm theo %')
                        {
                            $tongcuoc = ($tongcuoc*(100 - $gia_tri_ap_dung))/100;
                        }else if($hinh_thuc == 'Giảm cước')
                        {
                            $tongcuoc = $tongcuoc - $gia_tri_ap_dung;
                        }else if($hinh_thuc == 'Tăng cước')
                        {
                            $giam = $phi_dvpt + $gia_tri_ap_dung;
                            $tongcuoc = $tongcuoc + $gia_tri_ap_dung;
                        }
                    }
                }
            }
        }else
        {
            $tongcuoc = 0;
            $default = 0;
            $distance = 0;
            foreach($arrGoiKhachHangApDung as $item)
            {
                $hinh_thuc = $item['hinh_thuc'];
                $gia_tri_ap_dung = $item['gia_tri'];
                if($hinh_thuc == 'Đồng giá')
                {
                    $tongcuoc = $gia_tri_ap_dung;
                }else
                {
                    if($item['chi_giam_dich_vu_phu_troi']) //Chỉ tính giảm trên dịch vụ phụ trội
                    {
                        if($hinh_thuc == 'Giảm theo %')
                        {
                            $giam = ($phi_dvpt*(100 - $gia_tri_ap_dung))/100;
                            if($giam < 0)
                            {
                                $giam = 0;
                            }
                            $tiencuoc = $tongtien + $giam;
                        }else if($hinh_thuc == 'Giảm cước')
                        {
                            $giam = $phi_dvpt - $gia_tri_ap_dung;
                            if($giam < 0)
                            {
                                $giam = 0;
                            }
                            $tiencuoc = $tongtien + $giam;
                        }else if($hinh_thuc == 'Tăng cước')
                        {
                            $giam = $phi_dvpt + $gia_tri_ap_dung;
                            $tiencuoc = $tongtien + $giam;
                        }
                    }else //Áp dụng trên tổng tiền cước
                    {
                        $tiencuoc = $tongtien + $phi_dvpt;
                        if($hinh_thuc == 'Giảm theo %')
                        {
                            $tiencuoc = ($tiencuoc*(100 - $gia_tri_ap_dung))/100;
                        }else if($hinh_thuc == 'Giảm cước')
                        {
                            $tiencuoc = $tiencuoc - $gia_tri_ap_dung;
                        }else if($hinh_thuc == 'Tăng cước')
                        {
                            $giam = $phi_dvpt + $gia_tri_ap_dung;
                            $tiencuoc = $tiencuoc + $gia_tri_ap_dung;
                        }
                    }
                }
                
                $check = $tiencuoc - $default;
                if($check < $distance) //Lấy cái giá này gán cho $tongcuoc
                {
                    $distance = $check;
                    $tongcuoc = $tiencuoc;
                }
            }
        }
        return $tongcuoc;
    }
    
    public function CheckCoupon($cp_details = [], $kh_id, $kvg_id, $gdv_id)
    {
        $cp_id = $cp_details['cp_id'];
        $da_su_dung = $cp_details['da_su_dung'];
        $gioi_han = $cp_details['gioi_han'];
        $so_luong_coupon = $cp_details['so_luong_coupon'];
        $hinh_thuc_khuyen_mai = $cp_details['hinh_thuc_khuyen_mai'];
        $gia_tri = $cp_details['gia_tri'];
        $arr_kv = json_decode($cp_details['khu_vuc'], true);
        $arr_gdv = json_decode($cp_details['gdv_id'], true);
        
        //1>Test xem còn coupon hay không (< 1000; 1001 là ko hợp lệ)
        if($da_su_dung >= $so_luong_coupon)
        {
            return [
                'status' => 0,
                'errorMessage' => 'Coupon này đã phát hết'
            ];
        }else
        {
            //2>Test xem khách hàng đã sử dụng coupon này quá số lần cho phép hay không
            $so_lan_kh_su_dung_cp = Khachhangcoupon::find()->where(['cp_id' => $cp_id, 'kh_id' => $kh_id])->one()['da_su_dung'];
            if($so_lan_kh_su_dung_cp >= $gioi_han)
            {
                return [
                    'status' => 0,
                    'errorMessage' => 'Bạn đã sử dụng coupon vượt quá số lần cho phép'
                ];
            }
            
            //3>Test xem khu vực có được áp dụng hay không
            $kv_can = 0;
            foreach($arr_kv as $item)
            {
                if($item['value'] == 1)
                {
                    if($item['id'] == $kvg_id)
                    {
                        $kv_can = 1;
                    }
                }
            }
            if($kv_can == 0)
            {
                return [
                    'status' => 0,
                    'errorMessage' => 'Coupon không áp dụng cho khu vực giao hàng này'
                ];
            }
            
            //4>Test xem gói dịch vụ có được áp dụng hay không
            $gdv_can = 0;
            foreach($arr_gdv as $item)
            {
                if($item == $gdv_id)
                {
                    $gdv_can = 1;
                }
            }
            if($gdv_can == 0)
            {
                return [
                    'status' => 0,
                    'errorMessage' => 'Coupon không áp dụng cho gói dịch vụ này'
                ];
            }
            
            return [
                'status' => 1,
                'hinh_thuc_khuyen_mai' => $hinh_thuc_khuyen_mai,
                'gia_tri' => $gia_tri
            ];
        }
    }
}