<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\khachhang\models\Khachhang;
use app\modules\goidichvu\models\Goidichvu;
use yii\bootstrap\Modal;
use app\modules\duongpho\models\Duongpho;

$this->title = "Đơn hàng";

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
                        Danh sách đơn hàng
                        <span class="tools pull-right">
                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                            <a class="close-box fa fa-times" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <?php if($data->count > 0):?>
                            <table class="table responsive-data-table table-striped" style="font-size: 13px; width: 100%">
                                <thead>
                                    <tr>
                                        <th>
                                            Mã ĐH
                                        </th>
                                        <th>
                                            Tên KH
                                        </th>
                                        <th>
                                            Người nhận
                                        </th>
                                        <th>
                                            Gói dịch vụ
                                        </th>
                                        <th>
                                            Hình thức
                                        </th>
                                        <th>
                                            Cước v/c
                                        </th>
                                        <th>
                                            Tiền thu hộ
                                        </th>
                                        <th>
                                            Ngày tạo
                                        </th>
                                        <th>
                                            Trạng thái
                                        </th>
                                        <th width="120px" style="text-align: center">
                                            Tác vụ
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->models as $item):?>
                                        <tr data-id="<?= $item->primaryKey ?>">
                                            
                                            <td>
                                                <?php 
                                                    $model_kh = Khachhang::find()->where(['kh_id' => $item->kh_id])->one();
                                                    $ten = $model_kh['ten_hien_thi'];
                                                    $so_dien_thoai = $model_kh['so_dien_thoai'];
                                                    Modal::begin([
                                                        'header'=> '<h3 style="text-align : center;">Đơn hàng '.$item->ma_don_hang.' - Khách hàng '.$ten.'</h3>',
                                                        'id'    => $item->dh_id,
                                                        'size'  => 'modal-lg',
                                                    ]);
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="panel panel-danger">
                                                            <header class="panel-heading">
                                                                Thông tin đơn hàng
                                                            </header>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Mã đơn hàng
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $item->ma_don_hang;?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Cước vận chuyển
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $item->tong_tien.' VNĐ'?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Tiền thu hộ
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $item->tien_thu_ho.' VNĐ'?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Hình thức thanh toán
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $item->hinh_thuc_thanh_toan?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Phố giao hàng
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= Duongpho::find()->where(['dp_id' => $item->pho_giao_hang])->one()['ten_pho']?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                                        <p style="font-weight: bold">Dịch vụ phụ trội</p>
                                                                        <?php
                                                                            $arr_dvpt = json_decode($item->dich_vu_phu_troi, true);
                                                                            foreach($arr_dvpt as $dvpt)
                                                                            {
                                                                                if($dvpt['value'] == 1) //Hiển thị nó ra
                                                                                {
                                                                                    if($dvpt['key'] == 'dvpt4') //Hàng quá khổ
                                                                                    {
                                                                                        echo '<p>*'.$dvpt['content'].'</p><br>';
                                                                                        echo '<p style="padding-left: 10px">-Dài: '.$dvpt['note']['dài'].'</p>';
                                                                                        echo '<p style="padding-left: 10px">-Rộng: '.$dvpt['note']['rộng'].'</p>';
                                                                                        echo '<p style="padding-left: 10px">-Cao: '.$dvpt['note']['cao'].'</p>';
                                                                                        echo '<p style="padding-left: 10px">-Nặng: '.$dvpt['note']['nang'].'</p>';
                                                                                    }else
                                                                                    {
                                                                                        echo '<p>*'.$dvpt['content'].'</p>';
                                                                                        if($dvpt['note'])
                                                                                        {
                                                                                            if($dvpt['key'] == 'dvpt2')
                                                                                            {
                                                                                                echo '<p style="padding-left: 10px">-Ghi chú: '.$dvpt['note'].' giờ</p>';
                                                                                            }else
                                                                                            {
                                                                                                echo '<p style="padding-left: 10px">-Ghi chú: '.$dvpt['note'].'</p>';
                                                                                            }
                                                                                            
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Ngày tạo
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= date('H:i d/m/Y', $item->time)?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Ghi chú
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $item->ghi_chu?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Trạng thái
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $item->trang_thai?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="panel panel-danger">                                                            
                                                            <header class="panel-heading">
                                                                Thông tin người gửi
                                                            </header>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Tên người gửi
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $ten?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Địa chỉ lấy hàng
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $item->dia_chi_lay_hang?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Số điện thoại người gửi
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $so_dien_thoai?>
                                                                    </div>
                                                                </div>                                                                                                                                
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="panel panel-danger">
                                                            <?php
                                                                $arr_nguoi_nhan = json_decode($item->nguoi_nhan, true);
                                                                $nn_ten = $arr_nguoi_nhan['ten'];
                                                                $nn_so_dien_thoai = $arr_nguoi_nhan['so_dien_thoai'];
                                                                $nn_dia_chi_giao_hang = $arr_nguoi_nhan['dia_chi_giao_hang'];
                                                            ?>
                                                            <header class="panel-heading">
                                                                Thông tin người nhận
                                                            </header>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Tên người nhận
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $nn_ten?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Địa chỉ giao hàng
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $nn_dia_chi_giao_hang?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Số điện thoại người nhận
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $nn_so_dien_thoai?>
                                                                    </div>
                                                                </div>                                                                                                                                
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="panel panel-danger">
                                                            <?php
                                                                $arr_san_pham = json_decode($item->san_pham, true);
                                                                $sp_ten = $arr_san_pham['ten'];
                                                                $sp_so_luong = $arr_san_pham['so_luong'];
                                                            ?>
                                                            <header class="panel-heading">
                                                                Thông tin sản phẩm
                                                            </header>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Tên sản phẩm
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $sp_ten?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Số lượng
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= $sp_so_luong?>
                                                                    </div>
                                                                </div>                                                                                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="panel panel-danger">
                                                            <header class="panel-heading">
                                                                Lịch trình
                                                            </header>
                                                            <div class="panel-body">
                                                                                                                                                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>        
                                                <?php Modal::end();?>
                                                <?= $item->ma_don_hang?>
                                                <br>
                                                <br>
                                                <a data-toggle = 'modal' data-target = '#<?= $item->dh_id?>' class="btn btn-sm btn-default" style="margin-bottom: 8px"><i class="glyphicon glyphicon-modal-window" style="vertical-align: baseline"></i> Xem chi tiết</a>
                                                <a target='_blank' class="btn btn-sm btn-default" href="<?= Url::to(['/admin/donhang/a/print']).'/'.$item->dh_id?>"><i class="glyphicon glyphicon-print" style="vertical-align: baseline"></i> In đơn hàng</a>
                                            </td>
                                            <td>
                                                <?php
                                                    
                                                    echo $ten.'<br>'.$so_dien_thoai;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    
                                                    echo $nn_ten.'<br>'.$nn_so_dien_thoai.'<br>'.$nn_dia_chi_giao_hang;
                                                ?>
                                            </td>
                                            <td>
                                                <?=
                                                    Goidichvu::find()->where(['gdv_id' => $item->gdv_id])->one()['ten_goi_dich_vu'];
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?=
                                                    $item->hinh_thuc_thanh_toan;
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?=
                                                    $item->tong_tien.' VNĐ';
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?=
                                                    $item->tien_thu_ho.' VNĐ';
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?php
                                                    $hour = date('H:i', $item->time);
                                                    $day = date('d/m/Y', $item->time);
                                                    echo $hour.'<br>'.$day;
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?=
                                                    $item->trang_thai;
                                                ?>
                                            </td>
                                            <td style="text-align: center">
                                                <div>
                                                    <button type="button" style="width:120px; margin-bottom: 3px" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-user" style="vertical-align: baseline !important"></i> Chọn n/v lấy</button>
                                                </div>
                                                <div>
                                                    <button type="button" style="width:120px; margin-bottom: 3px" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-remove" style="vertical-align: baseline !important"></i> Hủy đơn</button>
                                                </div>
                                                <div>
                                                    <button type="button" style="width:120px; margin-bottom: 3px" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-pencil" style="vertical-align: baseline !important"></i> Sửa</button>
                                                </div>
                                                <div class="dropdown">
                                                    <button style="width: 120px" class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="glyphicon glyphicon-option-horizontal" style="vertical-align: baseline !important"></i> Khác
                                                    <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" style="min-width: 140px">
                                                      <li><a href="#">Thêm phụ phí</a></li>
                                                      <li><a href="#">Xóa</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>Không tìm thấy đơn hàng nào</p>
                        <?php endif;?>
                    </div>
                </section>
            </div>

        </div>
                
        </div>
    </div>
</div>

