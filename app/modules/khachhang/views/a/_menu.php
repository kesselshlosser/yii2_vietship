<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>

<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl(['/admin/'.$module]) ?>">
            <?php if($action == 'edit' || $action == 'photos') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= "Danh sách khách hàng" ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/create']) ?>"><?= "Tạo khách hàng mới" ?></a></li>
    <li <?= ($action === 'thanhtoan') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/thanhtoan']) ?>"><?= "Thanh toán" ?></a></li>
    <li <?= ($action === 'thanhtoanky') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/thanhtoanky']) ?>"><?= "Thanh toán đến kỳ" ?></a></li>
    <li <?= ($action === 'hoadon') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/hoadon']) ?>"><?= "Hoá đơn thanh toán" ?></a></li>
</ul>
<br/>