<?php
namespace app\modules\goidichvu\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\modules\goidichvu\models\Goidichvu;

class AController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Goidichvu::find(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Goidichvu;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', 'Tạo gói dịch vụ thành công!');
                    return $this->redirect(['/admin/'.$this->module->id]);
                }
                else{
                    $this->flash('error', 'Tạo gói dịch vụ thất bại! Hãy kiểm tra lại thông tin vừa khởi tạo!');
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
        $model = Goidichvu::findOne($id);

        if($model === null){
            $this->flash('error', "Không tìm thấy gói dịch vụ nào");
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', "Cập nhật gói dịch vụ thành công");
                }
                else{
                    $this->flash('error', "Cập nhật gói dịch vụ thất bại. Hãy kiểm tra lại thông tin!");
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
        if(($model = Goidichvu::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Không tìm thấy gói dịch vụ nào";
        }
        return $this->formatResponse("Xóa gói dịch vụ thành công");
    }
}