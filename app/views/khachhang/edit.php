<?php
$this->title = "Sửa thông tin khách hàng";
?>
<!--page header start-->
<div class="page-head-wrap">
    <h4 class="margin0">
        <?= $this->title?>
    </h4>
</div>
<!--page header end-->
<div class="ui-content-body">
    <div class="ui-container" style="padding: 10px;">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <?= $this->render('_menu') ?>                
            </div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
            'model_dclh' => $model_dclh,
            'model_httt' => $model_httt
            ]) ?>
    </div>
</div>

