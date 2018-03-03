<?php
use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$module = $this->context->module->id;
?>

<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
        ]); ?>
        <?= $form->field($model, 'ten_goi_dich_vu')?>
        <?= Html::submitButton("Lưu gói dịch vụ", ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end();?>
    </div>
</div>
 