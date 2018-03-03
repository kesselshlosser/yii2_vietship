<?php
use app\modules\khuvuc\models\Khuvuc;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Khu vực";

$module = $this->context->module->id;
?>

<!--page header start-->
<div class="page-head-wrap">
    
</div>
<!--page header end-->

<div class="ui-content-body">
    <div class="ui-container" style="padding: 10px; background-color: #fff">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <?= $this->render('_menu') ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <?php if($data->count > 0) : ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <?php if(IS_ROOT) : ?>
                                    <th width="50">#</th>
                                <?php endif; ?>
                                <th>Khu vực</th>
                                <th width="120"></th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php foreach($data->models as $item) : ?>
                            <tr data-id="<?= $item->primaryKey ?>">
                                <?php if(IS_ROOT) : ?>
                                    <td><?= $item->primaryKey ?></td>
                                <?php endif; ?>
                                <td><a style="color : blue !important" href="<?= Url::to(['/admin/'.$module.'/a/edit/', 'id' => $item->primaryKey]) ?>"><?= $item->ten_khu_vuc ?></a></td>
                                <td style="text-align: center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>" class="btn btn-default" title="Sửa khu vực"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="Xóa khu vực" onclick="return confirm('Bạn muốn xóa khu vực <?= $item->ten_khu_vuc;?>?');"><span class="glyphicon glyphicon-trash"></span></a>
                                    </div>
                                </td>
                            </tr>
                    <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= yii\widgets\LinkPager::widget([
                        'pagination' => $data->pagination
                    ]) ?>
                <?php else : ?>
                    <p>Không tìm thấy khu vực nào</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

