<?php
use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\quanhuyen\models\Quanhuyen;

$module = $this->context->module->id;
?>

<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
        ]); ?>
        <?=
            $form->field($model, 'qh_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Quanhuyen::find()->all(), 'qh_id', 'ten_quan_huyen'),
                'options' => [
                    'placeholder' => 'Chọn quận huyện'
                ]
            ])
        ?>
        
        <?=
            $form->field($model, 'kv_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Khuvuc::find()->all(), 'kv_id', 'ten_khu_vuc'),
                'options' => [
                    'placeholder' => 'Chọn khu vực'
                ]
            ])
        ?>
        
        <?= $form->field($model, 'ten_pho')?>
        <?= Html::submitButton("Lưu đường phố", ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end();?>
    </div>
</div>
 