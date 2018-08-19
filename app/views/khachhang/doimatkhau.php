<?php
use yii\helpers\Html;
use yii\helpers\Url;
use richardfan\widget\JSRegister;
$this->title = "Đổi mật khẩu";
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
        <!--Warning-->
        <?php if (Yii::$app->session->hasFlash('doimatkhau-success')): ?>
            <div class="alert alert-success">
                <strong>Thành công!</strong> <?= Yii::$app->session->getFlash('doimatkhau-success') ?>
            </div>
        <?php elseif (Yii::$app->session->hasFlash('doimatkhau-error')):?>
            <div class="alert alert-danger">
                <strong>Thất bại!</strong> <?= Yii::$app->session->getFlash('doimatkhau-error') ?>
            </div>
        <?php endif; ?>
        <!--End warning-->
        <?php echo Html::beginForm('', 'post', ['class' => 'form-horizontal']);?>
            <div class="form-group">
                <label class="control-label col-sm-offset-2 col-sm-2">Mật khẩu hiện tại:</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" name='mat_khau_cu'>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-offset-2 col-sm-2">Mật khẩu mới</label>
                <div class="col-sm-4"> 
                    <input type="password" class="form-control" name='mat_khau_moi'>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-offset-2 col-sm-2">Xác nhận mật khẩu</label>
                <div class="col-sm-4"> 
                    <input type="password" class="form-control" name='mat_khau_xac_nhan'>
                </div>
            </div>

            <div class="form-group"> 
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-danger">Cập nhật</button>
                </div>
            </div>
        <?php echo Html::endForm();?>
    </div>
</div>
<?php JSRegister::begin()?>
    <script>
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
