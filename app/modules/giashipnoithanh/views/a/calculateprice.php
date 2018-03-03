<?php
$this->title = 'Tính giá ship nội thành';
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\goidichvu\models\Goidichvu;
use app\modules\khuvuc\models\Khuvuc;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use richardfan\widget\JSRegister;
?>
<!--page header start-->
<div class="page-head-wrap">
    <h4 class="margin0">
        <?= $this->title?>
    </h4>
</div>
<!--page header end-->
<div class="ui-content-body">
    <div class="ui-container" style="padding: 10px; background-color: #fff">
        <div class="row">
            <div class="alert alert-success" id="alert-success" style="display: none">
                <strong>Giá ship nội thành :</strong> <span></span>
            </div>
            
            <div class="alert alert-danger" id="alert-error" style="display : none">
                <strong>Rất tiếc</strong> <span>Chưa có giá ship cho khu vực này. Vui lòng liên hệ quản trị để được giúp đỡ.</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <?php $form = ActiveForm::begin([
                    'enableAjaxValidation' => false,
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => 'model-form'
                    ]
                ])?>
                
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
                
                <?= Html::submitButton("Tính giá ship nội thành", ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end();?>
            </div>
        </div>
   </div>
</div>

<?php JSRegister::begin();?>
<script>
    $('form').on('beforeSubmit', function(e){
        var form = $(this);
        var url = form.attr("action");
        var formData = form.serialize();
        $.post(
            url,
            formData
        )
        .done(function(result){
            if(result == -1) //Chưa tạo giá ship cho khu vực này -> alert danger
            {
                $('#alert-error').css({'display' : 'block'});
                $('#alert-success').css({'display' : 'none'});
            }else
            {
                $('#alert-error').css({'display' : 'none'});
                $('#alert-success').css({'display' : 'block'});
                $('#alert-success span').text(result + " VNĐ");
            }
        })
        .fail(function(error){
            console.log(error);
        })
    }).on('submit', function(e){
        e.preventDefault();
    });
</script>
<?php JSRegister::end();?>