<?php
use app\modules\giashipnoithanh\models\Giashipnoithanh;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\khuvuc\models\Khuvuc;
use app\modules\goidichvu\models\Goidichvu;

$this->title = "Giá ship nội thành";

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
                        Danh sách giá ship nội thành
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
                                            Khu vực lấy
                                        </th>
                                        <th>
                                            Khu vực giao
                                        </th>
                                        <th>
                                            Gói dịch vụ
                                        </th>
                                        <th>
                                            Đơn giá
                                        </th>
                                        <th width="120px">
                                            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->models as $item):?>
                                        <tr data-id="<?= $item->primaryKey ?>">
                                            <td>
                                                <a style="color : blue !important;" href="<?= Url::to(['/admin/'.$module.'/a/edit/', 'id' => $item->primaryKey]) ?>"><?= $item->primaryKey ?></a>
                                            </td>
                                            <td>
                                                <?=
                                                    Khuvuc::find()->where(['kv_id' => $item->kvl_id])->one()['ten_khu_vuc']
                                                ?>
                                            </td>
                                            <td>
                                                <?=
                                                    Khuvuc::find()->where(['kv_id' => $item->kvg_id])->one()['ten_khu_vuc']
                                                ?>
                                            </td>
                                            <td>
                                                <?=
                                                    Goidichvu::find()->where(['gdv_id' => $item->gdv_id])->one()['ten_goi_dich_vu']
                                                ?>
                                            </td>
                                            <td>
                                                <?= $item->don_gia?> VNĐ
                                            </td>
                                            <td style="text-align: center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>" class="btn btn-default" title="Sửa giá ship nội thành"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="Xóa giá ship nội thành" onclick="return confirm('Bạn muốn xóa giá ship nội thành này?');"><span class="glyphicon glyphicon-trash"></span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>Không tìm thấy giá ship nội thành nào</p>
                        <?php endif;?>
                    </div>
                </section>
            </div>

        </div>
                
        </div>
    </div>
</div>

