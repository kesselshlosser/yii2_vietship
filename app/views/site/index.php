<?php
use yii\helpers\Html;
use \app\assets\AppAsset;
use yii\captcha\Captcha;
use \yii\bootstrap\ActiveForm;
use \richardfan\widget\JSRegister;
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
            .logo {
                margin-top: 50px;
                font-size: 35px;
                text-align: center;
                margin-bottom: 25px;
                font-weight: 300;
            }
            .sign-in-title {
                text-align: center;
                text-transform: uppercase;
                font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
                font-weight: 400;
                font-size: 16px;
            }
            .sign-in-body { 
                padding: 20px
            }
            .btn-facebook {
                color: #fff;
                background-color: #3b5998;
                border-color: rgba(0,0,0,0.2);
            }
            .btn-flat {
                position: relative;
                padding-left: 44px;
                text-align: left;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .btn-social>:first-child {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 32px;
                line-height: 34px;
                font-size: 1.6em;
                text-align: center;
                border-right: 1px solid rgba(0,0,0,0.2);
            }
            .btn-google {
                color: #fff;
                background-color: #dd4b39;
                border-color: rgba(0,0,0,0.2);
            }
            .auth {
                margin-top: 16px
            }
        </style>
    </head>
    <body style='background-color: #d2d6de;'>
        <?php $this->beginBody() ?>
            <div class='col-md-12 col-sm-12 col-xs-12' style='margin: 0px auto'>
                <div class='row'>
                    <div class='logo'>
                        <a href="http://kh.vietshipvn.com/templates/index2.html" style='color: #333'>
                            <b>VietShip</b>vn
                        </a>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-6 col-sm-12 col-xs-12'>
                        <div class='col-md-8 col-md-offset-4 col-sm-12 col-xs-12' style='background-color: white'>
                            <div class='sign-in-body'>
                                <div class='row'>
                                    <p class='sign-in-title' style='padding: 0 20px 20px 20px'>
                                        ĐĂNG NHẬP
                                    </p>

                                    <p>
                                        <!--display success message-->
                                        <?php if (Yii::$app->session->hasFlash('sign_in_success')): ?>
                                            <div class="alert alert-success alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <h4><i class="icon fa fa-check"></i>Thành công!</h4>
                                            <?= Yii::$app->session->getFlash('sign_in_success') ?>
                                            </div>
                                        <?php endif; ?>

                                        <!--display error message-->
                                        <?php if (Yii::$app->session->hasFlash('sign_in_error')): ?>
                                            <div class="alert alert-danger alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <h4><i class="icon fa fa-check"></i>Lỗi!</h4>
                                            <?= Yii::$app->session->getFlash('sign_in_error') ?>
                                            </div>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                
                                <div class='row'>
                                    <?php $form = ActiveForm::begin([
                                        'enableAjaxValidation' => false,
                                        'options' => ['enctype' => 'multipart/form-data']
                                    ]); ?>
                                        <div class='form-group has-feedback'>
                                            <?= $form->field($model, 'username')->textInput(['class'=>'form-control', 'placeholder'=>'Email', 'id' => 'login-user'])->label(false) ?>
                                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                        </div>

                                        <div class='form-group has-feedback'>
                                            <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>'Mật khẩu', 'id' => 'login-password'])->label(false) ?>
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        </div>

                                        <?php echo Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-6 col-xs-offset-6', 'value' => 'dangnhap', 'name' => 'smForm', 'style' => 'margin-top: 0px']);?>
                                    <?php ActiveForm::end()?>
                                </div>

                                <div class='row auth'>
                                    <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Đăng nhập bằng Facebook</a>
                                    <a style='margin-top: 8px' href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Đăng nhập bằng Google+</a>
                                </div>

                                <div class='row' style='margin-top: 24px; text-align: right'>
                                    <a href="#" style='col-md-4 col-md-offset-8'>Tôi quên mật khẩu</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Sign up-->
                    <div class='col-md-6 col-sm-12 col-xs-12'>
                        <div class='col-md-8 col-sm-12 col-xs-12' style='background-color: white'>
                            <div class='sign-in-body'>
                                <div class='row'>
                                    <p class='sign-in-title' style='padding: 0 20px 20px 20px'>
                                        ĐĂNG KÝ TÀI KHOẢN VIETSHIPVN
                                    </p>

                                    <p>
                                        <!--display success message-->
                                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                                            <div class="alert alert-success alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <h4><i class="icon fa fa-check"></i>Thành công!</h4>
                                            <?= Yii::$app->session->getFlash('success') ?>
                                            </div>
                                        <?php endif; ?>

                                        <!--display error message-->
                                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                                            <div class="alert alert-danger alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <h4><i class="icon fa fa-check"></i>Lỗi!</h4>
                                            <?= Yii::$app->session->getFlash('error') ?>
                                            </div>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                
                                <div class='row'>    
                                    <?php $form_sign_up = ActiveForm::begin([
                                        'enableAjaxValidation' => false,
                                        'options' => ['enctype' => 'multipart/form-data']
                                    ]); ?>
                                        <div class='form-group has-feedback'>
                                            <?= $form_sign_up->field($model, 'username')->textInput(['class'=>'form-control', 'placeholder'=>'Email', 'id' => 'signup-user'])->label(false) ?>
                                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                        </div>

                                        <div class='form-group has-feedback'>
                                            <?= $form_sign_up->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>'Mật khẩu', 'id' => 'signup-pw'])->label(false) ?>
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        </div>

                                        <div class='form-group has-feedback'>
                                            <?= $form_sign_up->field($model, 're_password')->passwordInput(['class'=>'form-control', 'placeholder'=>'Nhập lại mật khẩu', 'id' => 'signup-re-pw'])->label(false) ?>
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        </div>

                                        <div class='form-group has-feedback'>
                                            <?= $form_sign_up->field($model, 'captcha')->widget(Captcha::className())->label(false) ?>
                                        </div>
                                        <div style='margin-top: 24px;'>
                                            <p>Bấm nút đăng ký là bạn đã đồng ý với <a target='_blank' href="http://vietshipvn.com/dieu-khoan-va-dieu-kien">điều khoản dịch vụ của chúng tôi.</a></p>
                                        </div>
                                    <?= Html::submitButton('Đăng ký', ['id' => 'btn-dang-ky', 'class' => 'btn btn-primary col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-6 col-xs-offset-6', 'value' => 'dangky', 'name' => 'smForm', 'style' => 'margin-top: 0px']);?>
                                    <?php ActiveForm::end()?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php JSRegister::begin() ?>
                <script>
                    
                </script>
            <?php JSRegister::end()?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
