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
            'query' => Khachhang::find()->sortDate(),
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
//            echo '<pre>';
//            print_r($dataPost);
//            echo '</pre>';
//            echo 'Hình thức thanh toán trước đây';
//            echo $model_httt->hinh_thuc_thanh_toan;
//            exit();
            //Xử lý model khách hàng
            ////Xử lý tính năng ẩn
            if(isset($dataPost['tna']))
            {
                foreach($dataPost['tna'] as $key => $value)
                {
                    $model->arr_tinh_nang_an[$key]['value'] = $value;
                }
                $model->tinh_nang_an = json_encode($model->arr_tinh_nang_an, JSON_UNESCAPED_UNICODE);
            }
            
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
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
                    if($prev_httt == 'Tiền mặt' && $current_httt == "Tiền mặt")
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
    
    
}