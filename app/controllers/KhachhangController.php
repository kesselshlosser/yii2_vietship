<?php
namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\SortableDateController;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use \yii\web\Controller;
use app\modules\khachhang\models\Khachhang;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\StatusController;
use yii\easyii\models\Diachilayhang;
use yii\easyii\models\Hinhthucthanhtoan;
use yii\validators\RequiredValidator;
use \yii\easyii\models\Hoadon;
use \yii\easyii\models\Hoadonchitiet;
use \app\modules\donhang\models\Donhang;
use \app\modules\goidichvu\models\Goidichvu;

class KhachhangController extends Controller
{
    public function actionIndex()
    {
        // $data = new ActiveDataProvider([
        //     'query' => Khachhang::find()->sortDate(),
        //     'pagination' => [
        //         'pageSize' => 0
        //     ]
        // ]);

        // return $this->render('index', [
        //     'data' => $data
        // ]);
    }

    // Function doi mat khau
    public function actionDoimatkhau($id) {
        $model = Khachhang::findOne($id);
        if (Yii::$app->request->post()) {
            $dataPost = Yii::$app->request->post();
            $old_pw = $dataPost['mat_khau_cu'];
            $new_pw = $dataPost['mat_khau_moi'];
            $verify_pw = $dataPost['mat_khau_xac_nhan'];

            if (md5($old_pw) != $model->mat_khau) {
                Yii::$app->session->setFlash('doimatkhau-error', "Mật khẩu không đúng!");
            } else {
                if ($verify_pw != $new_pw) {
                    Yii::$app->session->setFlash('doimatkhau-error', "Mật khẩu xác nhận không đúng!");
                } else {
                    $model->mat_khau = md5($new_pw);
                    if($model->save(false)){
                        Yii::$app->session->setFlash('doimatkhau-success', "Đổi mật khẩu thành công");
                    }
                    else{
                        Yii::$app->session->setFlash('doimatkhau-error', "Đổi mật khẩu thất bại");
                    }
                }
            }
            return $this->refresh();
        }
        else {
            return $this->render('doimatkhau', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->session->has('user')) {
            $user = \Yii::$app->session->get('user');
        }
        $kh_id = $user['kh_id'];
        $model = Khachhang::findOne($kh_id);
        $result_dclh = Diachilayhang::find()->where(['kh_id' => $kh_id])->all();
        $model_httt = Hinhthucthanhtoan::find()->where(['kh_id' => $kh_id])->one();

        //Xử lý địa chỉ lấy hàng
        $arr_model_dclh = [];
        foreach($result_dclh as $item)
        {
            $arr_model_dclh[] = [
                'ten_goi_nho' => $item['ten_goi_nho'],
                'ten_nguoi_ban_giao_hang' => $item['ten_nguoi_ban_giao_hang'],
                'so_dien_thoai' => $item['so_dien_thoai'],
                'dia_chi_text' => $item['dia_chi_text'],
                'dp_id' => $item['dp_id']
            ];
        }
        $model_dclh = new Diachilayhang();
        $model_dclh->arr_dclh = $arr_model_dclh;
        
        //Xử lý hình thức thanh toán
        ////Thanh toán bằng tiền mặt
        if($model_httt->hinh_thuc_thanh_toan == 'Tiền mặt')
        {
            $model_httt->arr_ttck = [];
        }
        ////Thanh toán chuyển khoản
        else if($model_httt->hinh_thuc_thanh_toan == 'Chuyển khoản')
        {
            $model_httt->arr_ttck = json_decode($model_httt->thong_tin_ngan_hang, TRUE);
        }
        
        ////Xử lý thời gian thanh toán
        $arr_thoi_gian_thanh_toan = json_decode($model_httt->thoi_gian_thanh_toan, true);
        $model_httt->json_thoi_gian_thanh_toan = $arr_thoi_gian_thanh_toan['type'];
        if($arr_thoi_gian_thanh_toan['time'] > -1)
        {
            $model_httt->thanh_toan_theo_tuan = $arr_thoi_gian_thanh_toan['time'];
        }
        
        $model->gkh_id = json_decode($model->gkh_id, true);
        
        if ($model->load(Yii::$app->request->post())) {
            $dataPost = \Yii::$app->request->post();
            $model->gkh_id = json_encode($dataPost[$model->formName()]['gkh_id'], JSON_UNESCAPED_UNICODE);
//            echo '<pre>';
//            print_r($dataPost);
//            echo '</pre>';
//            echo 'Hình thức thanh toán trước đây';
//            echo $model_httt->hinh_thuc_thanh_toan;
//            exit();
            //Xử lý model khách hàng
            if($model->save(false)) {
                $kh_id = $model->id;
                //Tìm id bắt đầu để reset auto increment
                $id_reset_ai = Diachilayhang::find()->where(['kh_id' => $kh_id])->one()['dclh_id'];
                
                //Xử lý địa chỉ lấy hàng
                $dataPost_dclh = \Yii::$app->request->post()[$model_dclh->formName()];
                ////Xóa toàn bộ địa chỉ lấy hàng cũ
                if(Diachilayhang::deleteAll(['kh_id' => $kh_id]))
                {
                    $sql = 'ALTER TABLE dia_chi_lay_hang AUTO_INCREMENT = '.$id_reset_ai;
                    \Yii::$app->db->createCommand($sql)->execute();
                    foreach($dataPost_dclh['arr_dclh'] as $dclh)
                    {
                        $model_dclh = new Diachilayhang();
                        $model_dclh->ten_goi_nho = $dclh['ten_goi_nho'];
                        $model_dclh->ten_nguoi_ban_giao_hang = $dclh['ten_nguoi_ban_giao_hang'];
                        $model_dclh->so_dien_thoai = $dclh['so_dien_thoai'];
                        $model_dclh->dia_chi_text = $dclh['dia_chi_text'];
                        $model_dclh->dp_id = $dclh['dp_id'];
                        $model_dclh->kh_id = $kh_id;
                        $model_dclh->save();
                    }
                }
                
                //Xử lý hình thức thanh toán
                $dataPost_httt = \Yii::$app->request->post()[$model_httt->formName()];
                ////Xử lý hình thức thanh toán Tiền mặt và Chuyển khoản
                $prev_httt = $model_httt->hinh_thuc_thanh_toan;
                $current_httt = $dataPost_httt['hinh_thuc_thanh_toan'];
                //TH1 : pre = "Tiền mặt" current = "Tiền mặt" -> Cập nhật lại ten_nguoi_nhan, dia_chi, so_dien_thoai
                if($prev_httt == 'Tiền mặt' && $current_httt == "Tiền mặt")
                {
                    $model_httt->ten_nguoi_nhan = $dataPost_httt['ten_nguoi_nhan'];
                    $model_httt->dia_chi = $dataPost_httt['dia_chi'];
                    $model_httt->so_dien_thoai = $dataPost_httt['so_dien_thoai'];
                }
                //TH2 : pre = "Tiền mặt" current = "Chuyển khoản" -> ten_nguoi_nhan, dia_chi, so_dien_thoai = NULL, cập nhật lại thong_tin_ngan_hang
                else if($prev_httt == 'Tiền mặt' && $current_httt == "Chuyển khoản")
                {
                    $model_httt->ten_nguoi_nhan = NULL;
                    $model_httt->dia_chi = NULL;
                    $model_httt->so_dien_thoai = NULL;
                    $model_httt->thong_tin_ngan_hang = json_encode($dataPost_httt['arr_ttck'], JSON_UNESCAPED_UNICODE);
                }
                //TH3 : pre = "Chuyển khoản" current = "Tiền mặt" -> thong_tin_ngan_hang = NULL, cập nhật lại ten_nguoi_nhan, dia_chi, so_dien_thoai
                else if($prev_httt == 'Chuyển khoản' && $current_httt == "Tiền mặt")
                {
                    $model_httt->ten_nguoi_nhan = $dataPost_httt['ten_nguoi_nhan'];
                    $model_httt->dia_chi = $dataPost_httt['dia_chi'];
                    $model_httt->so_dien_thoai = $dataPost_httt['so_dien_thoai'];
                    $model_httt->thong_tin_ngan_hang = NULL;
                }
                //TH4 : pre = "Chuyển khoản" current = "Chuyển khoản" -> cập nhật lại thong_tin_ngan_hang
                if($prev_httt == 'Chuyển khoản' && $current_httt == "Chuyển khoản")
                {
                    $model_httt->thong_tin_ngan_hang = json_encode($dataPost_httt['arr_ttck'], JSON_UNESCAPED_UNICODE);
                }
                ////Xử lý thời gian thanh toán
                $arr_tgtt = [];
                if($dataPost_httt['json_thoi_gian_thanh_toan'] == 'Mỗi tuần 1 lần')
                {
                    $arr_tgtt['type'] = $dataPost_httt['json_thoi_gian_thanh_toan'];
                    $arr_tgtt['time'] = $dataPost_httt['thanh_toan_theo_tuan'];
                }  else {
                    $arr_tgtt['type'] = $dataPost_httt['json_thoi_gian_thanh_toan'];
                    $arr_tgtt['time'] = -1;
                }
                $model_httt->thoi_gian_thanh_toan = json_encode($arr_tgtt, JSON_UNESCAPED_UNICODE);
                $model_httt->hinh_thuc_thanh_toan = $dataPost_httt['hinh_thuc_thanh_toan'];
                $model_httt->save();
                
                $message = "Cập nhật thông tin khách hàng thành công";
                Yii::$app->session->setFlash('edit-user-profile-success', $message);
            }
            else{
                $message = "Cập nhật thông tin khách hàng không thành công. Vui lòng kiểm tra lại thông tin";
                Yii::$app->session->setFlash('edit-user-profile-error', $message);
            }
            return $this->refresh();
        }
        else {
            return $this->render('edit', [
                'model' => $model,
                'model_dclh' => $model_dclh,
                'model_httt' => $model_httt
            ]);
        }
    }
    // Thanh toán với KH
    public function actionThanhtoan() {
        // Thanh toán với từng khách hàng
        if (\Yii::$app->request->post()) {
            $dataPost = \Yii::$app->request->post();
            $don_hang_da_xuat_hoa_don = [];
            $kh_id = $dataPost['kh_id'];
            $chuyen_tra_khach = $dataPost['chuyen_tra_khach'];
            $time = time();
            $arr_don_hang = json_decode($dataPost['donhang'], true);
            $model_hd = Hoadon::find()->orderBy('id DESC')->one();
            if (count($model_hd) === 0) {
                $prefix = 1;
            } else {
                $prefix = $model_hd['id'] + 1;
            }
            // Ma hoa don HD-216-00001
            $ma_hoa_don = 'HD-'.$kh_id.'-'.$prefix;
            
            // Lưu vào bảng hoá đơn
            $model_hoa_don = new Hoadon();
            $model_hoa_don->ma_hoa_don = $ma_hoa_don;
            $model_hoa_don->kh_id = $kh_id;
            $model_hoa_don->chuyen_tra_khach = $chuyen_tra_khach;
            $model_hoa_don->trang_thai = 'Chờ xử lý';
            $model_hoa_don->time = $time;
            if ($model_hoa_don->save(false)) {
                // Lưu vào bảng hoá đơn chi tiết
                foreach($arr_don_hang as $key => $item) {
                    $don_hang_da_xuat_hoa_don[$key] = $item['dh_id'];
                    $ma_don_hang = $item['ma_don_hang'];
                    $ma_hoa_don = $ma_hoa_don;
                    $dia_chi_nguoi_nhan = json_decode($item['nguoi_nhan'], true)['dia_chi_giao_hang'];
                    $gdv_id = $item['gdv_id'];
                    $hinh_thuc_thanh_toan = $item['hinh_thuc_thanh_toan'];
                    $tong_tien = $item['tong_tien'];
                    $tien_thu_ho = $item['tien_thu_ho'];
                    
                    $model_hoa_don_chi_tiet = new Hoadonchitiet();
                    $model_hoa_don_chi_tiet->ma_hoa_don = $ma_hoa_don;
                    $model_hoa_don_chi_tiet->ma_don_hang = $ma_don_hang;
                    $model_hoa_don_chi_tiet->dia_chi_nguoi_nhan = $dia_chi_nguoi_nhan;
                    $model_hoa_don_chi_tiet->gdv_id = $gdv_id;
                    $model_hoa_don_chi_tiet->hinh_thuc_thanh_toan = $hinh_thuc_thanh_toan;
                    $model_hoa_don_chi_tiet->tong_tien = $tong_tien;
                    $model_hoa_don_chi_tiet->tien_thu_ho = $tien_thu_ho;
                    $model_hoa_don_chi_tiet->time = $item['time'];
    
                    if ($model_hoa_don_chi_tiet->save(false)) {
                        
                    } else {
                        
                    }
                }

                // Lưu list các đơn hàng đã được xuất hoá đơn
                $model_kh = Khachhang::findOne($kh_id);
                if (!empty($model_kh->don_hang_da_xuat_hoa_don)) {
                    $new_arr = json_decode($model_kh->don_hang_da_xuat_hoa_don, true);
                } else {
                    $new_arr = [];
                }
                foreach($don_hang_da_xuat_hoa_don as $key => $value) {
                    array_push($new_arr, $value);
                }
                $json_don_hang_da_xuat_hoa_don = json_encode($new_arr, JSON_UNESCAPED_UNICODE);
                $model_kh->don_hang_da_xuat_hoa_don = $json_don_hang_da_xuat_hoa_don;
                if ($model_kh->save(false)) {
                    return $this->redirect(['hoadon']);
                }
            } else {

            }
        } else {
            $model_kh = Khachhang::find()->with(['donhang', 'hinhthucthanhtoan'])->asArray()->all();
            $number_model_kh = count($model_kh);
            for ($k = 0; $k < $number_model_kh; $k++) {
                $number_don_hang = count($model_kh[$k]['donhang']);
                $don_hang_da_xuat_hoa_don = $model_kh[$k]['don_hang_da_xuat_hoa_don']; // Chuỗi json ['1','2','3']
                if (!empty($don_hang_da_xuat_hoa_don)) {
                    $arr_unset = [];
                    $arr_don_hang_da_xuat_hoa_don = json_decode($don_hang_da_xuat_hoa_don, true);
                    for ($i = 0; $i < $number_don_hang; $i++) {
                        if ($model_kh[$k]['donhang'][$i]['trang_thai'] != 'Đã giao') {
                            // unset($model_kh[$k]['donhang'][$i]);
                            array_push($arr_unset, $i);
                            continue;
                        }
                        $dh_id = $model_kh[$k]['donhang'][$i]['dh_id'];
                        for ($j = 0; $j < count($arr_don_hang_da_xuat_hoa_don); $j++) {
                            if ($arr_don_hang_da_xuat_hoa_don[$j] == $dh_id) {
                                // Đã xuất hoá đơn -> unset
                                // unset($model_kh[$k]['donhang'][$i]);
                                array_push($arr_unset, $i);
                            }
                        }    
                    }
                    foreach($arr_unset as $unsetIndex) {
                        unset($model_kh[$k]['donhang'][$unsetIndex]);
                     }
                } else {
                    $arr_unset = [];
                    for ($i = 0; $i < count($model_kh[$k]['donhang']); $i++) {
                        if ($model_kh[$k]['donhang'][$i]['trang_thai'] != 'Đã giao') {
                            // unset($model_kh[$k]['donhang'][$i]);
                            array_push($arr_unset, $i);
                        }
                    }
                    foreach($arr_unset as $unsetIndex) {
                        unset($model_kh[$k]['donhang'][$unsetIndex]);
                     }
                    continue;
                }
            }
            return $this->render('thanhtoan', [
                'models' => $model_kh
            ]);
        }
    }
    // Thanh toán với KH đến kỳ
    public function actionThanhtoandenky() {
        // Thanh toán với từng khách hàng
        if (\Yii::$app->request->post()) {
            $dataPost = \Yii::$app->request->post();
            $don_hang_da_xuat_hoa_don = [];
            $kh_id = $dataPost['kh_id'];
            $chuyen_tra_khach = $dataPost['chuyen_tra_khach'];
            $time = time();
            $arr_don_hang = json_decode($dataPost['donhang'], true);
            $model_hd = Hoadon::find()->orderBy('id DESC')->one();
            if (count($model_hd) === 0) {
                $prefix = 1;
            } else {
                $prefix = $model_hd['id'] + 1;
            }
            // Ma hoa don HD-216-00001
            $ma_hoa_don = 'HD-'.$kh_id.'-'.$prefix;
            
            // Lưu vào bảng hoá đơn
            $model_hoa_don = new Hoadon();
            $model_hoa_don->ma_hoa_don = $ma_hoa_don;
            $model_hoa_don->kh_id = $kh_id;
            $model_hoa_don->chuyen_tra_khach = $chuyen_tra_khach;
            $model_hoa_don->trang_thai = 'Chờ xử lý';
            $model_hoa_don->time = $time;
            if ($model_hoa_don->save(false)) {
                // Lưu vào bảng hoá đơn chi tiết
                foreach($arr_don_hang as $key => $item) {
                    $don_hang_da_xuat_hoa_don[$key] = $item['dh_id'];
                    $ma_don_hang = $item['ma_don_hang'];
                    $ma_hoa_don = $ma_hoa_don;
                    $dia_chi_nguoi_nhan = json_decode($item['nguoi_nhan'], true)['dia_chi_giao_hang'];
                    $gdv_id = $item['gdv_id'];
                    $hinh_thuc_thanh_toan = $item['hinh_thuc_thanh_toan'];
                    $tong_tien = $item['tong_tien'];
                    $tien_thu_ho = $item['tien_thu_ho'];
                    
                    $model_hoa_don_chi_tiet = new Hoadonchitiet();
                    $model_hoa_don_chi_tiet->ma_hoa_don = $ma_hoa_don;
                    $model_hoa_don_chi_tiet->ma_don_hang = $ma_don_hang;
                    $model_hoa_don_chi_tiet->dia_chi_nguoi_nhan = $dia_chi_nguoi_nhan;
                    $model_hoa_don_chi_tiet->gdv_id = $gdv_id;
                    $model_hoa_don_chi_tiet->hinh_thuc_thanh_toan = $hinh_thuc_thanh_toan;
                    $model_hoa_don_chi_tiet->tong_tien = $tong_tien;
                    $model_hoa_don_chi_tiet->tien_thu_ho = $tien_thu_ho;
                    $model_hoa_don_chi_tiet->time = $item['time'];
    
                    if ($model_hoa_don_chi_tiet->save(false)) {
                        
                    } else {
                        
                    }
                }

                // Lưu list các đơn hàng đã được xuất hoá đơn
                $model_kh = Khachhang::findOne($kh_id);
                if (!empty($model_kh->don_hang_da_xuat_hoa_don)) {
                    $new_arr = json_decode($model_kh->don_hang_da_xuat_hoa_don, true);
                } else {
                    $new_arr = [];
                }
                foreach($don_hang_da_xuat_hoa_don as $key => $value) {
                    array_push($new_arr, $value);
                }
                $json_don_hang_da_xuat_hoa_don = json_encode($new_arr, JSON_UNESCAPED_UNICODE);
                $model_kh->don_hang_da_xuat_hoa_don = $json_don_hang_da_xuat_hoa_don;
                if ($model_kh->save(false)) {
                    return $this->redirect(['hoadon']);
                }
            } else {

            }
        } else {
            $model_kh = Khachhang::find()->with(['donhang', 'hinhthucthanhtoan'])->asArray()->all();
            // Lọc ra các hoá đơn chưa thanh toán và trạng thái là đã giao
            $number_model_kh = count($model_kh);
                for ($k = 0; $k < $number_model_kh; $k++) {
                    $number_don_hang = count($model_kh[$k]['donhang']);
                    $don_hang_da_xuat_hoa_don = $model_kh[$k]['don_hang_da_xuat_hoa_don']; // Chuỗi json ['1','2','3']
                    if (!empty($don_hang_da_xuat_hoa_don)) {
                        $arr_unset = [];
                        $arr_don_hang_da_xuat_hoa_don = json_decode($don_hang_da_xuat_hoa_don, true);
                        for ($i = 0; $i < $number_don_hang; $i++) {
                            if ($model_kh[$k]['donhang'][$i]['trang_thai'] != 'Đã giao') {
                                // unset($model_kh[$k]['donhang'][$i]);
                                array_push($arr_unset, $i);
                                continue;
                            }
                            $dh_id = $model_kh[$k]['donhang'][$i]['dh_id'];
                            for ($j = 0; $j < count($arr_don_hang_da_xuat_hoa_don); $j++) {
                                if ($arr_don_hang_da_xuat_hoa_don[$j] == $dh_id) {
                                    // Đã xuất hoá đơn -> unset
                                    // unset($model_kh[$k]['donhang'][$i]);
                                    array_push($arr_unset, $i);
                                }
                            }    
                        }
                        foreach($arr_unset as $unsetIndex) {
                            unset($model_kh[$k]['donhang'][$unsetIndex]);
                         }
                    } else {
                        $arr_unset = [];
                        for ($i = 0; $i < count($model_kh[$k]['donhang']); $i++) {
                            if ($model_kh[$k]['donhang'][$i]['trang_thai'] != 'Đã giao') {
                                echo 'Khác đã giao: '.$i;
                                array_push($arr_unset, $i);
                            }
                        }
                        foreach($arr_unset as $unsetIndex) {
                           unset($model_kh[$k]['donhang'][$unsetIndex]);
                        }
                        continue;
                    }
                }
            // Lọc ra các hoá đơn mà đến kỳ phải thanh toán
            foreach($model_kh as $k => $model) {
                $arr_tgtt = json_decode($model['hinhthucthanhtoan']['thoi_gian_thanh_toan'], true);
                $type_tt = $arr_tgtt['type'];
                $time_tt = $arr_tgtt['time'];
                $now_day = date('d', time());
                $now_day_of_week = date('N', time()) + 1;
                $model_dh = $model['donhang'];
                foreach($model_dh as $key => $dh) {
                    $dh_day = date('d', $dh['time']);
                    $dh_day_of_week = date('N', $dh['time']) + 1;
                    switch ($type_tt) {
                        case 'Thanh toán cuối ngày':
                            if ($now_day != $dh_day) {
                                unset($model_kh[$k]['donhang'][$key]);
                            }       
                        break;
                        case 'Thanh toán vào hôm sau':
                            if ($now_day != $dh_day + 1) {
                                unset($model_kh[$k]['donhang'][$key]);
                            }
                        break;
                        case 'Thanh toán vào thứ 2, 4, 6':
                            if (($now_day_of_week != 2) && ($now_day_of_week != 4) && ($now_day_of_week != 6)) {
                                unset($model_kh[$k]['donhang'][$key]);
                            }
                        break;
                        case 'Mỗi tuần 1 lần':
                            if ($time_tt != $now_day_of_week) {
                                unset($model_kh[$k]['donhang'][$key]);
                            }
                        break;
                        case 'Theo yêu cầu':
                        break;
                    }
                }
            }
            return $this->render('thanhtoandenky', [
                'models' => $model_kh
            ]);
        }
    }

    public function actionHoadon() {
        if (\Yii::$app->session->has('user')) {
            $user = \Yii::$app->session->get('user');
        }
        $kh_id = $user['kh_id'];
        $model = Hoadon::find()->with('hoadonchitiet')->where(['kh_id' => $kh_id])->asArray()->all();
        $model_choxuly = [];    
        $model_dangtt = [];
        $model_datt = [];
        foreach($model as $key => $item) {
            if ($item['trang_thai'] == 'Đang thanh toán') {
                $model_dangtt[$key] = $model[$key];
            } else if ($item['trang_thai'] == 'Đã thanh toán') {
                $model_datt[$key] = $model[$key];
            } else if ($item['trang_thai'] == 'Chờ xử lý') {
                $model_choxuly[$key] = $model[$key];
            }
        }
        return $this->render('hoadon', [
            'models' => $model,
            'models_dangtt' => $model_dangtt,
            'models_datt' => $model_datt,
            'models_choxuly' => $model_choxuly
        ]);
    }

    public function actionQuanlytien() {
        // Hiển thị số tiền còn phải thanh toán được tính dựa trên trạng thái của các đơn hàng của khách
        // Đơn hàng nằm trong hoá đơn mà có trạng thái:
        // 1 - Chờ xử lý -> chưa thanh toán -> Tính toán
        // 2 - Đang thanh toán -> đang thanh toán -> Tính toán
        // 3 - Đã thanh toán -> đã thanh toán -> không tính toán chỉ hiển thị
        
        // Lấy ra mảng các đơn hàng của khách hàng đó
        if (\Yii::$app->session->has('user')) {
            $user = \Yii::$app->session->get('user');
            $kh_id = $user['kh_id'];
        }
        $model_kh = Khachhang::find()->where(['kh_id' => $kh_id])->one();
        $model_dh = Donhang::find()
        ->where(['kh_id' => $kh_id])
        ->andWhere(['trang_thai' => 'Đã giao'])
        ->asArray()
        ->all();

        // Lấy ra tất cả các hoá đơn
        $model_hd = Hoadon::find()
        ->with('hoadonchitiet')
        ->where(['kh_id' => $kh_id])
        ->asArray()
        ->all();
        $model_choxuly = [];
        $model_dangtt = [];
        $model_datt = [];
        foreach($model_hd as $key => $item) {
            if ($item['trang_thai'] == 'Đang thanh toán') {
                array_push($model_dangtt, $model_hd[$key]);
            } else if ($item['trang_thai'] == 'Đã thanh toán') {
                array_push($model_datt, $model_hd[$key]);
            } else if ($item['trang_thai'] == 'Chờ xử lý') {
                array_push($model_choxuly, $model_hd[$key]);
            }
        }

        $model_dh_chuatt = [];
        $model_dh_dangtt = [];
        $model_dh_datt = [];

        $isContainChoXuLy = false;
        $isContainDangThanhToan = false;
        $isContainDaThanhToan = false;
        for ($i = 0; $i < count($model_dh); $i++) {
            $ma_dh = $model_dh[$i]['ma_don_hang'];
            // Đang thanh toán
            for ($j = 0 ; $j < count($model_dangtt); $j++) {
                for ($jj = 0; $jj < count($model_dangtt[$j]['hoadonchitiet']); $jj++) {
                    $ma_dh_compare = $model_dangtt[$j]['hoadonchitiet'][$jj]['ma_don_hang'];
                    if ($ma_dh == $ma_dh_compare) {
                        $trang_thai = $model_dangtt[$j]['trang_thai'];
                        $thoi_gian_thanh_toan = $model_dangtt[$j]['thoi_gian_thanh_toan'];

                        // Push vao mang dang tt
                        switch($model_dh[$i]['hinh_thuc_thanh_toan']) {
                            case 'Người gửi thanh toán':
                            case 'Người nhận thanh toán':
                            case 'Thanh toán sau':
                            $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                            $tien_nhan_lai = $tien_thu_ho;  
                            break;
                            case 'Thanh toán sau COD':
                                $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                                $tong_tien = $model_dh[$i]['tong_tien'] > 0 ? $model_dh[$i]['tong_tien'] : 0;
                                $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                $tien_nhan_lai = $tien_thu_ho_phai_tra;
                            break;
                        }
                        
                        $new_item = [
                            'ma_don_hang' => $model_dh[$i]['ma_don_hang'],
                            'thoi_gian_tao_don' => $model_dh[$i]['time'],
                            'dia_chi_nguoi_nhan' => JSON_decode($model_dh[$i]['nguoi_nhan'], true)['dia_chi_giao_hang'],
                            'goi_cuoc' => Goidichvu::find()->where(['gdv_id' => $model_dh[$i]['gdv_id']])->one()['ten_goi_dich_vu'],
                            'khu_vuc' => $model_dh[$i]['pham_vi_don_hang'],
                            'phuong_thuc_tra_ship' => $model_dh[$i]['hinh_thuc_thanh_toan'],
                            'tien_ship' => $model_dh[$i]['tong_tien'],
                            'tien_thu_ho' => $model_dh[$i]['tien_thu_ho'],
                            'tien_nhan_lai' => $tien_nhan_lai,
                            'trang_thai' => $trang_thai,
                            // 'thoi_gian_thanh_toan' => $thoi_gian_thanh_toan
                        ];
                        array_push($model_dh_dangtt, $new_item);
                        $isContainDangThanhToan = true;
                        break;
                    }
                }
            }
            if ($isContainDangThanhToan) {
                $isContainDangThanhToan = false;
                continue;
            }
            // Đã thanh toán
            for ($k = 0 ; $k < count($model_datt); $k++) {
                for ($kk = 0; $kk < count($model_datt[$k]['hoadonchitiet']); $kk++) {
                    $ma_dh_compare = $model_datt[$k]['hoadonchitiet'][$kk]['ma_don_hang'];
                    if ($ma_dh == $ma_dh_compare) {
                        $trang_thai = $model_datt[$k]['trang_thai'];
                        $thoi_gian_thanh_toan = $model_datt[$k]['thoi_gian_thanh_toan'];

                        // Push vao mang dang tt
                        switch($model_dh[$i]['hinh_thuc_thanh_toan']) {
                            case 'Người gửi thanh toán':
                            case 'Người nhận thanh toán':
                            case 'Thanh toán sau':
                            $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                            $tien_nhan_lai = $tien_thu_ho;  
                            break;
                            case 'Thanh toán sau COD':
                                $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                                $tong_tien = $model_dh[$i]['tong_tien'] > 0 ? $model_dh[$i]['tong_tien'] : 0;
                                $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                $tien_nhan_lai = $tien_thu_ho_phai_tra;
                            break;
                        }
                        $new_item = [
                            'ma_don_hang' => $model_dh[$i]['ma_don_hang'],
                            'thoi_gian_tao_don' => $model_dh[$i]['time'],
                            'dia_chi_nguoi_nhan' => JSON_decode($model_dh[$i]['nguoi_nhan'], true)['dia_chi_giao_hang'],
                            'goi_cuoc' => Goidichvu::find()->where(['gdv_id' => $model_dh[$i]['gdv_id']])->one()['ten_goi_dich_vu'],
                            'khu_vuc' => $model_dh[$i]['pham_vi_don_hang'],
                            'phuong_thuc_tra_ship' => $model_dh[$i]['hinh_thuc_thanh_toan'],
                            'tien_ship' => $model_dh[$i]['tong_tien'],
                            'tien_thu_ho' => $model_dh[$i]['tien_thu_ho'],
                            'tien_nhan_lai' => $tien_nhan_lai,
                            'trang_thai' => $trang_thai,
                            // 'thoi_gian_thanh_toan' => $thoi_gian_thanh_toan
                        ];
                        array_push($model_dh_datt, $new_item);
                        $isContainDaThanhToan = true;
                        break;
                    }
                }
            }
            if ($isContainDaThanhToan) {
                $isContainDaThanhToan = false;
                continue;
            }
            // Chờ xử lý -> chưa thanh toán
            for ($l = 0 ; $l < count($model_choxuly); $l++) {
                for ($ll = 0; $ll < count($model_choxuly[$l]['hoadonchitiet']); $ll++) {
                    $ma_dh_compare = $model_choxuly[$l]['hoadonchitiet'][$ll]['ma_don_hang'];
                    if ($ma_dh == $ma_dh_compare) {
                        $trang_thai = 'Chưa thanh toán';
                        $thoi_gian_thanh_toan = $model_choxuly[$l]['thoi_gian_thanh_toan'];

                        // Push vao mang dang tt
                        switch($model_dh[$i]['hinh_thuc_thanh_toan']) {
                            case 'Người gửi thanh toán':
                            case 'Người nhận thanh toán':
                            case 'Thanh toán sau':
                            $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                            $tien_nhan_lai = $tien_thu_ho;  
                            break;
                            case 'Thanh toán sau COD':
                                $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                                $tong_tien = $model_dh[$i]['tong_tien'] > 0 ? $model_dh[$i]['tong_tien'] : 0;
                                $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                $tien_nhan_lai = $tien_thu_ho_phai_tra;
                            break;
                        }
                        $new_item = [
                            'ma_don_hang' => $model_dh[$i]['ma_don_hang'],
                            'thoi_gian_tao_don' => $model_dh[$i]['time'],
                            'dia_chi_nguoi_nhan' => JSON_decode($model_dh[$i]['nguoi_nhan'], true)['dia_chi_giao_hang'],
                            'goi_cuoc' => Goidichvu::find()->where(['gdv_id' => $model_dh[$i]['gdv_id']])->one()['ten_goi_dich_vu'],
                            'khu_vuc' => $model_dh[$i]['pham_vi_don_hang'],
                            'phuong_thuc_tra_ship' => $model_dh[$i]['hinh_thuc_thanh_toan'],
                            'tien_ship' => $model_dh[$i]['tong_tien'],
                            'tien_thu_ho' => $model_dh[$i]['tien_thu_ho'],
                            'tien_nhan_lai' => $tien_nhan_lai,
                            'trang_thai' => $trang_thai,
                            // 'thoi_gian_thanh_toan' => $thoi_gian_thanh_toan
                        ];
                        array_push($model_dh_chuatt, $new_item);
                        $isContainChoXuLy = true;
                        break;
                    }
                }
            }
            if ($isContainChoXuLy) {
                $isContainChoXuLy = false;
                continue;
            }
            // Còn lại cho hết vào chưa thanh toán
            switch($model_dh[$i]['hinh_thuc_thanh_toan']) {
                case 'Người gửi thanh toán':
                case 'Người nhận thanh toán':
                case 'Thanh toán sau':
                $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                $tien_nhan_lai = $tien_thu_ho;  
                break;
                case 'Thanh toán sau COD':
                    $tien_thu_ho = $model_dh[$i]['tien_thu_ho'] > 0 ? $model_dh[$i]['tien_thu_ho'] : 0;
                    $tong_tien = $model_dh[$i]['tong_tien'] > 0 ? $model_dh[$i]['tong_tien'] : 0;
                    $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                    $tien_nhan_lai = $tien_thu_ho_phai_tra;
                break;
            }
            $new_item = [
                'ma_don_hang' => $model_dh[$i]['ma_don_hang'],
                'thoi_gian_tao_don' => $model_dh[$i]['time'],
                'dia_chi_nguoi_nhan' => JSON_decode($model_dh[$i]['nguoi_nhan'], true)['dia_chi_giao_hang'],
                'goi_cuoc' => Goidichvu::find()->where(['gdv_id' => $model_dh[$i]['gdv_id']])->one()['ten_goi_dich_vu'],
                'khu_vuc' => $model_dh[$i]['pham_vi_don_hang'],
                'phuong_thuc_tra_ship' => $model_dh[$i]['hinh_thuc_thanh_toan'],
                'tien_ship' => $model_dh[$i]['tong_tien'],
                'tien_thu_ho' => $model_dh[$i]['tien_thu_ho'],
                'tien_nhan_lai' => $tien_nhan_lai,
                'trang_thai' => 'Chưa thanh toán',
                // 'thoi_gian_thanh_toan' => $model_dh[$i]['thoi_gian_thanh_toan']
            ];
            array_push($model_dh_chuatt, $new_item);
        }

        $models = array_merge($model_dh_chuatt, $model_dh_dangtt, $model_dh_datt);
        $tong_tien_nhan_lai = 0;
        foreach($models as $model) {
            if ($model['trang_thai'] != 'Đã thanh toán') {
                $tong_tien_nhan_lai += $model['tien_nhan_lai'];
            }
        }
        return $this->render('quanlytien', [
            'models' => $models,
            'so_du' => $model_kh['sodu'],
            'so_no' => $model_kh['sono'],
            'tong_tien_nhan_lai' => $tong_tien_nhan_lai
        ]);
    }

    // Hàm thống kê theo khách hàng
    public function actionThongke() {
        if (\Yii::$app->session->has('user')) {
            $user = \Yii::$app->session->get('user');
        }
        $kh_id = $user['kh_id'];
        if (\Yii::$app->request->post()) {
            $dataPost = \Yii::$app->request->post();
            $fromDate = $dataPost['fromDate'];
            $toDate = $dataPost['toDate'];
            $fromTime = strtotime($fromDate);
            $toTime = strtotime($toDate);

            $model = $this->getBaoCao($kh_id, $fromTime, $toTime);
            return $this->render('thongke', [
                'model' => $model,
                'from' => date('d/m/Y', $fromTime),
                'to' => date('d/m/Y', $toTime),
            ]);
        }
        // default 7 ngay
        $fromTime = strtotime('-7 days');
        $toTime = time();
        $model = $this->getBaoCao($kh_id, $fromTime, $toTime);
        return $this->render('thongke', [
            'model' => $model,
            'from' => date('d/m/Y', $fromTime),
            'to' => date('d/m/Y', $toTime),
        ]);
    }

    public function getBaoCao($kh_id, $fromDate, $toDate) {
        $tong_so_don = 0;
        $tong_cuoc_van_chuyen = 0;
        $tong_tien_thu_ho = 0 ;
        $so_don_dang_duyet_cho_lay = 0;
        $so_don_huy_khong_lay_duoc = 0;
        $so_don_phat_that_bai = 0;
        $don_dang_giao = 0;
        $don_cho_giao = 0;
        $don_dang_lay_hang = 0;
        $don_da_giao = 0;
        $ti_le_hoan_hang = 0;
        $pie_chart_info = [];
        $model_dh = Donhang::find()
            ->where(['kh_id' => $kh_id])
            ->andWhere(['>=', 'time', $fromDate])
            ->andWhere(['<=', 'time', $toDate])
            ->asArray()
            ->all();
        // Tong so don
        $tong_so_don = count($model_dh);
        
        if ($tong_so_don > 0) {
            foreach($model_dh as $item) {
                $trang_thai = $item['trang_thai'];
                // Tinh tong_cuoc && tien_thu_ho
                if ($trang_thai != 'Huỷ đơn') {
                    $tong_cuoc_van_chuyen += $item['tong_tien'];
                    $tong_tien_thu_ho += $item['tien_thu_ho'];
                } else {
                    $so_don_huy_khong_lay_duoc++;
                }
    
                // Tinh so don da duyet cho lay
                if ($trang_thai == 'Đã duyệt, chờ lấy') {
                    $so_don_dang_duyet_cho_lay++;
                }

                // Tinh so don phat that bai
                if ($trang_thai == 'Đã hoàn' || $trang_thai == 'Đang hoàn' || $trang_thai == 'Chờ hoàn hàng' || $trang_thai == 'Chờ hoàn lại') {
                    $so_don_phat_that_bai++;
                }
    
                // Tinh so don dang giao
                if ($trang_thai == 'Đang giao' || $trang_thai == 'Chờ giao lại') {
                    $don_dang_giao++;
                }
    
                // Tinh so don cho giao
                if ($trang_thai == 'Đã lấy, chờ giao') {
                    $don_cho_giao++;
                }
    
                // Tinh so don dang lay hang
                if ($trang_thai == 'Đang lấy' || $trang_thai == 'Chờ lấy lại') {
                    $don_dang_lay_hang++;
                }
    
                // Tinh so don da giao
                if ($trang_thai == 'Đã giao') {
                    $don_da_giao++;
                }
            }
    
            // Tinh ti le hoan hang
            $ti_le_hoan_hang = number_format((float)($so_don_phat_that_bai / $tong_so_don * 100), 2, '.', '');

            // Pie chart
            $list_label = [];
            $list_data = [];
            $list_color = [];
            if ($so_don_dang_duyet_cho_lay > 0) {
                array_push($list_label, 'Đã duyệt chờ lấy');
                array_push($list_data, number_format((float)($so_don_dang_duyet_cho_lay / $tong_so_don * 100), 2, '.', ''));
                array_push($list_color, 'black');
            }
            if ($don_da_giao > 0) {
                array_push($list_label, 'Đã giao');
                array_push($list_data, number_format((float)($don_da_giao / $tong_so_don * 100), 2, '.', ''));
                array_push($list_color, '#2E9AFE');
            }
            if ($so_don_phat_that_bai > 0) {
                array_push($list_label, 'Không phát được/hoàn');
                array_push($list_data, number_format((float)($so_don_phat_that_bai / $tong_so_don * 100), 2, '.', ''));
                array_push($list_color, '#FA58D0');
            }
            if ($don_cho_giao > 0) {
                array_push($list_label, 'Chờ giao');
                array_push($list_data, number_format((float)($don_cho_giao / $tong_so_don * 100), 2, '.', ''));
                array_push($list_color, '#4B088A');
            }
            if ($don_dang_giao > 0) {
                array_push($list_label, 'Đang giao');
                array_push($list_data, number_format((float)($don_dang_giao / $tong_so_don * 100), 2, '.', ''));
                array_push($list_color, '#0489B1');
            }
            if ($so_don_huy_khong_lay_duoc > 0) {
                array_push($list_label, 'Huỷ/không lấy được');
                array_push($list_data, number_format((float)($so_don_huy_khong_lay_duoc / $tong_so_don * 100), 2, '.', ''));
                array_push($list_color, 'red');
            }
            $pie_chart_info = [
                'labels' => $list_label,
                'data' => $list_data,
                'color' => $list_color
            ];
        }
        
        return [
            'tong_so_don' => $tong_so_don,
            'tong_cuoc_van_chuyen' => $tong_cuoc_van_chuyen,
            'tong_tien_thu_ho' => $tong_tien_thu_ho,
            'so_don_huy_khong_lay_duoc' => $so_don_huy_khong_lay_duoc,
            'so_don_phat_that_bai' => $so_don_phat_that_bai,
            'ti_le_hoan_hang' => $ti_le_hoan_hang,
            'don_dang_giao' => $don_dang_giao,
            'don_cho_giao' => $don_cho_giao,
            'don_dang_lay_hang' => $don_dang_lay_hang,
            'don_da_giao' => $don_da_giao,
            'pie_chart' => $pie_chart_info
        ];
    }

    public function actionLaylaimatkhau() {
        $email = Yii::$app->getRequest()->getQueryParam('e');
        $baseUrl = \yii\helpers\Url::base(true);
        if (Yii::$app->request->post()) {
            $dataPost = Yii::$app->request->post();
            $new_password = md5($dataPost['new_password']);
            $forgot_password_code = $dataPost['forgot_password_code'];
            $email = $dataPost['email'];

            $model_kh = Khachhang::find()
            ->where(['email' => $email])
            ->one();
            $code = $model_kh['forgot_password_code'];
            if ($code != $forgot_password_code) {
                // Sai code -> thông báo
                Yii::$app->session->setFlash('laylaimatkhau-error', "Mã lấy lại mật khẩu không chính xác");
                return $this->refresh();
            } else {
                $model_kh->mat_khau = $new_password;
                if ($model_kh->save(false)) {
                    return $this->redirect($baseUrl);
                } else {
                    Yii::$app->session->setFlash('laylaimatkhau-error', "Có lỗi trong quá trình lấy lại mật khẩu");
                    return $this->refresh();
                }
            }
        }
        return $this->renderPartial('laylaimatkhau', ['email' => $email]);
    }
}