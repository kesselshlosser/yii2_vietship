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
use kartik\depdrop\DepDrop;
use app\modules\duongpho\models\Duongpho;
use app\modules\coupon\models\Coupon;

$module = $this->context->module->id;
?>
<style>
    .form-group {
        margin-bottom: 5px !important;
    }
</style>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
                <header class="panel-heading">
                    Sửa đơn hàng <?= $model->ma_don_hang;?>
                    <span class="tools pull-right">
                        <!--<a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>-->
                    </span>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p style="color: red">Lưu ý: Những nội dung có dấu * là yêu cầu bắt buộc nhập</p>
                            <p style="padding-left: 10px; color : red">Bạn cần phải chọn kho hàng trước khi tạo đơn hàng mới</p>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p style="color: red">Chúng tôi sẽ chủ động thêm phụ phí hoặc thay đổi thông tin đơn hàng trong các trường hợp sau:</p>
                            <p style="padding-left: 10px">- Đơn hàng thực tế có các yêu cầu nằm trong hạng mục dịch vụ phụ trội</p>
                            <p style="padding-left: 10px">- Đơn hàng có địa chỉ giao thực tế khác với địa chỉ giao mà bạn chọn</p>
                            <p style="padding-left: 10px">- Thời gian yêu cầu giao thực tế khác với gói cước bạn chọn</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12 col-sm-6" style="margin-bottom: 5px">
                            <?php
                                echo Select2::widget(
                                    [
                                        'model' => $model,
                                        'name' => 'kh_id',
                                        'value' => $model->kh_id,
                                        'data' => ArrayHelper::map(Khachhang::find()->all(), 'kh_id', 'ten_hien_thi'),
                                        'options' => [
                                            'placeholder' => 'Chọn khách hàng *',
                                            'id'=>'kh-id',
                                            'disabled' => true
                                        ],
                                        'pluginOptions' => [
                                        ],
                                    ]
                                )
                            ?>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                                echo DepDrop::widget(
                                    [
                                        'model' => $model,
                                        'name' => 'dia_chi_lay_hang',
                                        'type' => DepDrop::TYPE_SELECT2,
                                        'options' => 
                                        [ 
                                            'id' => 'dclh-id',
                                            'disabled' => true
                                        ],
                                        'pluginOptions'=>[
                                            'depends'=>[ 'kh-id' ],
                                            'placeholder'=>'Chọn kho hàng *',
                                            'url'=>Url::to(['/admin/donhang/a/subcatedit/'.$model->dclh_id]),
                                            'initialize' => true
                                        ]
                                    ]);
                            ?>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px;" id="wrapper-multi-order">
                        <div class="col-md-12 col-sm-12 col-xs-12 wrapper-form" id="wrapper-form0">
                            <?php $form = ActiveForm::begin([
                                'enableAjaxValidation' => false,
                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form', 'id' => 'form0']
                            ]); ?>
                                <input type="hidden" class = "order_index" value="0"/>
                                <div class="panel panel-primary">
                                    <header class="panel-heading">
                                        Đơn hàng <?= $model->ma_don_hang;?>
                                        <span class="tools pull-right">
                                            <a class="form-collapse fa fa-chevron-down" href="javascript:;"></a>
                                            <a class="fa fa-times remove" href="javascript:;"></a>
                                        </span>
                                    </header>
                                    <div class="panel-body panel-body-form" style="padding: 0px 0px 10px 0px">
                                        <div  id="form0-alert-success" style="background-color: #dff0d8; color: #3c763d; border-color: #d6e9c6; padding: 10px; margin: 10px; border : 1px solid transparent; border-radius: 4px; display : none;">
                                            <p style='font-size : 18px; font-weight: bold'>Sửa đơn hàng thành công</p>
                                            <p id='form0-ma-don-hang'></p>
                                            <a id='form0-link-print' href='#' class='btn btn-success'>In đơn hàng</a>
                                        </div>
                                        <div  id="form0-alert-error" style="background-color: #f2dede; color: #a94442; border-color: #ebccd1; padding: 10px; margin: 10px; border : 1px solid transparent; border-radius: 4px; display : none;">
                                            <p style='font-size : 18px; font-weight: bold'>Có lỗi trong quá trình sửa đơn hàng</p>
                                            <p>Xin quý khách vui lòng kiểm tra lại thông tin</p>
                                        </div>
                                        <br>
                                        <div class="col-md-2" style="padding-right: 5px">
                                            <div>
                                                <?= $form->field($model, 'nguoi_nhan_ten')->textInput(['placeholder' => 'Người nhận'])->label(false)?>
                                            </div>

                                            <div>
                                                <?= $form->field($model, 'nguoi_nhan_dia_chi_giao_hang')->textInput(['placeholder' => 'Địa chỉ giao hàng*', 'class' => 'form-control nguoi_nhan_dia_chi_giao_hang'])->label(false)?>
                                            </div>

                                            <div>
                                                <?= $form->field($model, 'nguoi_nhan_so_dien_thoai')->textInput(['placeholder' => 'Số điện thoại*'])->label(false)?>
                                            </div>

                                            <div>
                                                <?= $form->field($model, 'san_pham_ten')->textInput(['placeholder' => 'Tên hàng hóa'])->label(false)?>
                                            </div>

                                            <div>
                                                <?= $form->field($model, 'san_pham_so_luong')->textInput(['placeholder' => 'Số lượng', 'id' => 'form0-so-luong'])->label(false)?>
                                            </div>
                                        </div>

                                        <div class="col-md-5" style="padding-right: 5px">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12 wrapper-pgh-gdv">
                                                    <div>
                                                        <?= 
                                                            $form->field($model, 'pho_giao_hang')->widget(Select2::className(), [
                                                                'data' => ArrayHelper::map(Duongpho::find()->all(), 'dp_id', 'ten_pho'),
                                                                'options' => [
                                                                    'placeholder' => 'Phố giao hàng*',
                                                                    'id' => 'form0-pho_giao_hang',
                                                                    'class' => 'form-control pho-giao-hang'
                                                                ],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ]
                                                            ])->label(false);
                                                        ?>
                                                    </div>

                                                    <div>
                                                        <?= 
                                                            $form->field($model, 'gdv_id')->widget(Select2::className(), [
                                                                'data' => ArrayHelper::map(Goidichvu::find()->all(), 'gdv_id', 'ten_goi_dich_vu'),
                                                                'options' => [
                                                                    'placeholder' => 'Gói dịch vụ*',
                                                                    'id' => 'form0-goi_dich_vu',
                                                                    'class' => 'form-control goi-dich-vu'
                                                                ],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ]
                                                            ])->label(false);
                                                        ?>
                                                    </div>

                                                    <div>
                                                        <?= 
                                                            $form->field($model, 'hinh_thuc_thanh_toan')->widget(Select2::className(), [
                                                                'data' => [
                                                                    'Người gửi thanh toán' => 'Người gửi thanh toán',
                                                                    'Người nhận thanh toán'=> 'Người nhận thanh toán',
                                                                    'Thanh toán sau COD' => 'Thanh toán sau COD',
                                                                    'Thanh toán sau' => 'Thanh toán sau'
                                                                ],
                                                                'options' => [
                                                                    'placeholder' => 'Hình thức thanh toán*',
                                                                    'id' => 'form0-hinh_thuc_thanh_toan',
                                                                    'class' => 'hinh-thuc-thanh-toan'
                                                                ],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ]
                                                            ])->label(false);
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0px">
                                                    <legend style="text-align: center; font-weight: bold;"><small>Dịch vụ phụ trội</small></legend>
                                                    <?php foreach($model->dvpt as $item):?>
                                                        <div class="form-group has-success wrapper-dvpt">
                                                            <label class="cbx-label" for="<?= 'form0-'.$item['key']?>">
                                                            <?= CheckboxX::widget([
                                                                'name' => 'dvpt['.$item['key'].']',
                                                                'value' => $item['value'],
                                                                'initInputType' => CheckboxX::INPUT_CHECKBOX,
                                                                'options'=>[
                                                                    'id' => 'form0-'.$item['key'],
                                                                    'class' => $item['key']
                                                                ],
                                                                'pluginOptions' => [
                                                                    'size' => 'xs',
                                                                    'theme' => 'krajee-flatblue',
                                                                    'enclosedLabel' => true,
                                                                    'threeState' => false
                                                                ]
                                                            ]); ?>
                                                            <span style="font-size: 13px"><?= $item['content']?></span>
                                                            </label>

                                                            <?php if($item['key'] == 'dvpt1'):?>
                                                                <div class="form-group dvpt1-ghi-chu" style="margin-top: 5px; display : none">
                                                                    <?= $form->field($model, 'dvpt1_ghi_chu')->textarea(['rows' => 2, 'placeholder' => 'VD : Giao 3 đổi cho khách chọn 1', 'class' => 'form-control input-ghi-chu-dvpt1'])->label(False)?>
                                                                </div>
                                                            <?php elseif($item['key'] == 'dvpt2'):?>
                                                                <div class="form-group dvpt2-ghi-chu" style="margin-top: 5px; display : none">
                                                                    <?= $form->field($model, 'dvpt2_gio_giao')->textInput(['placeholder' => 'Điền giờ giao', 'class' => 'form-control input-ghi-chu-dvpt2'])->label(False)?>
                                                                </div>
                                                            <?php elseif($item['key'] == 'dvpt4'):?>
                                                                <div class="form-group dvpt4-ghi-chu" style="margin-top: 5px; display : none">
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right: 0px">
                                                                            <?= $form->field($model, 'dvpt4_dai')->textInput(['class' => 'form-control input-dai-dvpt4'])?>
                                                                        </div>

                                                                        <div class="col-md-4" style="padding-right: 0px; padding-left: 5px">
                                                                            <?= $form->field($model, 'dvpt4_rong')->textInput(['class' => 'form-control input-rong-dvpt4'])?>
                                                                        </div>

                                                                        <div class="col-md-4" style="padding-left: 5px">
                                                                            <?= $form->field($model, 'dvpt4_cao')->textInput(['class' => 'form-control input-cao-dvpt4'])?>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <?= $form->field($model, 'dvpt4_can_nang')->textInput(['class' => 'form-control input-nang-dvpt4'])?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif;?>
                                                        </div>
                                                    <?php endforeach;?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'ghi_chu')->textarea(['placeholder' => 'Ghi chú', 'rows' => 4])->label(false)?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5" style="padding-right: 5px">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'tien_thu_ho')->textInput(['placeholder' => 'Tiền thu hộ', 'id' => 'form0-tien_thu_ho'])->label(FALSE)?>
                                                </div>

                                                <div class="col-md-12" id='form0-wrapper-ung_tien'>
                                                    <?php
                                                        if($model->ung_tien)
                                                        {
                                                            echo $form->field($model, 'ung_tien')->widget(CheckboxX::classname(), [
                                                                'autoLabel'=>true,
                                                                'value' => $model->ung_tien,
                                                                'options' => [
                                                                    'id' => 'form0-ung_tien'
                                                                ],
                                                                'pluginOptions'=>[
                                                                    'threeState' => false,
                                                                    'size' => 'sm'
                                                                ]
                                                            ])->label(false);
                                                        }else
                                                        {
                                                            echo $form->field($model, 'ung_tien')->widget(CheckboxX::classname(), [
                                                                'autoLabel'=>true,
                                                                'options' => [
                                                                    'id' => 'form0-ung_tien'
                                                                ],
                                                                'pluginOptions'=>[
                                                                    'threeState' => false,
                                                                    'size' => 'sm'
                                                                ]
                                                            ])->label(false);
                                                        }
                                                    ?>
                                                </div>

                                                <div class="col-md-12" id='form0-wrapper-lay_hang_ve'>
                                                    <?php
                                                        if($model->lay_hang_ve)
                                                        {
                                                            echo $form->field($model, 'lay_hang_ve')->widget(CheckboxX::classname(), [
                                                                'autoLabel'=>true,
                                                                'value' => $model->lay_hang_ve,
                                                                'options' => [
                                                                    'id' => 'form0-lay_hang_ve'
                                                                ],
                                                                'pluginOptions'=>[
                                                                    'threeState' => false,
                                                                    'size' => 'sm'
                                                                ]
                                                            ])->label(false);
                                                        }else
                                                        {
                                                            echo $form->field($model, 'lay_hang_ve')->widget(CheckboxX::classname(), [
                                                                'autoLabel'=>true,
                                                                'options' => [
                                                                    'id' => 'form0-lay_hang_ve'
                                                                ],
                                                                'pluginOptions'=>[
                                                                    'threeState' => false,
                                                                    'size' => 'sm'
                                                                ]
                                                            ])->label(false);
                                                        }
                                                    ?>
                                                </div>

                                                <div class="col-md-12" id="wrapper0-coupon">
                                                    <div>
                                                        <?= 
                                                            $form->field($model, 'cp_id')->widget(Select2::className(), [
                                                                'data' => ArrayHelper::map(Coupon::find()
                                                                        ->where(['status' => 1])
                                                                        ->andWhere(['<=', 'ngay_bat_dau', time()])
                                                                        ->andWhere(['>=', 'ngay_ket_thuc', time()])
                                                                        ->all(), 'cp_id', 'ma_coupon'),
                                                                'options' => [
                                                                    'placeholder' => 'Mã giảm giá',
                                                                    'id' => 'form0-coupon',
                                                                    'class' => 'coupon',
                                                                    'disabled' => true
                                                                ],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ]
                                                            ])->label(False);
                                                        ?>
                                                    </div>
                                                    <div id = 'form0-cp-error'>
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <?=
                                                       $form->field($model, 'tong_tien')->textInput(['placeholder' => '0 VNĐ', 'style' => 'border: 1px dashed red; background-color: #ffe0c4', 'readonly' => true, 'id' => 'form0-tong-tien'])
                                                    ?>
                                                </div>

                                                <div class="col-md-12" style="padding-left : 0px ; padding-right: 0px">
                                                    <div class="col-md-12" id="form0-ghi-chu-httt" style="border-bottom: 1px dashed #ddd; display: none; color : blue">

                                                    </div>

                                                    <div class="col-md-12" id="form0-ghi-chu-thoi-gian-ship" style="color: red; display: none">

                                                    </div>

                                                    <div class="col-md-12" id="form0-ghi-chu-luu-y" style="display : none">
                                                        <p>Lưu ý : Chọn gói cước phù hợp với thời gian giao nhận.</p>
                                                        <p>Nếu thời gian trên không đúng thời gian yêu cầu vui lòng đổi gói cước.</p>
                                                        <p>Xem quy định về thời gian giao nhận <a href="http://vietshipvn.com/bang-gia-noi-thanh" style="color : blue" target="_blank">ở đây</a></p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <?= Html::submitButton('Lưu đơn hàng', ['class' => 'btn btn-success btn-block btn-lg', 'id' => 'form0-submit'])?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php ActiveForm::end();?>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<?php JSRegister::begin();?>
<script>
    //Khai báo biến
    var arr_remove_order = {};
    var arr_dvpt = [];
    arr_dvpt[0] = {
        dvpt1 : {
            key : 'dvpt1',
            content : 'Giao hàng mẫu, đổi hàng',
            value : 0,
            note : ''
        },
        dvpt2 : {
            key : 'dvpt2',
            content : 'Hẹn giờ giao, giao sau giờ hành chính',
            value : 0,
            note : ''
        },
        dvpt3 : {
            key : 'dvpt3',
            content : 'Giao bến xe, văn phòng xe',
            value : 0,
            note : ''
        },
        dvpt4 : {
            key : 'dvpt4',
            content : 'Hàng quá khổ',
            value : 0,
            note : {
                dai : 0,
                rong: 0,
                cao : 0,
                nang : 0
            }
        }
    }
    
    //Lấy ra hình thức thanh toán ghi chú
    var htttdf = '<?= $model->hinh_thuc_thanh_toan?>';
    var htttGhiChuDefault = '';
    if(htttdf == 'Người gửi thanh toán')
    {
        htttGhiChuDefault = 'Người gửi thanh toán: Cước được thu trực tiếp khi lấy hàng';
    }
    else if(htttdf == 'Người nhận thanh toán')
    {
        htttGhiChuDefault = 'Người nhận thanh toán: Cước được thu trực tiếp khi giao hàng';
    }
    else if(htttdf == 'Thanh toán sau COD')
    {
        htttGhiChuDefault = 'Thanh toán sau COD: Cước sẽ được trừ trước khi thanh toán tiền thu hộ';
    }
    else if(htttdf == 'Thanh toán sau')
    {
        htttGhiChuDefault = 'Thanh toán sau: Cước sẽ cộng vào số nợ';
    }

    $('#form0-ghi-chu-httt').html(htttGhiChuDefault);
    $('#form0-ghi-chu-httt').show();
    
    //Lấy ra thời gian ship
    thongbaothoigianship(0, <?= $model->gdv_id?>);
    
    <?php foreach($model->dvpt as $item):?>
        <?php if($item['value'] == 1):?>
            arr_dvpt[0].<?= $item['key']?>.value = 1;
            <?php if($item['key'] == 'dvpt4'):?>
                arr_dvpt[0].<?= $item['key']?>.note.dai = <?= $item['note']['dai'];?>;
                arr_dvpt[0].<?= $item['key']?>.note.rong = <?= $item['note']['rong'];?>;
                arr_dvpt[0].<?= $item['key']?>.note.cao = <?= $item['note']['cao'];?>;
                arr_dvpt[0].<?= $item['key']?>.note.nang = <?= $item['note']['nang'];?>;
            <?php else:?>
                arr_dvpt[0].<?= $item['key']?>.note = '<?= $item['note']?>';
            <?php endif;?>
        <?php endif;?>    
    <?php endforeach;?>
    //Hiển thị dịch vụ phụ trội ghi chú
    var dvpt1_chk = $('#form0-dvpt1').val();
    var dvpt2_chk = $('#form0-dvpt2').val();
    var dvpt4_chk = $('#form0-dvpt4').val();
    
    if(dvpt1_chk == 1)
    {
        $('.dvpt1-ghi-chu').show();
    }
    if(dvpt2_chk == 1)
    {
        $('.dvpt2-ghi-chu').show();
    }
    if(dvpt4_chk == 1)
    {
        $('.dvpt1-ghi-chu').show();
    }
    
    //Xử lý togger đơn hàng
    jQuery(document).on('click', '.fa-chevron-down', function(e){
        e.preventDefault();
        var panel_body = $(e.target).parent().parent().parent().find('.panel-body');
        //Display none panel_body
        panel_body.attr('style', 'display : none');
        //Change fa-chevron-down to fa-chevron-up
        $(e.target).removeClass('fa-chevron-down');
        $(e.target).addClass('fa-chevron-up');
    })
    
    jQuery(document).on('click', '.fa-chevron-up', function(e){
        e.preventDefault();
        var panel_body = $(e.target).parent().parent().parent().find('.panel-body');
        //Display block panel_body
        panel_body.attr('style', 'display : block');
        //Change fa-chevron-up to fa-chevron-down
        $(e.target).removeClass('fa-chevron-up');
        $(e.target).addClass('fa-chevron-down');
    })
    //End toggle đơn hàng
    
    //Tạo sự kiện nhập địa chỉ ở class = nguoi_nhan_dia_chi_giao_hang sẽ tự động cập nhật phố ở ô phố giao hàng id = form0-pho_giao_hang
    $('.nguoi_nhan_dia_chi_giao_hang').blur((e) => {
        var nguoi_nhan_dcgh = $(e.target).val().toLowerCase();
        var order_index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        var pho_giao_hang_option = $('#form'+order_index+'-pho_giao_hang option');
        pho_giao_hang_option.each(function(option){
            var pho_giao_hang_option_value = $(this).val();
            var pho_giao_hang_option_text = $(this).text().toLowerCase();
            var result_search = nguoi_nhan_dcgh.search(pho_giao_hang_option_text);
            var beforeResultCharacter = nguoi_nhan_dcgh.substring(result_search-1, result_search);
            if((result_search >= 0 && beforeResultCharacter == ' ') || result_search == 0)
            {
                $('#form'+order_index+'-pho_giao_hang').val(pho_giao_hang_option_value).trigger('change');
            }
        })
    })
    //End sự kiện địa chỉ
    
    //Show hide dịch vụ phụ trội
    $('.dvpt1').change((e) => {
        var dvpt1_value = $(e.target).val();
        var dvpt1_ghi_chu = $(e.target).parents('.wrapper-dvpt').find('.dvpt1-ghi-chu');
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        
        if(dvpt1_value == 1) //Hiển thị ghi chú
        {
            dvpt1_ghi_chu.show();
            //Cập nhật lại object dvpt1
            arr_dvpt[index].dvpt1.value = 1;
        }else if(dvpt1_value == 0)
        {
            dvpt1_ghi_chu.hide();
            arr_dvpt[index].dvpt1.value = 0;
        }
        
        //Cập nhật giá
        tinhtientudong(index);
    })
    
    $('.dvpt2').change((e) => {
        var dvpt2_value = $(e.target).val();
        var dvpt2_ghi_chu = $(e.target).parents('.wrapper-dvpt').find('.dvpt2-ghi-chu');
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        if(dvpt2_value == 1) //Hiển thị ghi chú
        {
            dvpt2_ghi_chu.show();
            arr_dvpt[index].dvpt2.value = 1;
        }else if(dvpt2_value == 0)
        {
            dvpt2_ghi_chu.hide();
            arr_dvpt[index].dvpt2.value = 0;
        }
        
        //Cập nhật giá
        tinhtientudong(index);
    })
    
    $('.dvpt3').change((e) => {
        var dvpt3_value = $(e.target).val();
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        if(dvpt3_value == 1) //Hiển thị ghi chú
        {
            arr_dvpt[index].dvpt3.value = 1;
        }else if(dvpt3_value == 0)
        {
            arr_dvpt[index].dvpt3.value = 0;
        }
        
        //Cập nhật giá
        tinhtientudong(index);
    })
    
    $('.dvpt4').change((e) => {
        var dvpt4_value = $(e.target).val();
        var dvpt4_ghi_chu = $(e.target).parents('.wrapper-dvpt').find('.dvpt4-ghi-chu');
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        if(dvpt4_value == 1) //Hiển thị ghi chú
        {
            dvpt4_ghi_chu.show();
            arr_dvpt[index].dvpt4.value = 1;
        }else if(dvpt4_value == 0)
        {
            dvpt4_ghi_chu.hide();
            arr_dvpt[index].dvpt4.value = 0;
        }
        
        //Cập nhật giá
        tinhtientudong(index);
    })
    //End dvpt
    
    //Cập nhật ghi chu
    $('.input-ghi-chu-dvpt1').change((e) => {
        var dvpt1_note = $(e.target).val();
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        arr_dvpt[index].dvpt1.note = dvpt1_note;
    })
    
    $('.input-ghi-chu-dvpt2').change((e) => {
        var dvpt2_note = $(e.target).val();
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        arr_dvpt[index].dvpt2.note = dvpt2_note;
    })
    
    $('.input-dai-dvpt4').change((e) => {
        var dvpt4_note_dai = $(e.target).val();
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        arr_dvpt[index].dvpt4.note.dai = dvpt4_note_dai;
        //Cập nhật giá
        tinhtientudong(index);
    })
    
    $('.input-rong-dvpt4').change((e) => {
        var dvpt4_note_rong = $(e.target).val();
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        arr_dvpt[index].dvpt4.note.rong = dvpt4_note_rong;
        //Cập nhật giá
        tinhtientudong(index);
    })
    
    $('.input-cao-dvpt4').change((e) => {
        var dvpt4_note_cao = $(e.target).val();
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        arr_dvpt[index].dvpt4.note.cao = dvpt4_note_cao;
        //Cập nhật giá
        tinhtientudong(index);
    })
    
    $('.input-nang-dvpt4').change((e) => {
        var dvpt4_note_nang = $(e.target).val();
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        arr_dvpt[index].dvpt4.note.nang = dvpt4_note_nang;
        //Cập nhật giá
        tinhtientudong(index);
    })
    
    //End cập nhật ghi chú
    
    //Function tính tiền tự động
    function tinhtientudong(index)
    {
        var kvl_id = $('#dclh-id').val();
        var kh_id = $('#kh-id').val();
        var url = '<?= Url::to(['/admin/donhang/a/tinh-tien-tu-dong'])?>';
        $('#form0-coupon').removeAttr('disabled');
        var data = $('#form'+index).serialize() + "&kvl_id=" + kvl_id + "&kh_id=" + kh_id + "&dvpt=" + JSON.stringify(arr_dvpt[index]);
        $('#form0-coupon').attr('disabled', 'disabled');
        $.post(
            url,
            data
        )
        .done((response) => {
            //Cập nhật giá cho đơn hàng
            var result = JSON.parse(response); 
            var tongTien = result.tongTien;
            var couponMessage = result.couponMessage;
            var tangTienMessage = result.tangTienMessage;
            if(tongTien > 0)
            {
                $('#form'+index+'-tong-tien').val(tongTien);
            }else
            {
                var error = "Vui lòng liên hệ admin để biết giá ship";
                $('#form'+index+'-tong-tien').val(error);
            }
            
            //Tìm thằng cha của nó id = wrapper+index+-coupon -> Gán giá trị cho help-block error
            var errorElement = $('#form'+index+'-cp-error');
            if(couponMessage)
            {
                $(errorElement).text(couponMessage);
                $(errorElement).css({'color' : 'red'});
            }else
            {
                if(tangTienMessage)
                {
                    $(errorElement).text(tangTienMessage);
                    $(errorElement).css({'color' : 'green'});
                }else
                {
                    $(errorElement).text('');
                }
            }
        })
        .fail((e) => {
            console.log(e);
        })
    }
    //End function tính tiền tự động
    
    //Function thông báo thời gian giao hàng và lấy hàng
    function thongbaothoigianship(index, gdv_id)
    {
        var kvl_id = $('#dclh-id').val();
        var kvg_id = $('#form'+index+'-pho_giao_hang').val();
        if(kvl_id && kvg_id)
        {
            var dataObject = {
                gdv_id : gdv_id
            }
            var url = '<?= Url::to(['/admin/donhang/a/thong-bao-thoi-gian-ship'])?>';
            var data = dataObject;
            $.post(
                url,
                data
            )
            .done((response) => {
                $('#form'+index+'-ghi-chu-thoi-gian-ship').html(response);
                $('#form'+index+'-ghi-chu-thoi-gian-ship').show();
                $('#form'+index+'-ghi-chu-luu-y').show();
            })
            .fail((e) => {
                console.log(e);
            })
        }else
        {
            //console.log('Chưa có khu vực giao hoặc khu vực lấy');
        }
        
    }
    
    //Phố giao hàng change
    $('.pho-giao-hang').change((e) => {
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        var gdv_id = $('#form'+index+'-goi_dich_vu').val();
        var kvl_id = $('#dclh-id').val();
        var kh_id = $('#kh-id').val();
        if(gdv_id && kvl_id && kh_id)
        {
            tinhtientudong(index);
        }else
        {
            console.log("Chưa chọn gói dịch vụ hoặc nơi lấy hàng hoặc khách hàng");
        }
    })
    
    //Gói dịch vụ change
    $('.goi-dich-vu').change((e) => {
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        var gdv_id = $(e.target).val();
        var kvg_id = $('#form'+index+'-pho_giao_hang').val();
        var kvl_id = $('#dclh-id').val();
        var kh_id = $('#kh-id').val();
        if(kvg_id && kvl_id && kh_id)
        {
            tinhtientudong(index);
        }else
        {
            console.log("Chưa chọn nơi lấy hàng hoặc giao hàng hoặc khách hàng");
        }
        tinhtientudong(index);
        
        //Thông báo về thời gian giao hàng và lấy hàng
        thongbaothoigianship(index, gdv_id);
    })
    //Hình thức thanh toán change
    $('.hinh-thuc-thanh-toan').change((e) => {
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        var httt = $(e.target).val();
        var htttGhiChu = '';
        if(httt == 'Người gửi thanh toán')
        {
            htttGhiChu = 'Người gửi thanh toán: Cước được thu trực tiếp khi lấy hàng';
        }
        else if(httt == 'Người nhận thanh toán')
        {
            htttGhiChu = 'Người nhận thanh toán: Cước được thu trực tiếp khi giao hàng';
        }
        else if(httt == 'Thanh toán sau COD')
        {
            htttGhiChu = 'Thanh toán sau COD: Cước sẽ được trừ trước khi thanh toán tiền thu hộ';
        }
        else if(httt == 'Thanh toán sau')
        {
            htttGhiChu = 'Thanh toán sau: Cước sẽ cộng vào số nợ';
        }
        
        $('#form'+index+'-ghi-chu-httt').html(htttGhiChu);
        $('#form'+index+'-ghi-chu-httt').show();
    })
    
    //Coupon change
    $('.coupon').change((e) => {
        var index = $(e.target).parents('.wrapper-form').find('.order_index').val();
        var gdv_id = $('#form'+index+'-goi_dich_vu').val();
        var kvg_id = $('#form'+index+'-pho_giao_hang').val();
        var kvl_id = $('#dclh-id').val();
        var kh_id = $('#kh-id').val();
        if(gdv_id && kvg_id && kvl_id && kh_id)
        {
            tinhtientudong(index);
        }
    })
    
    //Địa chỉ lấy hàng change
    $('#dclh-id').change((e) => {
        var kvl_id = $(e.target).val();
        var kh_id = $('#kh-id').val();
        $('.wrapper-form').each(function(index, el) {
            if($(el).is(':visible'))
            {
                var index = $(el).find('.order_index').val();
                var gdv_id = $('#form'+index+'-goi_dich_vu').val();
                var kvg_id = $('#form'+index+'-pho_giao_hang').val();
                if(gdv_id && kvg_id && kh_id)
                {
                    tinhtientudong(index);
                }
            }
        })
        if(kvl_id)
        {
            $('#add-new-order').attr('disabled', false);
            $('#wrapper-multi-order').show();
        }
    })
    
    //Submit form
    $('form').on('beforeSubmit', function(e){
        var kvl_id = $('#dclh-id').val();
        var kh_id = $('#kh-id').val()
        var dia_chi_lay_hang = $('#dclh-id option:selected').text();
        var form = $(this);
        var index = $(this).find('.order_index').val();
        //Disable button submit
        $('#form'+index+'-submit').attr('disabled', true);
        var url = form.attr("action");
        $('#form0-coupon').removeAttr('disabled');
        var formData = form.serialize() + "&kvl_id=" + kvl_id + "&dia_chi_lay_hang=" + dia_chi_lay_hang + "&kh_id=" + kh_id + "&dvpt=" + JSON.stringify(arr_dvpt[index]);
        $('#form0-coupon').attr('disabled', 'disabled');
        $.post(
            url,
            formData
        )
        .done(function(result){
            var jsonResult = JSON.parse(result);
            
            console.log(jsonResult);
            if(jsonResult.message == 'success')
            {
                var ma_don_hang = jsonResult.data.ma_don_hang;
                var id = jsonResult.data.id;
                //Disable form
                $('#form'+index).find('input, textarea, select, button, checkbox').attr('disabled', 'disabled');
                $('#form'+index+'-wrapper-ung_tien').children().children().children('div').attr('style', 'pointer-events: none');
                $('#form'+index+'-wrapper-lay_hang_ve').children().children().children('div').attr('style', 'pointer-events: none');
                //Thêm mã đơn hàng
                $('#form'+index+'-ma-don-hang').text("Mã đơn hàng : "+ma_don_hang);
                //Thêm link sửa và in
                var print_url = '<?= Url::to(['/admin/donhang/a/print'])?>'+'/'+id;
                $('#form'+index+'-link-print').attr('href', print_url);
                $('#form'+index+'-link-print').attr('target', '_blank');
                //Show alert
                $('#form'+index+'-alert-success').show();
                $('#form'+index+'-alert-error').hide();
            }
            else if(jsonResult.message == 'error')
            {
                $('#form'+index+'-alert-success').hide();
                $('#form'+index+'-alert-error').show();
            }
            //var json_result = JSON.parse(result);
            //console.log(json_result);
        })
        .fail(function(error){
            console.log(error);
        })
    }).on('submit', function(e){
        e.preventDefault();
    });
</script>
<?php JSRegister::end();?>