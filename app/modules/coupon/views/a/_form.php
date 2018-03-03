<?php
use kartik\date\DatePicker;
use kartik\field\FieldRange;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\goidichvu\models\Goidichvu;
use kartik\checkbox\CheckboxX;
use richardfan\widget\JSRegister;
use app\modules\khachhang\models\Khachhang;

$module = $this->context->module->id;
?>
<div class="row">
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form', 'id' => 'myForm']
    ]); ?>
    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="panel">
            <header class="panel-heading">
                Thông tin coupon
                <span class="tools pull-right">
                    <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <?= $form->field($model, 'ten_coupon');?>
                    </div>
                    
                    <div class="col-md-4">
                        <?= $form->field($model, 'tien_to')?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'mo_ta')->textarea(); ?>
                    </div>
                </div>
                                                
                <div class="row">
                    <div class="col-md-3">
                        <?=
                            $form->field($model, 'gdv_id')->widget(Select2::className(), [
                                'data' => ArrayHelper::map(Goidichvu::find()->all(), 'gdv_id', 'ten_goi_dich_vu'),
                                'options' => [
                                    'placeholder' => 'Chọn gói dịch vụ áp dụng',
                                    'multiple' => true
                                ]
                            ])
                        ?>     
                    </div>
                    <div class="col-md-3">
                        <?=
                            $form->field($model, 'hinh_thuc_khuyen_mai')->widget(Select2::className(), [
                                'data' => [
                                    'Giảm theo %' => 'Giảm theo %',
                                    'Giảm cước' => 'Giảm cước',
                                    'Đồng giá' => 'Đồng giá',
                                    'Tặng tiền' => 'Tặng tiền'
                                ],
                                'options' => [
                                    'placeholder' => "Chọn hình thức khuyến mại"
                                ]
                            ])
                        ?>
                    </div>
                    
                    <div class="col-md-2">
                        <?= $form->field($model, 'gia_tri')?>
                    </div>
                    
                    <div class="col-md-2">
                        <?= $form->field($model, 'gioi_han')?>
                    </div>
                    
                    <div class="col-md-2">
                        <?= $form->field($model, 'so_luong_coupon')?>
                    </div>
                </div>
                
                <div class="row" style="margin-bottom: 20px">
                    <div class="col-md-12">
                        <?php
                            $dateRanger = <<< HTML
                                <span class="input-group-addon">Từ ngày</span>
                                {input1}
                                <span class="input-group-addon">Đến ngày</span>
                                {input2}
                                <span class="input-group-addon kv-date-remove">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </span>
HTML;
                            
                                if($model->ngay_bat_dau && $model->ngay_ket_thuc)
                                {
                                    $beginTime = date('d-m-Y', $model->ngay_bat_dau);
                                    $endTime = date('d-m-Y', $model->ngay_ket_thuc);
                                    echo '<label>Thời gian áp dụng</label>';
                                    echo DatePicker::widget([
                                        'type' => DatePicker::TYPE_RANGE,
                                        'name' => 'ngay_bat_dau',
                                        'value' => $beginTime,
                                        'name2' => 'ngay_ket_thuc',
                                        'value2' => $endTime,
                                        'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                        'layout' => $dateRanger,
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ]);
                                }else
                                {
                                    echo '<label>Thời gian áp dụng</label>';
                                    echo DatePicker::widget([
                                        'type' => DatePicker::TYPE_RANGE,
                                        'name' => 'ngay_bat_dau',
                                        'value' => date('d-m-Y'),
                                        'name2' => 'ngay_ket_thuc',
                                        'value2' => date('d-m-Y'),
                                        'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                        'layout' => $dateRanger,
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ]);
                                }
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            if($model->ap_dung_cung_goi_khach_hang)
                            {
                                echo $form->field($model, 'ap_dung_cung_goi_khach_hang')->widget(CheckboxX::classname(), [
                                    'autoLabel'=>true,
                                    'value' => $model->ap_dung_cung_goi_khach_hang,
                                    'pluginOptions'=>['threeState'=>false]
                                ])->label(false);
                            }else
                            {
                                echo $form->field($model, 'ap_dung_cung_goi_khach_hang')->widget(CheckboxX::classname(), [
                                    'autoLabel'=>true,
                                    'pluginOptions'=>['threeState'=>false]
                                ])->label(false);
                            }
                        ?>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-md-6">
                        <legend><small>Khu vực áp dụng</small></legend>
                        <?php foreach($model->kv as $item):?>
                            <div class="form-group">
                            <label class="cbx-label" for="<?= $item['key']?>">
                            <?= CheckboxX::widget([
                                'name' => 'kv['.$item['key'].']',
                                'value' => $item['value'],
                                'initInputType' => CheckboxX::INPUT_CHECKBOX,
                                'options'=>['id' => $item['key']],
                                'pluginOptions' => [
                                    'theme' => 'krajee-flatblue',
                                    'enclosedLabel' => true,
                                    'threeState' => false
                                ]
                            ]); ?>
                            <?= $item['content'];?>
                            </label>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
                
                    <?= Html::submitButton("Lưu mã khuyến mại", ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end();?>