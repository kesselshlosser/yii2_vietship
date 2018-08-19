<?php
$this->title = 'Tính giá ship nội thành';
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\goidichvu\models\Goidichvu;
use app\modules\khuvuc\models\Khuvuc;
use \app\modules\duongpho\models\Duongpho;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use richardfan\widget\JSRegister;
use \yii\easyii\models\Diachilayhang;
?>

<style>
    .fontBold {
        font-weight: bold;
    }
</style>

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

        <!--Hiển thị thông tin về giá ship-->
            <div class="col-md-12 col-xs-12 col-sm-12" style='border-style: dotted;'>
                <p style='margin-top: 16px'>
                    <span class='fontBold'>Nơi lấy: </span>
                    <span class='tt-noi-lay'></span>
                </p>

                <p>
                    <span class='fontBold'>Nơi giao: </span>
                    <span class='tt-noi-giao'></span>
                </p>

                <p>
                    <span class='fontBold'>Giá: </span>
                    <span class='tt-gia'></span>
                </p>

                <p>
                    <span class='fontBold'>Thời gian: (Hiện tại <?= date('H:i d/m/Y')?>)</span>
                </p>

                <p>
                    <span class='tt-thoi-gian-ship'></span>
                </p>

                <p>
                    <span>Xem quy định về thời gian giao nhận</span>
                     <a href="http://vietshipvn.com/bang-gia-noi-thanh" style="color : blue" target="_blank">ở đây</a>
                </p>
            </div>
        <!--End Hiển thị thông tin về giá ship-->

        <!--Form tính giá ship-->
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12" style='margin-top: 16px'>
                <?php $form = ActiveForm::begin([
                    'enableAjaxValidation' => false,
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => 'model-form'
                    ]
                ])?>
                
                <?php
                    $default_noi_lay_hang = Diachilayhang::find()->where(['kh_id' => $kh_id])->orderBy(['dclh_id' => SORT_ASC])->one()['dp_id'];
                    echo Select2::widget([
                        'model' => $model,
                        'name' => 'noi_lay_id',
                        'value' => $default_noi_lay_hang,
                        'data' => ArrayHelper::map(Duongpho::find()->all(), 'dp_id', 'ten_pho'),
                        'options' => [
                            'class' => 'noi-lay',
                            'id' => 'noi-lay-id',
                            'placeholder' => 'Chọn nơi lấy'
                        ]
                    ]);
                ?>
                        
                <?=
                    $form->field($model, 'noi_giao_id')->widget(Select2::className(), [
                        'data' => ArrayHelper::map(Duongpho::find()->all(), 'dp_id', 'ten_pho'),
                        'options' => [
                            'class' => 'noi-giao',
                            'placeholder' => 'Chọn nơi giao'
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
    const resultValue = $('#noi-lay-id').find('option:selected').text();
    console.log('resultValue', resultValue)
    $('.tt-noi-lay').text(resultValue);

    function autoFillPlace(sourceClass, resultClass) {
        const sourceTarget = $(`.${sourceClass}`)
        const resultTarget = $(`.${resultClass}`)
        sourceTarget.change(function() {
            const resultValue = sourceTarget.val()
            console.log('resultValue', resultValue)
            resultTarget.text(resultValue);
        });
    }

    $('.noi-lay').change(function(e) {
        const resultValue = $(e.target).find('option:selected').text();
        console.log('resultValue', resultValue)
        $('.tt-noi-lay').text(resultValue);
    });

    $('.noi-giao').change(function(e) {
        const resultValue = $(e.target).find('option:selected').text();
        console.log('resultValue', resultValue)
        $('.tt-noi-giao').text(resultValue);
    });

    $('form').on('beforeSubmit', function(e){
        var form = $(this);
        var url = form.attr("action");
        var formData = form.serialize();
        $.post(
            url,
            formData
        )
        .done(function(response) {
            const result = JSON.parse(response)
            if(result == -1) {
                 //Chưa tạo giá ship cho khu vực này -> alert danger
                $('#alert-error').css({'display' : 'block'});
                $('#alert-success').css({'display' : 'none'});
            } else {
                const giaship = result.dongia;
                const thoigianship = result.thoigianship
                $('#alert-error').css({'display' : 'none'});
                $('#alert-success').css({'display' : 'block'});
                $('#alert-success span').text(giaship + " VNĐ");

                // Fill gía ship
                $('.tt-gia').text(giaship + " VNĐ")

                // Fill thời gian ship
                $('.tt-thoi-gian-ship').text(thoigianship)
            }
        })
        .fail(function(error){
            console.log(error);
        })
    }).on('submit', function(e){
        e.preventDefault();
    });

    $('.dropdown-toggle').click(e => {
        const target = e.target;
        const parent = $(target).parents('.dropdown');
        if ($(parent).hasClass('open')) {
            setTimeout(() => {
                $(parent).removeClass('open');
            }, 88)
        } else {
            setTimeout(() => {
                $(parent).addClass('open')
            }, 88)
            
        }
    })
</script>
<?php JSRegister::end();?>