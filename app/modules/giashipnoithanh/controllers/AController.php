<?php
namespace app\modules\giashipnoithanh\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\modules\giashipnoithanh\models\Giashipnoithanh;

class AController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Giashipnoithanh::find(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Giashipnoithanh;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', 'Tạo giá ship nội thành thành công!');
                    return $this->redirect(['/admin/'.$this->module->id]);
                }
                else{
                    $this->flash('error', 'Tạo giá ship nội thành thất bại! Hãy kiểm tra lại thông tin vừa khởi tạo!');
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Giashipnoithanh::findOne($id);

        if($model === null){
            $this->flash('error', "Không tìm thấy giá ship nội thành nào");
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', "Cập nhật giá ship nội thành thành công");
                }
                else{
                    $this->flash('error', "Cập nhật giá ship nội thành thất bại. Hãy kiểm tra lại thông tin!");
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
        if(($model = Giashipnoithanh::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Không tìm thấy giá ship nội thành nào";
        }
        return $this->formatResponse("Xóa giá ship nội thành thành công");
    }
    
    public function actionCalculatePrice()
    {
        $model = new Giashipnoithanh();
        
        if($model->load(Yii::$app->request->post()))
        {
            $dataForm = Yii::$app->request->post()[$model->formName()];
            $kvl_id = $dataForm['kvl_id'];
            $kvg_id = $dataForm['kvg_id'];
            $gdv_id = $dataForm['gdv_id'];
            //Tìm đơn giá
            $dongia = Giashipnoithanh::find()->where([
                'kvl_id' => $kvl_id,
                'kvg_id' => $kvg_id,
                'gdv_id' => $gdv_id
            ])->one()['don_gia'];
            if($dongia)
            {
                return $dongia;
            }
            return -1;
        }
        return $this->render('calculateprice', ['model' => $model]);
    }
}