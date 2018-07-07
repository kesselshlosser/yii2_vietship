<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>

<ul class="nav nav-pills">
    <li <?= ($action === 'hoadon') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/khachhang/hoadon']) ?>"><?= "Hoá đơn thanh toán" ?></a></li>
</ul>
<br/>