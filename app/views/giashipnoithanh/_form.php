<?php
use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\goidichvu\models\Goidichvu;

$module = $this->context->module->id;
?>

<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
        ]); ?>
        
        <?=
            $form->field($model, 'kvl_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Khuvuc::find()->all(), 'kv_id', 'ten_khu_vuc'),
                'options' => [
                    'placeholder' => 'Chọn khu vực lấy'
                ]
            ])
        ?>
        
        <?=
            $form->field($model, 'kvg_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Khuvuc::find()->all(), 'kv_id', 'ten_khu_vuc'),
                'options' => [
                    'placeholder' => 'Chọn khu vực giao'
                ]
            ])
        ?>
        
        <?=
                $form->field($model, 'gdv_id')->widget(Select2::className(), [
                    'data' => ArrayHelper::map(Goidichvu::find()->all(), 'gdv_id', 'ten_goi_dich_vu'),
                    'options' => [
                        'placeholder' => 'Chọn gói dịch vụ'
                    ]
                ])
        ?>
        
        <?= $form->field($model, 'don_gia')?>
        <?= Html::submitButton("Lưu gía ship nội thành", ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end();?>
    </div>
</div>
 