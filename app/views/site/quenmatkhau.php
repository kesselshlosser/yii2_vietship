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
        <title>Quên mật khẩu</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="<?= $asset->baseUrl ?>/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?= $asset->baseUrl ?>/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php $this->head() ?>
        <style>
        </style>
    </head>

    <body>
        <?php $this->beginBody() ?>
            <div class='row'>
                <div class='col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4 col-xs-12'>
                    <div clas='col-md-12 col-sm-12 col-xs-12'>
                        <!--Warning-->
                        <?php if (Yii::$app->session->hasFlash('quenmatkhau-success')): ?>
                            <div class="alert alert-success">
                                <strong>Thành công!</strong> <?= Yii::$app->session->getFlash('quenmatkhau-success') ?>
                            </div>
                        <?php elseif (Yii::$app->session->hasFlash('quenmatkhau-error')):?>
                            <div class="alert alert-danger">
                                <strong>Thất bại!</strong> <?= Yii::$app->session->getFlash('quenmatkhau-error') ?>
                            </div>
                        <?php endif; ?>
                        <!--End warning-->
                        <h3>Lấy lại mật khẩu</h3>
                        <h4>Bạn vui lòng nhập email để lấy lại mật khẩu</h4>
                        <?php echo Html::beginForm('', 'post');?>
                            <div class="form-group">
                                <label>Email:</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    name='email'
                                    required
                                    oninvalid="this.setCustomValidity('Bạn chưa nhập email')"
                                    oninput="setCustomValidity('')">
                            </div>
                        <?php echo Html::submitButton('Xác nhận', ['class' => 'btn btn-success', 'style' => 'margin-top: 8px']);?>
                        <?php echo Html::endForm();?>
                    </div>
                </div>
            </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>