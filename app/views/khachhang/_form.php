<?php
use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\goikhachhang\models\Goikhachhang;
use unclead\multipleinput\MultipleInput;
use app\modules\duongpho\models\Duongpho;
use richardfan\widget\JSRegister;
use yii\bootstrap\Modal;

$module = $this->context->module->id;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<!--<style>
    #tte .multiple-input-list__btn {
        margin-top : -10px !important;
    }
</style>-->
<?php
 Modal::begin();
 Modal::end();
?>
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="panel">
            <header class="panel-heading">
                Thông tin khách hàng
                <span class="tools pull-right">
                    <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                    <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                    <a class="close-box fa fa-times" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <?php if(!$model->ten_dang_nhap):?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'ten_dang_nhap')->textInput()->label("Tên đăng nhập(*)")?>
                    </div>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'mat_khau')->passwordInput()->label("Mật khẩu(*)")
                        ?>
                    </div>
                </div>
                <?php endif;?>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'so_dien_thoai')->textInput()->label("Số điện thoại(*)")?>
                    </div>
                    
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput()->label("Email(*)")?>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'ten_hien_thi')
                        ?>
                    </div>
                    
                    <div class="col-md-6">
                        <?= $form->field($model, 'website')?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'ten_shop')?>
                    </div>
                    
                    <div class="col-md-6">
                        <?= $form->field($model, 'facebook')?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'dia_chi')->textarea(['rows' => 5])->label("Địa chỉ(*)")?>
                    </div>
                    
                    <div class="col-md-6">
                        <legend><small>Tính năng ẩn</small></legend>
                        <?php foreach($model->arr_tinh_nang_an as $item):?>
                            <div class="form-group has-success">
                                <label class="cbx-label" for="<?= $item['key']?>">
                                <?= CheckboxX::widget([
                                    'name' => 'tna['.$item['key'].']',
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
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        
                        <?php
                            
                            echo $form->field($model, 'gkh_id')->widget(Select2::className(), [
                                'data' => ArrayHelper::map(Goikhachhang::find()->all(), 'gkh_id', 'ten_goi'),
                                'options' => [
                                    'placeholder' => 'Chọn gói khách hàng'
                                ],
                                'pluginOptions' => [
                                    'multiple' => true
                                ]
                        ])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="panel">
            <header class="panel-heading">
                Địa chỉ lấy hàng
                <span class="tools pull-right">
                    <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                    <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                    <a class="close-box fa fa-times" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            $list_duong_pho = ArrayHelper::map(Duongpho::find()->all(), 'dp_id', 'ten_pho');
                            echo $form->field($model_dclh, 'arr_dclh')->widget(MultipleInput::className(), [
                                'id' => 'tte',                                
                                'sortable' => true,
                                'columns' => [
                                    [
                                        'name'  => 'ten_goi_nho',
                                        'options' => [
                                            'placeholder' => 'Tên gợi nhớ. VD: kho1',
                                            'required' => true,
                                            'oninvalid' => "this.setCustomValidity('Bạn chưa nhập tên gợi nhớ')",
                                            'oninput' => "setCustomValidity('')",                                            
                                        ]
                                    ],
                                    [
                                        'name'  => 'ten_nguoi_ban_giao_hang',
                                        'options' => [
                                            'placeholder' => 'Tên người bàn giao',
                                            'required' => true,
                                            'oninvalid' => "this.setCustomValidity('Bạn chưa nhập người bàn giao hàng')",
                                            'oninput' => "setCustomValidity('')"
                                        ]
                                    ],
                                    [
                                        'name'  => 'so_dien_thoai',
                                        'options' => [
                                            'placeholder' => 'Số điện thoại',
                                            'required' => true,
                                            'oninvalid' => "this.setCustomValidity('Bạn chưa nhập số điện thoại')",
                                            'oninput' => "setCustomValidity('')"
                                        ]
                                    ],
                                    [
                                        'name'  => 'dia_chi_text',
                                        'options' => [
                                            'placeholder' => "Địa chỉ",
                                            'class' => 'dia_chi_text',
                                            'required' => true,
                                            'oninvalid' => "this.setCustomValidity('Bạn chưa nhập địa chỉ')",
                                            'oninput' => "setCustomValidity('')"
                                        ]
                                    ],
                                    [
                                        'name'  => 'dp_id',
                                        'type'  => Select2::className(),
                                        'options' => [
                                            'data' => $list_duong_pho,
                                            'options' => [
                                                'placeholder' => 'Chọn đường phố',
                                                'class' => 'list_duong_pho'
                                            ]
                                        ]
                                    ],
                                ]
                            ])->label("Bạn cần phải nhập đầy đủ thông tin : tên gợi nhớ, người nhận, số điện thoại và địa chỉ");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="panel">
            <header class="panel-heading">
                Hình thức thanh toán
                <span class="tools pull-right">
                    <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                    <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                    <a class="close-box fa fa-times" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12 col-sm-12">
                        <?= $form->field($model_httt, 'hinh_thuc_thanh_toan')->widget(Select2::className(), [
                            'data' => [
                                'Tiền mặt' => 'Tiền mặt',
                                'Chuyển khoản' => 'Chuyển khoản'
                            ],
                            'options' => [
                                'placeholder' => 'Chọn hình thức thanh toán',
                                'id' => 'hinh_thuc_thanh_toan'
                            ]
                        ])->label(FALSE)?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="display : none" id="tt_chuyen_khoan">
                        <?php
                            echo $form->field($model_httt, 'arr_ttck')->widget(MultipleInput::className(), [
                                'sortable' => true,
                                'columns' => [
                                    [
                                        'name'  => 'ten_ngan_hang',
                                        'options' => [
                                            'placeholder' => 'Tên ngân hàng',
                                        ]
                                    ],
                                    [
                                        'name'  => 'chu_tai_khoan',
                                        'options' => [
                                            'placeholder' => 'Chủ tài khoản',
                                        ]
                                    ],
                                    [
                                        'name'  => 'so_tai_khoan',
                                        'options' => [
                                            'placeholder' => 'Số tài khoản',
                                        ]
                                    ],
                                    [
                                        'name'  => 'chi_nhanh',
                                        'options' => [
                                            'placeholder' => 'Chi nhánh',
                                        ]
                                    ],
                                    [
                                        'name'  => 'tinh_thanh',
                                        'options' => [
                                            'placeholder' => 'Tỉnh thành',
                                        ]
                                    ],
                                ]
                            ])->label(false);
                        ?>
                    </div>
                    
                    <div class="col-md-12 col-xs-12 col-sm-12" style="display: none" id="tt_tien_mat">
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <?= $form->field($model_httt, 'ten_nguoi_nhan')->textInput(['placeholder' => 'Tên người nhận'])->label(false)?>
                        </div>
                        
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <?= $form->field($model_httt, 'dia_chi')->textInput(['placeholder' => 'Địa chỉ'])->label(false)?>
                        </div>
                        
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <?= $form->field($model_httt, 'so_dien_thoai')->textInput(['placeholder' => 'Số điện thoại'])->label(false)?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <?= $form->field($model_httt, 'json_thoi_gian_thanh_toan')->widget(Select2::className(), [
                            'data' => [
                                'Thanh toán cuối ngày' => 'Thanh toán cuối ngày',
                                'Thanh toán vào hôm sau' => 'Thanh toán vào hôm sau',
                                'Thanh toán vào thứ 2, 4, 6' => 'Thanh toán vào thứ 2, 4, 6',
                                'Mỗi tuần 1 lần' => 'Mỗi tuần 1 lần',
                                'Theo yêu cầu' => 'Theo yêu cầu'
                            ],
                            'options' => [
                                'placeholder' => 'Chọn thời gian thanh toán',
                                'id' => 'json_thoi_gian_thanh_toan'
                            ]
                        ])->label(false)?>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-6" style="display: none" id="thanh_toan_theo_tuan">
                        <?= $form->field($model_httt, 'thanh_toan_theo_tuan')->textInput(['placeholder' => 'Nhập 1 ngày trong tuần bằng số (Từ thứ 2 đến thứ 7)'])->label(false)?>
                    </div>
                </div>
            </div>
        </div>
        <?= Html::submitButton("Lưu thông tin khách hàng", ['class' => 'btn btn-success']) ?>
    </div>
</div>
 <?php ActiveForm::end();?>

<?php JSRegister::begin();?>
<script>
    //Xử lý select hình thức thanh toán
    $('#hinh_thuc_thanh_toan').change(() => {
        var httt_selected = $('#hinh_thuc_thanh_toan :selected').val();
        if(httt_selected == 'Tiền mặt')
        {
            $('#tt_chuyen_khoan').css({'display' : 'none'});
            $('#tt_tien_mat').css({'display' : 'block'});
        }else if(httt_selected == 'Chuyển khoản')
        {
            $('#tt_chuyen_khoan').css({'display' : 'block'});
            $('#tt_tien_mat').css({'display' : 'none'});
        }
    })
    
    //Xử lý hiển thị thời gian thanh toán edit page
    var hinh_thuc_thanh_toan = $('#hinh_thuc_thanh_toan :selected').val();
    if(hinh_thuc_thanh_toan == 'Tiền mặt')
    {
        $('#tt_chuyen_khoan').css({'display' : 'none'});
        $('#tt_tien_mat').css({'display' : 'block'});
    }else if(hinh_thuc_thanh_toan == 'Chuyển khoản')
    {
        $('#tt_chuyen_khoan').css({'display' : 'block'});
        $('#tt_tien_mat').css({'display' : 'none'});
    }
    
    //Xử lý select thời gian thanh toán
    $('#json_thoi_gian_thanh_toan').change(() => {
        var tgtt_selected = $('#json_thoi_gian_thanh_toan :selected').val();
        if(tgtt_selected == 'Mỗi tuần 1 lần')
        {
            $('#thanh_toan_theo_tuan').css({'display' : 'block'});
        }else
        {
            $('#thanh_toan_theo_tuan').css({'display' : 'none'});
        }
    })
    
    //Xử lý hiển thị thời gian thanh toán edit page
    var thoi_gian_thanh_toan = $('#json_thoi_gian_thanh_toan :selected').val();
    if(thoi_gian_thanh_toan == 'Mỗi tuần 1 lần')
    {
        $('#thanh_toan_theo_tuan').css({'display' : 'block'});
    }else
    {
        $('#thanh_toan_theo_tuan').css({'display' : 'none'});
    }
    
    //Xử lý lấy địa chỉ
    $('#tte').find('tr').each(function() {                
        $(this).find('.dia_chi_text').blur(function(){
            var after_result_search = 0;
            if($(this).val())
            {
                var dia_chi_text_value = $(this).val().toLowerCase();
                var ten_pho_element = $(this).parent().parent().next().find('.list_duong_pho');
                var ten_pho_option_element = $(this).parent().parent().next().find('.list_duong_pho option');

                $(ten_pho_option_element).each(function(option) {
                    var ten_pho_value = $(this).val();
                    var ten_pho_text = $(this).text().toLowerCase();
                    after_result_search = dia_chi_text_value.search(ten_pho_text);
                    if(after_result_search >= 0)
                    {
                        $(ten_pho_element).val(ten_pho_value).trigger("change");
                    }
                })
            }
        });
    });
    
    var numberOfRows = $('#tte tr').length;
    
    $("#tte").bind("DOMSubtreeModified", function() {
        if($("#tte tr").length !== numberOfRows){
            numberOfRows = $("#tte tr").length;
            $('#tte').find('tr').each(function() {                
                $(this).find('.dia_chi_text').blur(function(){
                    var after_result_search = 0;
                    if($(this).val())
                    {
                        var dia_chi_text_value = $(this).val().toLowerCase();
                        var ten_pho_element = $(this).parent().parent().next().find('.list_duong_pho');
                        var ten_pho_option_element = $(this).parent().parent().next().find('.list_duong_pho option');
                        
                        $(ten_pho_option_element).each(function(option) {
                            var ten_pho_value = $(this).val();
                            var ten_pho_text = $(this).text().toLowerCase();
                            after_result_search = dia_chi_text_value.search(ten_pho_text);
                            if(after_result_search >= 0)
                            {
                                $(ten_pho_element).val(ten_pho_value).trigger("change");
                            }
                        })
                    }
                });
            });
        }
    });
</script>
<?php JSRegister::end();?>