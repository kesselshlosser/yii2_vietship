<?php
use app\modules\khachhang\models\Khachhang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\models\Diachilayhang;
use yii\easyii\models\Hinhthucthanhtoan;
use yii\bootstrap\Modal;

$this->title = "Khách hàng";

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
                        Danh sách khách hàng
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
                                        <th width="30px">
                                            #
                                        </th>
                                        <th>
                                            Tên khách hàng
                                        </th>
                                        <th>
                                            Gói KH
                                        </th>
                                        <th>
                                            Địa chỉ
                                        </th>
                                        <th>
                                            Số điện thoại
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th width="120px">
                                            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->models as $item):?>
                                        <?php
                                            Modal::begin([
                                                'header' => '<h3 style="text-align : center;">'.$item->ten_hien_thi.'</h3>',
                                                'id' => 'w'.$item->kh_id,
                                                'size'=>'modal-lg',
                                            ]);
                                            
                                            $model_dclh = Diachilayhang::find()->where(['kh_id' => $item->kh_id])->all();
                                            $model_httt = Hinhthucthanhtoan::find()->where(['kh_id' => $item->kh_id])->one();
                                        ?>    
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <h3 style="text-align: center; background-color: #337ab7;border-radius: 5px; color: #fff; font-size: 20px; padding: 5px">Địa chỉ lấy hàng</h3>
                                                <?php if(count($model_dclh) > 0):?>
                                                    <?php foreach($model_dclh as $dclh):?>
                                                            <div class="panel panel-danger">
                                                                <header class="panel-heading">
                                                                    <?= $dclh['ten_goi_nho']?>
                                                                </header>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                            Người bàn giao hàng
                                                                        </div>

                                                                        <div style="text-align: right" class="col-md-6 col-sm-6 col-xs-6">
                                                                            <?= $dclh->ten_nguoi_ban_giao_hang;?>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                            Số điện thoại
                                                                        </div>

                                                                        <div style="text-align: right" class="col-md-6 col-sm-6 col-xs-6">
                                                                            <?= $dclh->so_dien_thoai?>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                            Địa chỉ
                                                                        </div>

                                                                        <div style="text-align: right" class="col-md-6 col-sm-6 col-xs-6">
                                                                            <?= $dclh->dia_chi_text?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php endforeach;?>
                                                <?php else:?>
                                                    <div>
                                                        Chưa có thông tin về kho của khách hàng.
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                            
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <h3 style="text-align: center; background-color: #337ab7;border-radius: 5px; color: #fff; font-size: 20px; padding: 5px">Hình thức thanh toán</h3>
                                                <?php if(count($model_httt) > 0):?>
                                                    <?php if($model_httt->hinh_thuc_thanh_toan == 'Tiền mặt'):?>
                                                        <div class="panel panel-danger">
                                                                <header class="panel-heading">
                                                                    Thanh toán bằng tiền mặt
                                                                </header>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                            Tên người nhận
                                                                        </div>
                                                                        
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                            <?= $model_httt['ten_nguoi_nhan']?>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                            Địa chỉ
                                                                        </div>
                                                                        
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                            <?= $model_httt['dia_chi']?>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                            Số điện thoại
                                                                        </div>
                                                                        
                                                                        <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                            <?= $model_httt['so_dien_thoai']?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    <?php elseif ($model_httt->hinh_thuc_thanh_toan == 'Chuyển khoản'):?>
                                                        <?php 
                                                            $arr_thong_tin_ngan_hang = json_decode($model_httt->thong_tin_ngan_hang, true);
                                                        ?>
                                                        <?php foreach($arr_thong_tin_ngan_hang as $ttnh):?>
                                                            <div class="panel panel-danger">
                                                                    <header class="panel-heading">
                                                                        Ngân hàng <?= $ttnh['ten_ngan_hang']?>
                                                                    </header>
                                                                    <div class="panel-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                                Chủ tài khoản
                                                                            </div>

                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                                <?= $ttnh['chu_tai_khoan']?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                                Số tài khoản
                                                                            </div>

                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                                <?= $ttnh['so_tai_khoan']?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                                Chi nhánh
                                                                            </div>

                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                                <?= $ttnh['chi_nhanh']?>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="row">
                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                                Tỉnh thành
                                                                            </div>

                                                                            <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                                <?= $ttnh['tinh_thanh']?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        <?php endforeach;?>   
                                                    <?php endif;?>
                                                <?php else:?>
                                                    <div>
                                                        Chưa có thông tin về hình thức thanh toán của khách hàng.
                                                    </div>
                                                <?php endif;?>
                                                
                                                <!--Thời gian thanh toán-->
                                                <?php
                                                    $arr_tgtt = json_decode($model_httt->thoi_gian_thanh_toan, true);
                                                ?>
                                                <div class="panel panel-danger">
                                                    <header class="panel-heading">
                                                        Thời gian thanh toán
                                                    </header>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <?php if($arr_tgtt['type'] == 'Mỗi tuần 1 lần'):?>
                                                                <div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                    <?= $arr_tgtt['type']?>
                                                                </div>

                                                                <div class="col-md-6 col-sm-6 col-xs-6" style="text-align: right">
                                                                    Thanh toán thứ <?= $arr_tgtt['time']?>
                                                                </div>
                                                            <?php else:?>
                                                                <div class="col-md-12 col-sm-12 col-xs-12" style="font-weight: bold">
                                                                    <?= $arr_tgtt['type']?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php        
                                            Modal::end();
                                        ?>
                                
                                        
                                
                                        <tr data-id="<?= $item->primaryKey ?>">
                                            <td>
                                                <a style="color : blue !important;" href="<?= Url::to(['/admin/'.$module.'/a/edit/', 'id' => $item->primaryKey]) ?>"><?= $item->primaryKey ?></a>
                                            </td>
                                            <td>
                                                <a style="color : blue !important;" data-toggle = 'modal' data-target = '#w<?= $item->kh_id?>' href="#"><?= $item->ten_hien_thi ?></a>
                                            </td>
                                            <td>
                                                <?php
                                                    $gkh = json_decode($item->gkh_id, true);
                                                    $str_gkh = '';
                                                    foreach($gkh as $gkh):
                                                ?>
                                                    <?php
                                                        $model_gkh = app\modules\goikhachhang\models\Goikhachhang::find()->where(['gkh_id' => $gkh])->one();
                                                        Modal::begin([
                                                            'header' => '<h3 style="text-align : center">Gói khách hàng '.strtoupper($model_gkh['ten_goi']).'</h3>',
                                                            'id' => 'gkh'.$gkh,
                                                            'size' => 'modal-lg'
                                                        ])
                                                    ?>
                                                    <div class='row'>
                                                        <div class='col-md-12'>
                                                            <p><span style='font-weight: bold'>Hình thức khuyến mại :</span> <span><?= $model_gkh['hinh_thuc']?></span></p>
                                                            <p><span style='font-weight: bold'>Giá trị :</span> <span><?= $model_gkh['gia_tri']?></span></p>
                                                            <?php if($model_gkh['chi_giam_dich_vu_phu_troi'] == 1):?>
                                                                <p><span style='font-weight: bold'>Áp dụng giảm cho dịch vụ phụ trội</span></p>
                                                            <?php else:?>
                                                                <p><span style='font-weight: bold'>Áp dụng giảm cho toàn bộ tiền cước</span></p>
                                                            <?php endif;?>
                                                            <p><span style='font-weight: bold'>Dịch vụ phụ trội :</span></p>
                                                            <?php
                                                                $dvpt = json_decode($model_gkh['dich_vu_phu_troi'], true);
                                                                foreach($dvpt as $dvpt)
                                                                {
                                                                    if($dvpt['value'] == 1)
                                                                    {
                                                                        echo '<p style="padding-left : 10px">- '.$dvpt['content'].'</p>';
                                                                    }
                                                                }
                                                            ?>
                                                            <?php
                                                                $ngay_gio_status = 0;
                                                                if($model_gkh['day_ngay_bat_dau'] && $model_gkh['day_ngay_ket_thuc'])
                                                                {
                                                                    $ngay_gio_status = 1; //Áp dụng theo ngày
                                                                }
                                                            ?>
                                                            <?php if($ngay_gio_status == 1):?>
                                                            <p><span style='font-weight: bold'>Áp dụng theo ngày: </span><span>từ <?= date('d-m-Y', $model_gkh['day_ngay_bat_dau'])?> đến <?= date('d-m-Y', $model_gkh['day_ngay_ket_thuc'])?></span></p>
                                                            <?php elseif($ngay_gio_status == 0):?>
                                                            <p><span style='font-weight: bold'>Áp dụng theo giờ : </span><span>từ <?= date('d-m-Y', $model_gkh['hour_thoi_gian_ap_dung'])?> vào lúc <?= $model_gkh['hour_gio_ap_dung']?> giờ</span></p>
                                                            <?php endif;?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        Modal::end();
                                                    ?>
                                                    
                                                    <?php
                                                        $ten_gkh = $model_gkh['ten_goi'];
                                                        $new_str = '<a style="color : blue !important;" data-toggle = "modal" data-target = "#gkh'.$gkh.'" href="#">'.$ten_gkh.'</a><br>';
                                                        $str_gkh = $str_gkh.$new_str;
                                                    ?>
                                                        
                                                <?php endforeach;?>
                                                <?php echo $str_gkh;?>
                                            </td>
                                            <td>
                                                <?= $item->dia_chi;?>
                                            </td>
                                            <td>
                                                <?= $item->so_dien_thoai?>
                                            </td>
                                            <td>
                                                <?= $item->email?>
                                            </td>
                                            <td style="text-align: center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>" class="btn btn-default" title="Sửa khách hàng"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="Xóa khách hàng" onclick="return confirm('Bạn muốn xóa khách hàng <?= $item->ten_hien_thi;?>?');"><span class="glyphicon glyphicon-trash"></span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>Không tìm thấy khách hàng nào</p>
                        <?php endif;?>
                    </div>
                </section>
            </div>

        </div>
                
        </div>
    </div>
</div>

