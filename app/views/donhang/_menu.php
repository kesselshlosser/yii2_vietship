<?php
use yii\helpers\Url;

$action = $this->context->action->id;
?>

<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/donhang/index']) ?>">
            <?= "Danh sách đơn hàng" ?>
        </a>    
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/donhang/create']) ?>"><?= "Tạo đơn hàng mới" ?></a></li>
</ul>
<br/>