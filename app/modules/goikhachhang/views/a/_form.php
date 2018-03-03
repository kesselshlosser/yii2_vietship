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

$module = $this->context->module->id;
?>
<div class="row">
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form', 'id' => 'myForm']
    ]); ?>
    <div class="col-md-6 col-xs-12 col-sm-12">
        <div class="panel">
            <header class="panel-heading">
                Thông tin gói
                <span class="tools pull-right">
                    <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                    <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                    <a class="close-box fa fa-times" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'ten_goi')->textInput()->label('Tên gói*')?>
                    </div>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'hinh_thuc')->widget(Select2::className(), [
                                'data' => [
                                    'Giảm theo %' => 'Giảm theo %',
                                    'Giảm cước' => 'Giảm cước',
                                    'Tăng cước' => 'Tăng cước',
                                    'Đồng giá' => 'Đồng giá',
                                ],
                                'options' => [
                                    'placeholder' => "Chọn hình thức khuyến mại"
                                ]
                            ])->label('Hình thức khuyến mại*')
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <?=
                            $form->field($model, 'mo_ta')->textarea()->label('Mô tả*');
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'gdv_id')->widget(Select2::className(), [
                                'data' => ArrayHelper::map(Goidichvu::find()->all(), 'gdv_id', 'ten_goi_dich_vu'),
                                'options' => [
                                    'placeholder' => 'Chọn gói dịch vụ áp dụng'
                                ],
                                'pluginOptions' => [
                                    'multiple' => true
                                ]
                            ])->label('Gói dịch vụ*')
                        ?>
                    </div>
                    
                    <div class="col-md-6">
                        <?= $form->field($model, 'gia_tri')->textInput()->label('Giá trị áp dụng*')?>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            if($model->chi_giam_dich_vu_phu_troi)
                            {
                                echo $form->field($model, 'chi_giam_dich_vu_phu_troi')->widget(CheckboxX::classname(), [
                                    'autoLabel'=>true,
                                    'value' => $model->chi_giam_dich_vu_phu_troi,
                                    'pluginOptions'=>['threeState'=>false]
                                ])->label(false);
                            }else
                            {
                                echo $form->field($model, 'chi_giam_dich_vu_phu_troi')->widget(CheckboxX::classname(), [
                                    'autoLabel'=>true,
                                    'pluginOptions'=>['threeState'=>false]
                                ])->label(false);
                            }
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <?= 
                            $form->field($model, 'muc_do_uu_tien')->textInput(['placeholder' => 'Mức độ ưu tiên: 1, 2, 3...'])->label('Mức độ ưu tiên*')
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <legend><small>Dịch vụ phụ trội</small></legend>
                        <?php foreach($model->dvpt as $item):?>
                            <div class="form-group has-success">
                                <label class="cbx-label" for="<?= $item['key']?>">
                                <?= CheckboxX::widget([
                                    'name' => 'dvpt['.$item['key'].']',
                                    'value' => $item['value'],
                                    'initInputType' => CheckboxX::INPUT_CHECKBOX,
                                    'options'=>['id' => $item['key']],
                                    'pluginOptions' => [
                                        'theme' => 'krajee-flatblue',
                                        'enclosedLabel' => true,
                                        'threeState' => false
                                    ]
                                ]); ?>
                                <?= $item['content']?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                    
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
                    
                    
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xs-12 col-sm-12">
        <div class="panel">
            <header class="panel-heading">
                Thời gian áp dụng
                <span class="tools pull-right">
                    <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                    <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                    <a class="close-box fa fa-times" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <?= $form->field($model, 'thoi_gian_ap_dung')->radioList([
                            'day' => 'Áp dụng theo ngày',
                            'hour' => 'Áp dụng theo giờ'
                        ],
                        [
                            'id' => 'thoi_gian_ap_dung',
                        ]
                    )->label(false);?>
                </div>
                
                <div class="form-group">
                    <?php if($model->thoi_gian_ap_dung == 'day'):?>
                        <div id="hour_gkh_number" style="padding-left: 20px; display: none">
                            <?= $form->field($model, 'hour_gio_ap_dung')?>
                        </div>

                        <div id="day_gkh_date_ranger">
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
                                    $beginTime = date('d-m-Y', $model->day_ngay_bat_dau);
                                    $endTime = date('d-m-Y', $model->day_ngay_ket_thuc);
                                    echo DatePicker::widget([
                                        'type' => DatePicker::TYPE_RANGE,
                                        'name' => 'day_gkh_begin',
                                        'value' => $beginTime,
                                        'name2' => 'day_gkh_end',
                                        'value2' => $endTime,
                                        'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                        'layout' => $dateRanger,
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'dd-M-yyyy'
                                        ]
                                    ]);
                                    
                        ?>
                    <?php elseif($model->thoi_gian_ap_dung == 'hour'):?>
                        <div id="hour_gkh_number" style="padding-left: 20px;">
                            <?= $form->field($model, 'hour_gio_ap_dung')?>
                        </div>

                        <div id="day_gkh_date_ranger" style="display: none">
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
                                    echo DatePicker::widget([
                                        'type' => DatePicker::TYPE_RANGE,
                                        'name' => 'day_gkh_begin',
                                        'value' => date('d-m-Y'),
                                        'name2' => 'day_gkh_end',
                                        'value2' => date('d-m-Y'),
                                        'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                        'layout' => $dateRanger,
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'dd-M-yyyy'
                                        ]
                                    ]);
                        ?>
                    <?php elseif($model->thoi_gian_ap_dung == ''):?>
                        <div id="hour_gkh_number" style="padding-left: 20px; display: none">
                            <?= $form->field($model, 'hour_gio_ap_dung')?>
                        </div>

                        <div id="day_gkh_date_ranger" style="display:none">
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
                                echo DatePicker::widget([
                                    'type' => DatePicker::TYPE_RANGE,
                                    'name' => 'day_gkh_begin',
                                    'value' => date('d-m-Y'),
                                    'name2' => 'day_gkh_end',
                                    'value2' => date('d-m-Y'),
                                    'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                    'layout' => $dateRanger,
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'dd-M-yyyy'
                                    ]
                                ]);
                        ?>
                        </div>
                    <?php endif;?>
                    
                </div>
            </div>
        </div>
        
        <div class="panel">
            <header class="panel-heading">
                Áp dụng đăng ký mới
                <span class="tools pull-right">
                    <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                    <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                    <a class="close-box fa fa-times" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <?php
                        echo $form->field($model, 'new_gkh')->widget(CheckboxX::classname(), [
                            'autoLabel' => true,
                            'value' => $model->new_gkh,
                            'options'=>['id' => 'new_gkh'],
                            'pluginOptions'=>['threeState'=>false]
                        ])->label(false);
                    ?>
                </div>
                
                <div id="new_gkh_date_ranger" class="form-group" style="display:none">
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
                        if($model->new_ngay_bat_dau && $model->new_ngay_ket_thuc)
                        {
                            $beginTime = date('d-m-Y', $model->new_ngay_bat_dau);
                            $endTime = date('d-m-Y', $model->new_ngay_ket_thuc);
                            echo DatePicker::widget([
                                'type' => DatePicker::TYPE_RANGE,
                                'name' => 'new_gkh_begin',
                                'value' => $beginTime,
                                'name2' => 'new_gkh_end',
                                'value2' => $endTime,
                                'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                'layout' => $dateRanger,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-M-yyyy'
                                ]
                            ]);
                        }  else {
                            echo DatePicker::widget([
                                'type' => DatePicker::TYPE_RANGE,
                                'name' => 'new_gkh_begin',
                                'value' => date('d-m-Y'),
                                'name2' => 'new_gkh_end',
                                'value2' => date('d-m-Y'),
                                'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                'layout' => $dateRanger,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-M-yyyy'
                                ]
                            ]);
                        }
                    ?>
                </div>
                <?= Html::submitButton("Lưu gói khách hàng", ['class' => 'btn btn-success btn-block']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>

<?php JSRegister::begin();?>
<script>
    var thoi_gian_ap_dung_checked = $('input[name="Goikhachhang[thoi_gian_ap_dung]"]:checked', '#myForm').val();
    if(thoi_gian_ap_dung_checked == 'day') //Show date ranger and hide Hour number
    {
        $("#hour_gkh_number").css({'display' : 'none'});
        $("#day_gkh_date_ranger").css({'display' : 'block'});
    }else if(thoi_gian_ap_dung_checked == 'hour') //Hide date ranger and show hour number
    {
        $("#hour_gkh_number").css({'display' : 'block'});
        $("#day_gkh_date_ranger").css({'display' : 'none'});
    }
    
    $('#myForm input[name="Goikhachhang[thoi_gian_ap_dung]"]').change(() => {
        var thoi_gian_ap_dung_checked = $('input[name="Goikhachhang[thoi_gian_ap_dung]"]:checked', '#myForm').val();
        if(thoi_gian_ap_dung_checked == 'day') //Show date ranger and hide Hour number
        {
            $("#hour_gkh_number").css({'display' : 'none'});
            $("#day_gkh_date_ranger").css({'display' : 'block'});
        }else if(thoi_gian_ap_dung_checked == 'hour') //Hide date ranger and show hour number
        {
            $("#hour_gkh_number").css({'display' : 'block'});
            $("#day_gkh_date_ranger").css({'display' : 'none'});
        }
    })
    
    var new_gkh_checked = $('#new_gkh').val();
    if(new_gkh_checked == 1)
    {
        $("#new_gkh_date_ranger").css({'display' : 'block'});
    }else if(new_gkh_checked == 0)
    {
        $("#new_gkh_date_ranger").css({'display' : 'none'});
    }
    $("#new_gkh").change(() => {
        var new_gkh_value = $("#new_gkh").val();
        if(new_gkh_value == 1)
        {
            $("#new_gkh_date_ranger").css({'display' : 'block'});
        }else if(new_gkh_value == 0)
        {
            $("#new_gkh_date_ranger").css({'display' : 'none'});
        }
    });
</script>
<?php JSRegister::end();?>