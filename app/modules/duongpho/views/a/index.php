<?php
use app\modules\duongpho\models\Duongpho;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\quanhuyen\models\Quanhuyen;

$this->title = "Đường phố";

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
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading panel-border">
                        Danh sách đường phố
                        <span class="tools pull-right">
                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                            <a class="close-box fa fa-times" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <?php if($data->count > 0):?>
                            <table class="table responsive-data-table table-striped">
                                <thead>
                                    <tr>
                                        <th width="50px">
                                            #
                                        </th>
                                        <th>
                                            Tên phố
                                        </th>
                                        <th>
                                            Quận / Huyện
                                        </th>
                                        <th>
                                            Khu vực
                                        </th>
                                        <th>
                                            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->models as $item):?>
                                        <tr data-id="<?= $item->primaryKey ?>">
                                            <td>
                                                <?= $item->primaryKey?>
                                            </td>
                                            <td>
                                                <a style="color : blue !important;" href="<?= Url::to(['/admin/'.$module.'/a/edit/', 'id' => $item->primaryKey]) ?>"><?= $item->ten_pho ?></a>
                                            </td>
                                            <td>
                                                <?=
                                                    Quanhuyen::find()->where(['qh_id' => $item->qh_id])->one()['ten_quan_huyen']
                                                ?>
                                            </td>
                                            <td>
                                                <?=
                                                    Khuvuc::find()->where(['kv_id' => $item->kv_id])->one()['ten_khu_vuc']
                                                ?>
                                            </td>
                                            <td style="text-align: center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>" class="btn btn-default" title="Sửa phố"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="Xóa phố" onclick="return confirm('Bạn muốn xóa phố <?= $item->ten_pho;?>?');"><span class="glyphicon glyphicon-trash"></span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>Không tìm thấy đường phố nào</p>
                        <?php endif;?>
                    </div>
                </section>
            </div>

        </div>
                
        </div>
    </div>
</div>

