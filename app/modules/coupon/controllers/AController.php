<?php
namespace app\modules\coupon\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\modules\coupon\models\Coupon;
use app\modules\khuvuc\models\Khuvuc;

class AController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Coupon::find(),
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }
    
    //Function random string include string and number
    public function randomString($length, $tien_to) {
            $str = "";
            $characters = array_merge(range('a','z'), range('0','9'));
            $max = count($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                    $rand = mt_rand(0, $max);
                    $str .= $characters[$rand];
            }
            return $tien_to.$str;
    } 

    public function actionCreate()
    {
        $model = new Coupon;
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
//            echo '<pre>';
//            print_r($dataPost);
//            echo '</pre>';
//            exit();
            //Xử lý dịch vụ phụ trội JSON
//            if(isset($dataPost['dvpt']))
//            {
//                foreach($dataPost['dvpt'] as $key => $value)
//                {
//                    if($model_dvpt[$key])
//                    {
//                        $model_dvpt[$key]['value'] = 1;
//                    }
//                }
//                $model->dich_vu_phu_troi = json_encode($model_dvpt, JSON_UNESCAPED_UNICODE);
//            }else
//            {
//                $model->dich_vu_phu_troi = NULL;
//            }
            
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
            
            //Xử lý thời gian áp dụng
            $model->ngay_bat_dau = strtotime($dataPost['ngay_bat_dau']);
            $model->ngay_ket_thuc = strtotime($dataPost['ngay_ket_thuc']);
            
            //Xử lý gói dịch vụ
            $goi_dich_vu = [];
            foreach($dataPost['Coupon']['gdv_id'] as $gdv)
            {
                $goi_dich_vu[] = $gdv;
            }
            $model->gdv_id = json_encode($goi_dich_vu, JSON_UNESCAPED_UNICODE);
            
            //Xử lý mã coupon
            $condition = 1;
            while($condition == 1)
            {
                $random_ma_coupon = $this->randomString(8, $dataPost['Coupon']['tien_to']);
                $check_ma_coupon = count(Coupon::find()->where(['ma_coupon' => $random_ma_coupon])->asArray()->one());
                if($check_ma_coupon == 0) //Thêm mới
                {
                    $model->ma_coupon = $random_ma_coupon;
                    $condition = 0;
                }
            }
            
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', 'Tạo coupon thành công!');
                    return $this->redirect(['/admin/'.$this->module->id]);
                }
                else{
                    $this->flash('error', 'Tạo coupon thất bại! Hãy kiểm tra lại thông tin vừa khởi tạo!');
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
        $model = Coupon::findOne($id);
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
        $model->gdv_id = json_decode($model->gdv_id, true);
        
        if($model === null){
            $this->flash('error', "Không tìm thấy coupon nào");
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $dataPost = Yii::$app->request->post();
            //Xử lý dịch vụ phụ trội JSON
//            if(isset($dataPost['dvpt']))
//            {
//                foreach($arr_dvpt as $item)
//                {
//                    $item['value'] = 0;
//                    foreach($dataPost['dvpt'] as $key => $value)
//                    {
//                        if($item['key'] == $key)
//                        {
//                            $item['value'] = 1;
//                        }
//                    }
//                    $arr_dvpt[$item['key']] = $item;                            
//                }
//                $model->dich_vu_phu_troi = json_encode($arr_dvpt, JSON_UNESCAPED_UNICODE);
//            }else
//            {
//                $model->dich_vu_phu_troi = NULL;
//            }
            
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
            
            //Xử lý thời gian áp dụng
            $model->ngay_bat_dau = strtotime($dataPost['ngay_bat_dau']);
            $model->ngay_ket_thuc = strtotime($dataPost['ngay_ket_thuc']);
            
            //Xử lý gói dịch vụ
            $goi_dich_vu = [];
            foreach($dataPost['Coupon']['gdv_id'] as $gdv)
            {
                $goi_dich_vu[] = $gdv;
            }
            $model->gdv_id = json_encode($goi_dich_vu, JSON_UNESCAPED_UNICODE);
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', "Cập nhật coupon thành công");
                }
                else{
                    $this->flash('error', "Cập nhật coupon thất bại. Hãy kiểm tra lại thông tin!");
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
        if(($model = Coupon::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Không tìm thấy coupon nào";
        }
        return $this->formatResponse("Xóa coupon thành công");
    }
}