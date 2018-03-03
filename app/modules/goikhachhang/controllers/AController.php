<?php
namespace app\modules\goikhachhang\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\modules\goikhachhang\models\Goikhachhang;
use app\modules\khuvuc\models\Khuvuc;

use yii\easyii\behaviors\StatusController;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => Goikhachhang::className()
            ]
        ];
    }
    
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Goikhachhang::find(),
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    public function actionCreate()
    {
        $model = new Goikhachhang;
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

        //Khởi tạo mảng khu vực
        $khuvuc = Khuvuc::find()->all();
        $model_kv = [];
        foreach($khuvuc as $item)
        {
            $model_kv['kv'.$item->kv_id] = [
                'id' => $item->kv_id,
                'content' => $item->ten_khu_vuc,
                'key' => 'kv'.$item->kv_id,
                'value' => 0
            ];
        }
        
        $model->dvpt = $model_dvpt;
        $model->kv = $model_kv;
        
        if ($model->load(Yii::$app->request->post())) {
            $dataPost = Yii::$app->request->post();
            //Xử lý gói dịch vụ
            $model->gdv_id = json_encode($dataPost[$model->formName()]['gdv_id'], JSON_UNESCAPED_UNICODE);
            //Xử lý dịch vụ phụ trội JSON
            if(isset($dataPost['dvpt']))
            {
                foreach($dataPost['dvpt'] as $key => $value)
                {
                    if($model_dvpt[$key])
                    {
                        $model_dvpt[$key]['value'] = 1;
                    }
                }
                $model->dich_vu_phu_troi = json_encode($model_dvpt, JSON_UNESCAPED_UNICODE);
            }else
            {
                $model->dich_vu_phu_troi = NULL;
            }
            
            //Xử lý khu vực JSON
            if(isset($dataPost['kv']))
            {
                foreach($dataPost['kv'] as $key => $value)
                {
                    if($model_kv[$key])
                    {
                        $model_kv[$key]['value'] = 1;
                    }
                }
                $model->khu_vuc = json_encode($model_kv, JSON_UNESCAPED_UNICODE);
            }else
            {
                $model->khu_vuc = NULL;
            }
            
            //Xử lý áp dụng gói KM theo ngày
            if($dataPost[$model->formName()]['thoi_gian_ap_dung'] == 'day')
            {
                $model->day_ngay_bat_dau = strtotime($dataPost['day_gkh_begin']);
                $model->day_ngay_ket_thuc = strtotime($dataPost['day_gkh_end']);
                $model->hour_gio_ap_dung = NULL;
                $model->hour_thoi_gian_ap_dung = NULL;
            }else if($dataPost[$model->formName()]['thoi_gian_ap_dung'] == 'hour')
            {
                $model->hour_thoi_gian_ap_dung = time();
            }
            
            //Xử lý áp dụng gói KM khi đăng ký mới
            if($dataPost[$model->formName()]['new_gkh'] == 1) //Có áp dụng -> set ngày bắt đầu và kết thúc
            {
                $model->new_ngay_bat_dau = strtotime($dataPost['new_gkh_begin']);
                $model->new_ngay_ket_thuc = strtotime($dataPost['new_gkh_end']);
            }else
            {
                $model->new_ngay_bat_dau = NULL;
                $model->new_ngay_ket_thuc = NULL;
            }
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', 'Tạo gói khách hàng thành công!');
                    return $this->redirect(['/admin/'.$this->module->id]);
                }
                else{
                    $this->flash('error', 'Tạo gói khách hàng thất bại! Hãy kiểm tra lại thông tin vừa khởi tạo!');
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Goikhachhang::findOne($id);
        //Xử lý Gói dịch vụ
        $model->gdv_id = json_decode($model->gdv_id, true);
        //Xử lý JSON dich_vu_phu_troi và khu_vuc;
        if($model->dich_vu_phu_troi)
        {
            $arr_dvpt = json_decode($model->dich_vu_phu_troi, true);
        }else
        {
            $arr_dvpt = [];
            $arr_dvpt['dvpt1'] = [
                'content' => 'Giao hàng mẫu, đổi hàng',
                'key' => 'dvpt1',
                'value' => 0
            ];
            $arr_dvpt['dvpt2'] = [
                'content' => 'Hẹn giờ giao, giao sau giờ hành chính',
                'key' => 'dvpt2',
                'value' => 0
            ];
            $arr_dvpt['dvpt3'] = [
                'content' => 'Giao bến xe, văn phòng xe',
                'key' => 'dvpt3',
                'value' => 0
            ];
            $arr_dvpt['dvpt4'] = [
                'content' => 'Hàng quá khổ',
                'key' => 'dvpt4',
                'value' => 0
            ];
        }
        
        if($model->khu_vuc)
        {
            $arr_kv = json_decode($model->khu_vuc, true);
        }else
        {
            $arr_kv = [];
            $khuvuc = Khuvuc::find()->all();
            foreach($khuvuc as $item)
            {
                $arr_kv['kv'.$item->kv_id] = [
                    'id' => $item->kv_id,
                    'content' => $item->ten_khu_vuc,
                    'key' => 'kv'.$item->kv_id,
                    'value' => 0
                ];
            }
        }
        $model->dvpt = $arr_dvpt;
        $model->kv = $arr_kv;
        
        //Xử lý áp dụng KM theo ngày và theo giờ
        if($model->hour_gio_ap_dung) //Áp dụng theo giờ
        {
            $model->thoi_gian_ap_dung = 'hour';
        }else
        {
            $model->thoi_gian_ap_dung = 'day';
        }
        
        //Xử lý áp dụng KM đăng ký mới
        if($model->new_ngay_bat_dau && $model->new_ngay_ket_thuc)
        {
            $model->new_gkh = 1;
        }else
        {
            $model->new_gkh = 0;
        }
        
        if($model === null){
            $this->flash('error', "Không tìm thấy gói khách hàng nào");
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $dataPost = Yii::$app->request->post();
            //Xử lý gói dịch vụ
            $model->gdv_id = json_encode($dataPost[$model->formName()]['gdv_id']);
//            echo '<pre>';
//            print_r($arr_dvpt);
//            
//            print_r($dataPost['dvpt']);
//            echo '</pre>';
//            exit();
            //Xử lý dịch vụ phụ trội JSON
            if(isset($dataPost['dvpt']))
            {
                foreach($arr_dvpt as $item)
                {
                    $item['value'] = 0;
                    foreach($dataPost['dvpt'] as $key => $value)
                    {
                        if($item['key'] == $key)
                        {
                            $item['value'] = 1;
                        }
                    }
                    $arr_dvpt[$item['key']] = $item;                            
            
                }
                $model->dich_vu_phu_troi = json_encode($arr_dvpt, JSON_UNESCAPED_UNICODE);
            }else
            {
                $model->dich_vu_phu_troi = NULL;
            }
            
            //Xử lý khu vực JSON
            if(isset($dataPost['kv']))
            {
                foreach($arr_kv as $item)
                {
                    $item['value'] = 0;
                    foreach($dataPost['kv'] as $key => $value)
                    {
                        if($item['key'] == $key)
                        {
                            $item['value'] = 1;
                        }
                    }
                    $arr_kv[$item['key']] = $item;
                }
                
                $model->khu_vuc = json_encode($arr_kv, JSON_UNESCAPED_UNICODE);
            }else
            {
                $model->khu_vuc = NULL;
            }
            
            //Xử lý áp dụng gói KM theo ngày
            if($dataPost[$model->formName()]['thoi_gian_ap_dung'] == 'day')
            {
                $model->day_ngay_bat_dau = strtotime($dataPost['day_gkh_begin']);
                $model->day_ngay_ket_thuc = strtotime($dataPost['day_gkh_end']);
                $model->hour_gio_ap_dung = NULL;
                $model->hour_thoi_gian_ap_dung = NULL;
            }else if($dataPost[$model->formName()]['thoi_gian_ap_dung'] == 'hour')
            {
                $model->hour_thoi_gian_ap_dung = time();
                $model->day_ngay_bat_dau = NULL;
                $model->day_ngay_ket_thuc = NULL;
            }
            
            //Xử lý áp dụng gói KM khi đăng ký mới
            if($dataPost[$model->formName()]['new_gkh'] == 1) //Có áp dụng -> set ngày bắt đầu và kết thúc
            {
                $model->new_ngay_bat_dau = strtotime($dataPost['new_gkh_begin']);
                $model->new_ngay_ket_thuc = strtotime($dataPost['new_gkh_end']);
            }else
            {
                $model->new_ngay_bat_dau = NULL;
                $model->new_ngay_ket_thuc = NULL;
            }
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', "Cập nhật gói khách hàng thành công");
                }
                else{
                    $this->flash('error', "Cập nhật gói khách hàng thất bại. Hãy kiểm tra lại thông tin!");
                }
                return $this->refresh();
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
        if(($model = Goikhachhang::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Không tìm thấy gói khách hàng nào";
        }
        return $this->formatResponse("Xóa gói khách hàng thành công");
    }
    
    public function actionChangestatus()
    {
        if(\Yii::$app->request->post())
        {
            $dataPost = \Yii::$app->request->post();
            $id = $dataPost['gkh_id'];
            $gkh = Goikhachhang::findOne($id);
            if($gkh->status == 0)
            {
                $gkh->status = 1;
            }else if($gkh->status == 1)
            {
                $gkh->status = 0;
            }
            if($gkh->save(false))
            {
                echo 'Change status success!';
            }else
            {
                echo 'Change status fail!';
            }
        }
        
    }
}