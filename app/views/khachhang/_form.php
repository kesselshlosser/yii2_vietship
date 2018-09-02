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
use \app\modules\donhang\models\Donhang;

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
                        
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <label style="padding-left: 0px !important" class='control-label col-md-12'>Gói khách hàng</label>
                        
                        <div class='col-md-12' style="padding-left: 0px !important">
                            <div class='col-md-4' style="padding-left: 0px !important">
                                <input name='gkh_id' id='gkh' class='form-control' placeholder='Nhập mã gói khách hàng'/>
                            </div>
                            
                            <a id='add_gkh' class='btn btn-primary col-md-2'>
                                Xác nhận
                            </a>

                            <div class='col-md-6' id='errorWrapper'>
                                <span id='error' style='height: 34px; line-height: 34px; color: red'></span>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!--Hiển thị chi tiết gói khách hàng-->
                <?php 
                    if (!empty($model->gkh_id)):
                ?>
                    <div class='row'>
                        <?php 
                            $gkh_id = $model->gkh_id;
                            if (gettype($gkh_id) == 'array') {

                            } else {
                                $gkh_id = json_decode($model->gkh_id, true);
                            }
                            $model_gkh = Goikhachhang::find()->where(['gkh_id' => $gkh_id])->asArray()->all();
                            foreach($model_gkh as $item):
                        ?>
                            <div class='col-md-4'>
                                <h3 style='text-align: center; border-bottom: 1px solid black;'><?= $item['ten_goi']?></h3>
                                <p><span style='font-weight: bold'>Hình thức khuyến mại :</span> <span><?= $item['hinh_thuc']?></span></p>
                                <p><span style='font-weight: bold'>Giá trị :</span> <span><?= $item['gia_tri']?> VNĐ</span></p>
                                <?php if($item['chi_giam_dich_vu_phu_troi'] == 1):?>
                                    <p><span style='font-weight: bold'>Áp dụng giảm cho dịch vụ phụ trội</span></p>
                                <?php else:?>
                                    <p><span style='font-weight: bold'>Áp dụng giảm cho toàn bộ tiền cước</span></p>
                                <?php endif;?>
                                <p><span style='font-weight: bold'>Dịch vụ phụ trội :</span></p>
                                <?php
                                    if (isset($item['dich_vu_phu_troi']) && !empty($item['dich_vu_phu_troi'])) {
                                        $dvpt = json_decode($item['dich_vu_phu_troi'], true);
                                        foreach($dvpt as $dvpt)
                                        {
                                            if($dvpt['value'] == 1)
                                            {
                                                echo '<p style="padding-left : 10px">- '.$dvpt['content'].'</p>';
                                            }
                                        }
                                    }
                                ?>
                                <?php
                                    $ngay_gio_status = 0;
                                    if($item['day_ngay_bat_dau'] && $item['day_ngay_ket_thuc'])
                                    {
                                        $ngay_gio_status = 1; //Áp dụng theo ngày
                                    }
                                ?>
                                <?php if($ngay_gio_status == 1):?>
                                <p><span style='font-weight: bold'>Áp dụng theo ngày: </span><span>từ <?= date('d-m-Y', $item['day_ngay_bat_dau'])?> đến <?= date('d-m-Y', $item['day_ngay_ket_thuc'])?></span></p>
                                <?php elseif($ngay_gio_status == 0):?>
                                <p><span style='font-weight: bold'>Áp dụng theo giờ : </span><span>từ <?= date('d-m-Y', $item['hour_thoi_gian_ap_dung'])?> vào lúc <?= $item['hour_gio_ap_dung']?> giờ</span></p>
                                <?php endif;?>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
    
    <?php if (isset($hien_thi_du_no) && $hien_thi_du_no == 1):?>
        <div class='col-md-12 col-xs-12 col-sm-12'>
            <div class='panel'>
                <header class="panel-heading">
                    Dư nợ hiện tại
                    <span class="tools pull-right">
                        <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                        <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                        <a class="close-box fa fa-times" href="javascript:;"></a>
                    </span>
                </header>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                                $tong_so_du = 0;
                                $tong_so_no = 0;
                                $model_dh = Donhang::find()->where(['kh_id' => $kh_id])->asArray()->all();
                                if (count($model_dh) > 0) {
                                    foreach ($model_dh as $key => $dh) {
                                        $so_no = (int)$dh['so_no'];
                                        $tong_so_no += $so_no;
                                    }
                                }
                            ?>

                            <?php
                                $model_dh_no = Donhang::find()
                                ->where(['kh_id' => $kh_id])
                                ->andWhere(['>', 'so_no', 0])
                                ->asArray()->all();
                                $tong_so_no_str = $tong_so_no > 0 ? number_format($tong_so_no, 0, '', ',').' VNĐ' : 0;
                                Modal::begin([
                                    'header'=> '<h3 style="text-align : center;">Số nợ: '.$tong_so_no_str.'</h3>',
                                    'id'    => 'so-no',
                                    'size'  => 'modal-lg',
                                ]);
                            ?>
                                <?php if (count($model_dh_no) > 0):?>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Lý do</th>
                                                <th>Mã vận đơn</th>
                                                <th>Khu vực giao hàng</th>
                                                <th>Phương thức trả ship</th>
                                                <th>Tiền ship</th>
                                                <th>Tiền thu hộ</th>
                                                <th>Thời gian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($model_dh_no as $key => $dh):?>
                                                <tr>
                                                    <td>
                                                        Lý do
                                                    </td>

                                                    <td>
                                                        <?= $dh['ma_don_hang']?>
                                                    </td>

                                                    <td>
                                                        <?php
                                                            if (!empty($dh['nguoi_nhan'])) {
                                                                $nguoi_nhan_json = $dh['nguoi_nhan'];
                                                                $dcgh = json_decode($nguoi_nhan_json, true)['dia_chi_giao_hang'];
                                                                echo $dcgh;
                                                            }
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <?= $dh['hinh_thuc_thanh_toan']?>
                                                    </td>

                                                    <td>
                                                        <?php
                                                            if (isset($dh['tong_tien']) && !empty($dh['tong_tien'])) {
                                                                $tong_tien = $dh['tong_tien'];
                                                                echo $tong_tien > 0 ? number_format($tong_tien, 0, '', ',').' VNĐ' : 0;
                                                            }
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <?php
                                                            if (isset($dh['tien_thu_ho']) && !empty($dh['tien_thu_ho'])) {
                                                                $tien_thu_ho = $dh['tien_thu_ho'];
                                                                echo $tien_thu_ho > 0 ? number_format($tien_thu_ho, 0, '', ',').' VNĐ' : 0;
                                                            }
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <?= date('d/m/Y', $dh['time'])?>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                <?php endif;?>
                            <?php
                                Modal::end();
                            ?>
                            <div class='col-md-6'>
                                <span>Số dư: <?= $tong_so_du > 0 ? number_format($tong_so_du, 0, '', ',').' VNĐ' : 0?></span>
                                <button
                                    data-toggle='modal'
                                    data-target='#so-du'
                                    type="button"
                                    style='margin-left: 8px'
                                    class='btn btn-sm btn-success'
                                >
                                Chi tiết
                                </button>
                            </div>

                            <div class='col-md-6'>
                                <span>Số nợ: <?= $tong_so_no > 0 ? number_format($tong_so_no, 0, '', ',').' VNĐ' : 0?></span>
                                <button
                                    data-toggle='modal'
                                    data-target='#so-no'
                                    type="button"
                                    style='margin-left: 8px'
                                    class='btn btn-sm btn-success'
                                >
                                Chi tiết
                                </button>
                            </div>
                        </div>

                        <div class='col-md-6'>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>

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

    // Xử lý gói khách hàng
    $('#add_gkh').click(e => {
        const gkh = $('#gkh').val();
        if (!gkh) return;
        const kh_id = <?= $kh_id?>;
        const url = '<?= Url::to(['/khachhang/update-goi-khach-hang'])?>';
        const data = {
            gkh: gkh,
            kh_id: kh_id
        }
        $.post(
            url,
            data
        )
        .done((response) => {
            const result = JSON.parse(response);
            const errorCode = result.errorCode;
            $('#error').text(errorCode)
            if (errorCode === 'Gói khách hàng được thêm thành công') {
                setTimeout(() => {
                    location.reload();
                }, 500)
            }
        })
        .fail((err) => {
            console.log(err);
        })
    })
    
</script>
<?php JSRegister::end();?>