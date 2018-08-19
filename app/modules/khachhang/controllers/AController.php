<?php
namespace app\modules\khachhang\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\SortableDateController;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii\components\Controller;
use app\modules\khachhang\models\Khachhang;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\StatusController;
use yii\easyii\models\Diachilayhang;
use yii\easyii\models\Hinhthucthanhtoan;
use yii\validators\RequiredValidator;
use \yii\easyii\models\Hoadon;
use \yii\easyii\models\Hoadonchitiet;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableDateController::className(),
                'model' => Khachhang::className(),
            ],
            [
                'class' => StatusController::className(),
                'model' => Khachhang::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Khachhang::find(),
            'sort'=> ['defaultOrder' => ['time' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 0
            ]
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Khachhang();
        $model_dclh = new Diachilayhang();
        $model_httt = new Hinhthucthanhtoan();
        $model->time = time();
        $arr_tinh_nang_an = [];
        $arr_tinh_nang_an['tna1'] = [
            'key' => 'tna1',
            'value' => 0,
            'content' => 'Cho phép ứng tiền'
        ];
        $arr_tinh_nang_an['tna2'] = [
            'key' => 'tna2',
            'value' => 0,
            'content' => 'Cho phép tạo đơn hỏa tốc'
        ];
        $arr_tinh_nang_an['tna3'] = [
            'key' => 'tna3',
            'value' => 0,
            'content' => 'Người gửi hỗ trợ cước cho người nhận'
        ];
        $arr_tinh_nang_an['tna4'] = [
            'key' => 'tna4',
            'value' => 0,
            'content' => 'Thanh toán cuối ngày'
        ];
        $arr_tinh_nang_an['tna5'] = [
            'key' => 'tna5',
            'value' => 0,
            'content' => 'Thanh toán sau'
        ];
        $model->arr_tinh_nang_an = $arr_tinh_nang_an;

        if ($model->load(Yii::$app->request->post())) {
            $dataPost = \Yii::$app->request->post();
            //Xử lý gói khách hàng
            $model->gkh_id = json_encode($dataPost[$model->formName()]['gkh_id'], JSON_UNESCAPED_UNICODE);
            //Xử lý model khách hàng
            ////Xử lý tính năng ẩn
            if(isset($dataPost['tna']))
            {
                foreach($dataPost['tna'] as $key => $value)
                {
                    $arr_tinh_nang_an[$key]['value'] = $value;
                }
                $model->arr_tinh_nang_an = $arr_tinh_nang_an;
            }
            $model->tinh_nang_an = json_encode($model->arr_tinh_nang_an, JSON_UNESCAPED_UNICODE);
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $kh_id = $model->id;
                    //Xử lý địa chỉ lấy hàng
                    $dataPost_dclh = \Yii::$app->request->post()[$model_dclh->formName()];
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
                    
                    //Xử lý hình thức thanh toán
                    $dataPost_httt = \Yii::$app->request->post()[$model_httt->formName()];
                    $model_httt = new Hinhthucthanhtoan();
                    ////Thanh toán bằng tiền mặt -> tài khoản ngân hàng = NULL hết
                    if($dataPost_httt['hinh_thuc_thanh_toan'] == 'Tiền mặt')
                    {
                        $model_httt->thong_tin_ngan_hang = NULL;
                        $model_httt->ten_nguoi_nhan = $dataPost_httt['ten_nguoi_nhan'];
                        $model_httt->dia_chi = $dataPost_httt['dia_chi'];
                        $model_httt->so_dien_thoai = $dataPost_httt['so_dien_thoai'];
                    }
                    ////Thanh toán bằng chuyển khoản -> thông tin tiền mặt = NULL hết
                    else if($dataPost_httt['hinh_thuc_thanh_toan'] == 'Chuyển khoản')
                    {
                        $model_httt->ten_nguoi_nhan = NULL;
                        $model_httt->dia_chi = NULL;
                        $model_httt->so_dien_thoai = NULL;
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
                    $model_httt->kh_id = $kh_id;
                    $model_httt->hinh_thuc_thanh_toan = $dataPost_httt['hinh_thuc_thanh_toan'];
                    $model_httt->save();
                    $this->flash('success', 'Tạo khách hàng mới thành công');
                    return $this->redirect(['/admin/'.$this->module->id]);
                }
                else{
                    $this->flash('error', "Tạo khách hàng mới không thành công. Vui lòng kiểm tra lại thông tin");
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'model_dclh' => $model_dclh,
                'model_httt' => $model_httt
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Khachhang::findOne($id);
        $model->mat_khau = '';
        $result_dclh = Diachilayhang::find()->where(['kh_id' => $id])->all();
        $model_httt = Hinhthucthanhtoan::find()->where(['kh_id' => $id])->one();

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
        
        //Xử lý tính năng ẩn
        if($model->tinh_nang_an) //json decode thành mảng
        {
            $model->arr_tinh_nang_an = json_decode($model->tinh_nang_an, true);
        }else //Chưa có tính năng ẩn khởi tạo giá trị ban đầu
        {
            $arr_tinh_nang_an = [];
            $arr_tinh_nang_an['tna1'] = [
                'key' => 'tna1',
                'value' => 0,
                'content' => 'Cho phép ứng tiền'
            ];
            $arr_tinh_nang_an['tna2'] = [
                'key' => 'tna2',
                'value' => 0,
                'content' => 'Cho phép tạo đơn hỏa tốc'
            ];
            $arr_tinh_nang_an['tna3'] = [
                'key' => 'tna3',
                'value' => 0,
                'content' => 'Người gửi hỗ trợ cước cho người nhận'
            ];
            $arr_tinh_nang_an['tna4'] = [
                'key' => 'tna4',
                'value' => 0,
                'content' => 'Thanh toán cuối ngày'
            ];
            $arr_tinh_nang_an['tna5'] = [
                'key' => 'tna5',
                'value' => 0,
                'content' => 'Thanh toán sau'
            ];
            $model->arr_tinh_nang_an = $arr_tinh_nang_an;
        }
        
        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        $model->gkh_id = json_decode($model->gkh_id, true);
        
        if ($model->load(Yii::$app->request->post())) {
            $dataPost = \Yii::$app->request->post();
            $model->gkh_id = json_encode($dataPost[$model->formName()]['gkh_id'], JSON_UNESCAPED_UNICODE);
            if(isset($dataPost['tna']))
            {
                foreach($dataPost['tna'] as $key => $value)
                {
                    $model->arr_tinh_nang_an[$key]['value'] = $value;
                }
                $model->tinh_nang_an = json_encode($model->arr_tinh_nang_an, JSON_UNESCAPED_UNICODE);
            }

            // Xử lý phần mật khẩu
            $mat_khau = $dataPost[$model->formName()]['mat_khau'];
            if (!empty($mat_khau)) {
                $model->mat_khau = md5($mat_khau);
            }
            
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
                
                $this->flash('success', "Cập nhật thông tin khách hàng thành công");
            }
            else{
                $this->flash('error', "Cập nhật thông tin khách hàng không thành công. Vui lòng kiểm tra lại thông tin");
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

    public function actionPhotos($id)
    {
        if(!($model = Khachhang::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if(($model = Khachhang::findOne($id))){
            if($model->delete())
            {
                //Xóa cả ở bảng dia_chi_lay_hang và hinh_thuc_thanh_toan
                Diachilayhang::deleteAll(['kh_id' => $id]);
                Hinhthucthanhtoan::deleteAll(['kh_id' => $id]);
            }
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse("Xóa khách hàng thành công");
    }

    public function actionClearImage($id)
    {
        $model = Khachhang::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        else{
            $model->image = '';
            if($model->update()){
                @unlink(Yii::getAlias('@webroot').$model->image);
                $this->flash('success', "Xỏa ảnh khách hàng thành công");
            } else {
                $this->flash('error', "Xóa ảnh khách hàng không thành công");
            }
        }
        return $this->back();
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
        $model_hd = Hoadon::find()->with('hoadonchitiet')->asArray()->all();
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
        return $this->render('hoadon', [
            'models' => $model_hd,
            'models_dangtt' => $model_dangtt,
            'models_datt' => $model_datt,
            'models_choxuly' => $model_choxuly
        ]);
    }
}