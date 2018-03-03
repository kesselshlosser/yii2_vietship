<?php
$this->title = "Sửa thông tin coupon";
?>
<!--page header start-->
<div class="page-head-wrap">
    <h4 class="margin0">
        <?= $this->title?>
    </h4>
</div>
<!--page header end-->
<div class="ui-content-body">
    <div class="ui-container">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <?= $this->render('_menu') ?>                
            </div>
        </div>
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>

