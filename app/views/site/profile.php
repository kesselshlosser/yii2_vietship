<?php
use yii\helpers\Html;
use \app\assets\AppAsset;
use yii\captcha\Captcha;
use \yii\bootstrap\ActiveForm;
use \richardfan\widget\JSRegister;
use unclead\multipleinput\MultipleInput;
use \yii\helpers\ArrayHelper;
use \app\modules\duongpho\models\Duongpho;
use \kartik\select2\Select2;
$asset = AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>Đăng nhập vietshipvn.com</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="<?= $asset->baseUrl ?>/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?= $asset->baseUrl ?>/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php $this->head() ?>
        <style>
            .marTop: {
                margin-top: 8px !important
            }
            .wizard {
                margin: 20px auto;
                background: #fff;
            }

            .wizard .nav-tabs {
                position: relative;
                margin: 40px auto;
                margin-bottom: 0;
                border-bottom-color: #e0e0e0;
            }

            .wizard > div.wizard-inner {
                position: relative;
            }

            .connecting-line {
                height: 2px;
                background: #e0e0e0;
                position: absolute;
                width: 80%;
                margin: 0 auto;
                left: 0;
                right: 0;
                top: 50%;
                z-index: 1;
            }

            .wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
                color: #555555;
                cursor: default;
                border: 0;
                border-bottom-color: transparent;
            }

            span.round-tab {
                width: 70px;
                height: 70px;
                line-height: 70px;
                display: inline-block;
                border-radius: 100px;
                background: #fff;
                border: 2px solid #e0e0e0;
                z-index: 2;
                position: absolute;
                left: 0;
                text-align: center;
                font-size: 25px;
            }
            span.round-tab i{
                color:#555555;
            }
            .wizard li.active span.round-tab {
                background: #fff;
                border: 2px solid #5bc0de;
                
            }
            .wizard li.active span.round-tab i{
                color: #5bc0de;
            }

            span.round-tab:hover {
                color: #333;
                border: 2px solid #333;
            }

            .wizard .nav-tabs > li {
                width: 25%;
            }

            .wizard li:after {
                content: " ";
                position: absolute;
                left: 46%;
                opacity: 0;
                margin: 0 auto;
                bottom: 0px;
                border: 5px solid transparent;
                border-bottom-color: #5bc0de;
                transition: 0.1s ease-in-out;
            }

            .wizard li.active:after {
                content: " ";
                position: absolute;
                left: 46%;
                opacity: 1;
                margin: 0 auto;
                bottom: 0px;
                border: 10px solid transparent;
                border-bottom-color: #5bc0de;
            }

            .wizard .nav-tabs > li a {
                width: 70px;
                height: 70px;
                margin: 20px auto;
                border-radius: 100%;
                padding: 0;
            }

                .wizard .nav-tabs > li a:hover {
                    background: transparent;
                }

            .wizard .tab-pane {
                position: relative;
                padding-top: 50px;
            }

            .wizard h3 {
                margin-top: 0;
            }

            @media( max-width : 585px ) {

                .wizard {
                    width: 90%;
                    height: auto !important;
                }

                span.round-tab {
                    font-size: 16px;
                    width: 50px;
                    height: 50px;
                    line-height: 50px;
                }

                .wizard .nav-tabs > li a {
                    width: 50px;
                    height: 50px;
                    line-height: 50px;
                }

                .wizard li.active:after {
                    content: " ";
                    position: absolute;
                    left: 35%;
                }
            }
        </style>
    </head>

    <body>
        <?php $this->beginBody() ?>
            <div class="container">
                <div class="row">
                    <section>
                        <div class="wizard">
                            <div class="wizard-inner">
                                <div class="connecting-line"></div>
                                <ul class="nav nav-tabs" role="tablist">

                                    <li role="presentation" class="active">
                                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-folder-open"></i>
                                            </span>
                                        </a>
                                    </li>

                                    <li role="presentation" class="disabled">
                                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-picture"></i>
                                            </span>
                                        </a>
                                    </li>

                                    <li role="presentation" class="disabled">
                                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-ok"></i>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- <form role="form"> -->
                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel" id="step1">
                                        <div class="alert alert-info">Nhập các thông tin cơ bản (Trường có dấu * là bắt buộc nhập)</div>

                                        <?php $form_khach_hang = ActiveForm::begin([
                                                'enableAjaxValidation' => false,
                                                'options' => [
                                                    'enctype' => 'multipart/form-data',
                                                    'class' => 'model-form',
                                                    'id' => 'form_kh'
                                                ]
                                            ]); ?>
                                            <div class='col-md-7 col-sm-6 col-xs-6'>
                                                <div class='row marTop' style='margin-top: 8px'>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4 col-md-4 col-xs-4">Số điện thoại (*)</label>
                                                        <div class="col-sm-8">
                                                            <?= $form_khach_hang->field($model_khach_hang, 'so_dien_thoai')->textInput(['class' => 'form-control', 'id' => 'so_dien_thoai'])->label(false)?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row marTop' style='margin-top: 8px'>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4 col-md-4 col-xs-4">Tên cá nhân/cửa hàng / công ty (Tên hiển thị) (*)</label>
                                                        <div class="col-sm-8">
                                                            <?= $form_khach_hang->field($model_khach_hang, 'ten_hien_thi')->textInput(['class' => 'form-control', 'id' => 'ten_hien_thi'])->label(false)?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row marTop' style='margin-top: 8px'>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4 col-md-4 col-xs-4">Facebook</label>
                                                        <div class="col-sm-8">
                                                            <?= $form_khach_hang->field($model_khach_hang, 'facebook')->textInput(['class' => 'form-control', 'id' => 'facebook'])->label(false)?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class='col-md-5 col-sm-6 col-xs-6'>
                                                <div class='row marTop' style='margin-top: 8px'>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4 col-md-4 col-xs-4">Địa chỉ (*)</label>
                                                        <div class="col-sm-8">
                                                            <?= $form_khach_hang->field($model_khach_hang, 'dia_chi')->textarea(['class' => 'form-control', 'id' => 'dia_chi', 'rows' => 4])->label(false)?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row marTop' style='margin-top: 8px'>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4 col-md-4 col-xs-4">Website</label>
                                                        <div class="col-sm-8">
                                                            <?= $form_khach_hang->field($model_khach_hang, 'website')->textInput(['class' => 'form-control', 'id' => 'website'])->label(false)?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <ul class="list-inline pull-right" style='margin-top: 18px; padding-right: 12px'>
                                                <li><button type="submit" class="btn btn-primary next-step">Tiếp tục</button></li>
                                            </ul>
                                        <?php ActiveForm::end()?>

                                        
                                    </div>
                                    <div class="tab-pane" role="tabpanel" id="step2">
                                        <div class="alert alert-info">Địa chỉ kho hàng của quý khách, chúng tôi sẽ qua lấy hàng tại địa chỉ này, có thể thêm nhiều kho hàng và chọn kho khi tạo đơn, chọn kho hàng chính bằng cách kéo lên đầu.</div>
                                        
                                        <div class="col-md-12">
                                            <?php $form_dclh = ActiveForm::begin([
                                                'enableAjaxValidation' => false,
                                                'options' => [
                                                    'enctype' => 'multipart/form-data',
                                                    'class' => 'model-form',
                                                    'id' => 'form_dclh'
                                                ]
                                            ]); ?>

                                            <?php
                                                $list_duong_pho = ArrayHelper::map(Duongpho::find()->all(), 'dp_id', 'ten_pho');
                                                echo $form_dclh->field($model_dclh, 'arr_dclh')->widget(MultipleInput::className(), [
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
                                                ])->label(false);
                                            ?>

                                            <ul class="list-inline pull-right">
                                                <li><button type="button" class="btn btn-default prev-step">Quay lại</button></li>
                                                <li><button type="submit" class="btn btn-primary next-step">Tiếp tục</button></li>
                                            </ul>
                                            <?php ActiveForm::end() ?>

                                            
                                        </div>
                                    </div>
                                    <div class="tab-pane" role="tabpanel" id="step3">
                                        <div class="alert alert-info">Chúng tôi sẽ thanh toán tiền hàng cho bạn dựa theo thông tin bạn nhập trong phần này</div>
                                        
                                        <?php $form_httt = ActiveForm::begin([
                                            'enableAjaxValidation' => false,
                                            'options' => [
                                                'enctype' => 'multipart/form-data',
                                                'class' => 'model-form',
                                                'id' => 'form_httt'
                                            ]
                                        ]); ?>

                                        <div class="row">
                                            <div class="col-md-3 col-xs-12 col-sm-12">
                                                <?= $form_httt->field($model_httt, 'hinh_thuc_thanh_toan')->widget(Select2::className(), [
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
                                                    echo $form_httt->field($model_httt, 'arr_ttck')->widget(MultipleInput::className(), [
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
                                                    <?= $form_httt->field($model_httt, 'ten_nguoi_nhan')->textInput(['placeholder' => 'Tên người nhận'])->label(false)?>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12 col-sm-12">
                                                    <?= $form_httt->field($model_httt, 'dia_chi')->textInput(['placeholder' => 'Địa chỉ'])->label(false)?>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12 col-sm-12">
                                                    <?= $form_httt->field($model_httt, 'so_dien_thoai')->textInput(['placeholder' => 'Số điện thoại'])->label(false)?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <?= $form_httt->field($model_httt, 'json_thoi_gian_thanh_toan')->widget(Select2::className(), [
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
                                                <?= $form_httt->field($model_httt, 'thanh_toan_theo_tuan')->textInput(['placeholder' => 'Nhập 1 ngày trong tuần bằng số (Từ thứ 2 đến thứ 7)'])->label(false)?>
                                            </div>
                                        </div>

                                        <ul class="list-inline pull-right">
                                            <li><button type="button" class="btn btn-default prev-step">Quay lại</button></li>
                                            <li><button type="submit" class="btn btn-primary btn-info-full next-step">Kết thúc</button></li>
                                        </ul>
                                        <?php ActiveForm::end()?>
                                    </div>

                                    <div class="tab-pane" role="tabpanel" id="complete">
                                        <h3>Complete</h3>
                                        <p>You have successfully completed all steps.</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            <!-- </form> -->
                        </div>
                    </section>
                </div>
            </div>

            <?php \richardfan\widget\JSRegister::begin()?>
                <script>
                    console.log('aaaaa')
                    // Form khach hang step 1
                    $('#form_kh').on('beforeSubmit', function(e){
                        $('#form_kh').attr('disabled', true);
                        var url = $('#form_kh').attr("action");
                        var email = "<?= $email?>"
                        var formData = $('#form_kh').serialize() + "&smForm=khachhang" + "&email=" + email;
                        $.post(
                            url,
                            formData
                        )
                        .done(function(result){
                            var jsonResult = JSON.parse(result);
                            console.log(jsonResult);
                            if(jsonResult.message == 'success') {
                                var $active = $('.wizard .nav-tabs li.active');
                                $active.next().removeClass('disabled');
                                nextTab($active);
                            }
                            else if(jsonResult.message == 'error') {
                                $('#form_kh').attr('disabled', false);
                            }
                        })
                        .fail(function(error){
                            console.log(error);
                        })
                    }).on('submit', function(e){
                        e.preventDefault();
                    });
                    // Form dia chi lay hang step 2
                    $('#form_dclh').on('beforeSubmit', function(e){
                        $('#form_dclh').attr('disabled', true);
                        var url = $('#form_dclh').attr("action");
                        var email = "<?= $email?>"
                        var formData = $('#form_dclh').serialize() + "&smForm=dclh" + "&email=" + email;
                        $.post(
                            url,
                            formData
                        )
                        .done(function(result){
                            var jsonResult = JSON.parse(result);
                            console.log(jsonResult);
                            if(jsonResult.message == 'success') {
                                var $active = $('.wizard .nav-tabs li.active');
                                $active.next().removeClass('disabled');
                                nextTab($active);
                            }
                            else if(jsonResult.message == 'error') {
                                $('#form_kh').attr('disabled', false);
                            }
                        })
                        .fail(function(error){
                            console.log(error);
                        })
                    }).on('submit', function(e){
                        e.preventDefault();
                    });

                    // Form hinh thuc thanh toan step 3
                    $('#form_httt').on('beforeSubmit', function(e){
                        $('#form_httt').attr('disabled', true);
                        var url = $('#form_httt').attr("action");
                        var email = "<?= $email?>"
                        var formData = $('#form_httt').serialize() + "&smForm=httt" + "&email=" + email;
                        $.post(
                            url,
                            formData
                        )
                        .done(function(result){
                            var jsonResult = JSON.parse(result);
                            console.log(jsonResult);
                            if(jsonResult.message == 'success') {
                                var $active = $('.wizard .nav-tabs li.active');
                                $active.next().removeClass('disabled');
                                nextTab($active);
                            }
                            else if(jsonResult.message == 'error') {
                                $('#form_kh').attr('disabled', false);
                            }
                        })
                        .fail(function(error){
                            console.log(error);
                        })
                    }).on('submit', function(e){
                        e.preventDefault();
                    });

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

                    //Initialize tooltips
                    $('.nav-tabs > li a[title]').tooltip();
                    
                    //Wizard
                    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

                        var $target = $(e.target);
                    
                        if ($target.parent().hasClass('disabled')) {
                            return false;
                        }
                    });

                    // $(".next-step").click(function (e) {

                    //     var $active = $('.wizard .nav-tabs li.active');
                    //     $active.next().removeClass('disabled');
                    //     nextTab($active);

                    // });
                    $(".prev-step").click(function (e) {

                        var $active = $('.wizard .nav-tabs li.active');
                        prevTab($active);

                    });

                    function nextTab(elem) {
                        $(elem).next().find('a[data-toggle="tab"]').click();
                    }
                    function prevTab(elem) {
                        $(elem).prev().find('a[data-toggle="tab"]').click();
                    }
                </script>
            <?php \richardfan\widget\JSRegister::end()?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>