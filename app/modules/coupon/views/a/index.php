<?php
use app\modules\coupon\models\Coupon;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\goidichvu\models\Goidichvu;

$this->title = "Coupon";

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
                        Danh sách coupon
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
                                        <th>
                                            Mã coupon
                                        </th>
                                        <th>
                                            Thời gian áp dụng
                                        </th>
                                        <th>
                                            Gói áp dụng
                                        </th>
                                        <th>
                                            Giá trị
                                        </th>
                                        <th width="120px">
                                            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->models as $item):?>
                                        <tr data-id="<?= $item->primaryKey ?>">                                            
                                            <td>
                                                <a style="color : blue !important;" href="<?= Url::to(['/admin/'.$module.'/a/edit/', 'id' => $item->primaryKey]) ?>"><?= strtoupper($item->ma_coupon) ?></a>
                                            </td>
                                            <td>
                                                <?php
                                                    echo 'Từ '.date('d/m/Y', $item->ngay_bat_dau).' đến '.date('d/m/Y', $item->ngay_ket_thuc);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $str_gdv = '';
                                                    $arr_gdv = json_decode($item->gdv_id, true);
                                                    foreach($arr_gdv as $gdv_id)
                                                    {
                                                        $str_gdv .= Goidichvu::find()->where(['gdv_id' => $gdv_id])->one()['ten_goi_dich_vu'].'<br>';
                                                    }
                                                    echo $str_gdv;
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?php
                                                    $giatri = '';
                                                    if($item->hinh_thuc_khuyen_mai == 'Giảm theo %')
                                                    {
                                                        $giatri = 'Giảm '.$item->gia_tri.' %';
                                                    }else if($item->hinh_thuc_khuyen_mai == 'Đồng giá')
                                                    {
                                                        $giatri = "Đồng giá ".$item->gia_tri.' VNĐ';
                                                    }else if($item->hinh_thuc_khuyen_mai == 'Giảm cước')
                                                    {
                                                        $giatri = "Giảm ".$item->gia_tri.' VNĐ';
                                                    }else if($item->hinh_thuc_khuyen_mai == 'Tặng tiền')
                                                    {
                                                        $giatri = "Tặng ".$item->gia_tri.' VNĐ';
                                                    }
                                                    echo $giatri;
                                                ?>
                                            </td>
                                            
                                            <td style="text-align: center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>" class="btn btn-default" title="Sửa coupon"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="Xóa coupon" onclick="return confirm('Bạn muốn xóa coupon này?');"><span class="glyphicon glyphicon-trash"></span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>Không tìm thấy coupon nào</p>
                        <?php endif;?>
                    </div>
                </section>
            </div>

        </div>
                
        </div>
    </div>
</div>

