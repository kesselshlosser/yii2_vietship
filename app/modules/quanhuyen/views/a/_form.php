<?php
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
        <?= $form->field($model, 'ten_quan_huyen')?>
        <?= Html::submitButton("Lưu quận huyện", ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end();?>
    </div>
</div>
 