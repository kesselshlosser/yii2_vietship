<?php
use yii\helpers\Url;

$action = $this->context->action->id;
?>

<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/giashipnoithanh/index']) ?>">
            <?= "Danh sách giá ship nội thành" ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/giashipnoithanh/create']) ?>"><?= "Tạo giá ship nội thành mới" ?></a></li>
</ul>
<br/>