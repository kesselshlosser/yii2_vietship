<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\khachhang\models\Khachhang;
use app\modules\goidichvu\models\Goidichvu;
use yii\bootstrap\Modal;
use app\modules\duongpho\models\Duongpho;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\checkbox\CheckboxX;
use yii\easyii\models\Admin;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use richardfan\widget\JSRegister;
use yii\widgets\Pjax;

$this->title = "Đơn hàng";
$baseUrl = \yii\helpers\Url::base(true);

$module = $this->context->module->id;
?>
<style>
    .table thead tr, .table tbody tr td {
        vertical-align: baseline !important
    }
</style>
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
                            <table id='table-don-hang' class="table table-striped" style="font-size: 13px; width: 100%">
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
                                        <th style="text-align: center">
                                            Tác vụ
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->models as $item):?>
                                        <tr data-id="<?= $item->primaryKey ?>" id='<?= $item->dh_id?>'>
                                            <td>
                                                <?php 
                                                    $model_kh = Khachhang::find()->where(['kh_id' => $item->kh_id])->one();
                                                    $ten = $model_kh['ten_hien_thi'];
                                                    $so_dien_thoai = $model_kh['so_dien_thoai'];
                                                    Modal::begin([
                                                        'header'=> '<h3 style="text-align : center;">Đơn hàng '.$item->ma_don_hang.' - Khách hàng '.$ten.'</h3>',
                                                        'id'    => 'chi-tiet'.$item->dh_id,
                                                        'size'  => 'modal-lg',
                                                    ]);
                                                ?>
                                                <div class="row">
                                                    <div class='col-md-6 col-sm-6 col-xs-6'>
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
                                                                        <?= number_format($item->tong_tien, 0, '', ',').' VNĐ'?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-5 col-sm-6 col-xs-6" style="font-weight: bold">
                                                                        Tiền thu hộ
                                                                    </div>

                                                                    <div style="text-align: right" class="col-md-7 col-sm-6 col-xs-6">
                                                                        <?= number_format($item->tien_thu_ho, 0, '', ',').' VNĐ'?>
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
                                                    </div>
                                                    <div class='col-md-6 col-sm-6 col-xs-6'>
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
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class='col-md-6 col-sm-6 col-xs-6'>
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
                                                    </div>
                                                    <div class='col-md-6 col-sm-6 col-xs-6'>
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
                                                </div>
                                                <div class='row'>
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="panel panel-danger">
                                                            <header class="panel-heading">
                                                                Lịch trình
                                                            </header>
                                                            <div class="panel-body">
                                                                <?php if (!empty($item->lich_trinh_don_hang)):?>
                                                                    <div class='col-md-12 col-sm-12 col-xs-12'>
                                                                        <table class='table table-bordered'>
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Thời gian</th>
                                                                                    <th>Trạng thái</th>
                                                                                    <th>Ghi chú</th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody>
                                                                                <?php $lich_trinh_don_hang = json_decode($item->lich_trinh_don_hang, true);
                                                                                    foreach($lich_trinh_don_hang as $ltdh):
                                                                                    $time = date('H:i d-m-Y', $ltdh['time']);
                                                                                    $action = (isset($ltdh['action']) && !empty($ltdh['action'])) ? $ltdh['action'] : '';
                                                                                    $lydo = (isset($ltdh['lydo']) && !empty($ltdh['lydo'])) ? $ltdh['lydo'] : '';
                                                                                    $ghichu = (isset($ltdh['ghichu']) && !empty($ltdh['ghichu'])) ? $ltdh['ghichu'] : '';
                                                                                    $trangThai = (isset($ltdh['trangThai']) && !empty($ltdh['trangThai'])) ? $ltdh['trangThai'] : '';
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?= $time?></td>
                                                                                        <td><?= $trangThai?></td>
                                                                                        <td><?= $action?> <?= !empty($lydo) ? '- '.$lydo : ''?> <?= !empty($ghichu) ? '- '.$ghichu : ''?></td>
                                                                                    </tr>
                                                                                <?php endforeach;?>
                                                                            </tbody>
                                                                        </table>  
                                                                    </div>    
                                                                <?php endif;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php Modal::end();?>
                                                <?= $item->ma_don_hang?>
                                                <br>
                                                <a data-toggle = 'modal' data-target = '#chi-tiet<?= $item->dh_id?>' class="btn btn-sm btn-default" style="margin-bottom: 8px"><i class="glyphicon glyphicon-modal-window" style="vertical-align: baseline"></i> Xem chi tiết</a><br>
                                                <a target='_blank' class="btn btn-sm btn-default" href="<?= Url::to(['/admin/donhang/print']).'/'.$item->dh_id?>"><i class="glyphicon glyphicon-print" style="vertical-align: baseline"></i> In đơn hàng</a>
                                            </td>
                                            <td>
                                                <?php
                                                    echo $ten.'<br>'.$so_dien_thoai.'<br>'.$item->dia_chi_lay_hang;
                                                ?>
                                                <?php 
                                                    if ($item->ghi_chu):
                                                ?>
                                                    <br>
                                                    <br>
                                                    <p><span style='font-weight: bold; background-color: red; color: white'>Ghi chú: </span><?= $item->ghi_chu?></p>
                                                <?php endif;?>
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
                                                <p><?= $item->hinh_thuc_thanh_toan?></p>
                                                <?php
                                                    $ghi_chu_hinh_thuc_thanh_toan = $item->ghi_chu_hinh_thuc_thanh_toan;
                                                    if ($ghi_chu_hinh_thuc_thanh_toan):
                                                    $arr_ghi_chu_hinh_thuc_thanh_toan = json_decode($ghi_chu_hinh_thuc_thanh_toan, true);
                                                    $prev_httt = $arr_ghi_chu_hinh_thuc_thanh_toan['prev'];
                                                    $reason_httt = $arr_ghi_chu_hinh_thuc_thanh_toan['reason'];
                                                ?>
                                                    <p style='text-decoration: line-through;'><?= $prev_httt?></p>
                                                    <p>Lý do: <?= $reason_httt?></p>
                                                <?php endif;?>
                                            </td>
                                            
                                            <td width='110'>
                                                <?php
                                                Modal::begin([
                                                    'header'=> '<h3 style="text-align : center;">Phụ phí</h3>',
                                                    'id'    => 'xemphuphi'.$item->dh_id,
                                                    'options' => [
                                                        'tabindex' => false
                                                    ]
                                                ]);
                                                ?>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Số tiền</th>
                                                            <th>Ghi chú</th>
                                                            <th>Ngày tháng</th>
                                                        </tr>
                                                    </thead>
                                                    <?php if (!empty($item->phu_phi)):?>
                                                        <tbody>
                                                            <?php 
                                                                $arrPhuPhi = json_decode($item->phu_phi, true);
                                                                foreach($arrPhuPhi as $phuPhi):
                                                                    $ghiChu = '';
                                                                    $ghiChuDate = '';
                                                                    if ($phuPhi['ghi_chu']) {
                                                                        $ghiChu = $phuPhi['ghi_chu'];
                                                                    }
                                                                    $ghiChuDate = date('d-m-Y H:i:s', $phuPhi['ghi_chu_thoi_gian']);
                                                                    $phuphi = (int)$phuPhi['phu_phi'];
                                                            ?>
                                                                <tr>
                                                                    <td><?= number_format($phuphi, 0, '', ',').' VNĐ'?></td>
                                                                    <td><?= $ghiChu?></td>
                                                                    <td><?= $ghiChuDate?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                        </tbody>
                                                    <?php endif;?>
                                                </table>
                                                <?php Modal::end()?>
                                                <?php
                                                    $tongPhuPhi = 0;
                                                    if (!empty($item->phu_phi)) {
                                                        $pPhi = json_decode($item->phu_phi, true);
                                                        foreach($pPhi as $pp) {
                                                            $tongPhuPhi += $pp['phu_phi'];
                                                        }
                                                    }
                                                ?>
                                                <?php
                                                    $tong_tien_cuoc = $item->tong_tien + $tongPhuPhi;
                                                    echo $tong_tien_cuoc > 0 ? number_format($tong_tien_cuoc, 0, '', ',').' VNĐ' : '0 VNĐ';
                                                ?>
                                                <?php
                                                    if ($tongPhuPhi > 0) {
                                                        echo '<p>Tiền cước bao gồm phụ phí</p>';
                                                    }
                                                ?>

                                                <?php
                                                    if (!empty($item->phu_phi)):
                                                ?>
                                                    <label>Phụ phí:</label>
                                                    <br>
                                                    <p><?= $tongPhuPhi > 0 ? number_format($tongPhuPhi, 0, '', ',').' VNĐ' : '0 VNĐ'?></p>
                                                    <button
                                                        data-toggle='modal'
                                                        data-target='#xemphuphi<?= $item->dh_id?>'
                                                        type="button"
                                                        style='width:100%;'
                                                        class='btn btn-sm btn-default'
                                                    >
                                                        Xem chi tiết
                                                    </button>
                                                <?php endif;?>
                                            </td>
                                            
                                            <td>
                                                <?=
                                                    $item->tien_thu_ho > 0 ? number_format($item->tien_thu_ho, 0, '', ',').' VNĐ' : '0 VNĐ';
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?php
                                                    $hour = date('H:i', $item->time);
                                                    $day = date('d/m/Y', $item->time);
                                                    echo $hour.'<br>'.$day;
                                                ?>
                                            </td>
                                            
                                            <td width='120' style='text-align: center'>
                                                <?php
                                                    $labelStyle = 'background-color: #f39c12;';
                                                    switch($item->trang_thai) {
                                                        case 'Đang lấy':
                                                        case 'Đang giao':
                                                            $labelStyle = 'background-color: #00c0ef;';
                                                        break;
                                                        case 'Đã giao':
                                                            $labelStyle = 'background-color: #00a65a;';
                                                        break;
                                                        case 'Đã huỷ':
                                                            $labelStyle = 'background-color: #dd4b39;';
                                                        break;
                                                        case 'Chờ duyệt':
                                                            $labelStyle = 'background-color: #d2d6de;';
                                                        break;
                                                        default:
                                                            $labelStyle = 'background-color: #f39c12;';
                                                    }     
                                                ?>
                                                <span
                                                    style='<?= $labelStyle?> font-weight: 600; line-height: 1; color: #fff; text-align: center; border-radius: .25em; padding: .5em .3em .6em; font-size: 75%'>
                                                    <?= $item->trang_thai ?>
                                                </span>
                                                <span>
                                                    <?php if(!empty($item->ly_do)):?>
                                                        <?php 
                                                            $ly_do = json_decode($item->ly_do, true);
                                                            $ly_do_str = '';
                                                            foreach($ly_do as $ld) {
                                                                $time = date('H:i d-m-Y', $ld['time']);
                                                                $action = (isset($ld['action']) && !empty($ld['action'])) ? $ld['action'] : '';
                                                                $lydo = (isset($ld['lydo']) && !empty($ld['lydo'])) ? $ld['lydo'] : '';
                                                                $ghichu = (isset($ld['ghichu']) && !empty($ld['ghichu'])) ? $ld['ghichu'] : '';
                                                                $trangThai = (isset($ld['trangThai']) && !empty($ld['trangThai'])) ? $ld['trangThai'] : '';
                                                                if ($trangThai == $item->trang_thai) {
                                                                    $str = '*'.$action.' - '.$lydo.'<br>';
                                                                    $ly_do_str .= $str;
                                                                }
                                                            }
                                                        ?>
                                                        <p style='margin-top: 10px !important'><?= $ly_do_str?></p>
                                                    <?php endif;?>
                                                </span>
                                            </td>
                                            <td>
                                                <!--Modal chọn nhân viên-->
                                                <?php
                                                    $actionType = 'default';
                                                    $submitTypeValue = 'chonNvl';
                                                    $placeHolderChonNv = 'Chọn nhân viên lấy hàng...';
                                                    if ($item->trang_thai == 'Đang lấy') {
                                                        $actionType = 'nvlDangLay';
                                                        $nvlArr = json_decode($item->nhan_vien_lay_hang, true);
                                                        $nvl_ten = Admin::find()->where(['admin_id' => $nvlArr['id']])->one()['ten_hien_thi'];
                                                    } elseif ($item->trang_thai == 'Chờ giao lại' || $item->trang_thai == 'Đã lấy, chờ giao') {
                                                        $actionType = 'chonNvg';
                                                        $placeHolderChonNv = 'Chọn nhân viên giao hàng...';
                                                    } elseif ($item->trang_thai == 'Đang giao') {
                                                        $actionType = 'nvgDangGiao';
                                                        $nvgArr = json_decode($item->nhan_vien_giao_hang, true);
                                                        $nvg_ten = Admin::find()->where(['admin_id' => $nvgArr['id']])->one()['ten_hien_thi'];
                                                    } elseif ($item->trang_thai == 'Đã giao') {
                                                        $actionType = 'nvgDaGiao';
                                                    } elseif ($item->trang_thai == 'Huỷ đơn') {
                                                        $actionType = 'huyDon';
                                                    } elseif ($item->trang_thai == 'Chờ hoàn hàng' || $item->trang_thai == 'Chờ hoàn lại') {
                                                        $actionType = 'chonNvh';
                                                    } elseif ($item->trang_thai == 'Đang hoàn') {
                                                        $actionType = 'nvhDangHoan';
                                                        $nvhArr = json_decode($item->nhan_vien_hoan_hang, true);
                                                        $nvh_ten = Admin::find()->where(['admin_id' => $nvhArr['id']])->one()['ten_hien_thi'];
                                                    } elseif ($item->trang_thai == 'Đã hoàn') {
                                                        $actionType = 'nvhDaHoan';
                                                    } elseif ($item->trang_thai == 'Chờ duyệt') {
                                                        $actionType = 'choDuyet';
                                                    }
                                                    switch ($actionType) {
                                                        case 'nvlDangLay':
                                                            $submitTypeValue = 'chonNvlKhac';
                                                        break;
                                                        case 'chonNvg':
                                                            $submitTypeValue = 'chonNvg';
                                                        break;
                                                        case 'nvgDangGiao':
                                                            $submitTypeValue = 'chonNvgKhac';
                                                        break;
                                                        case 'nvgDaGiao':
                                                        break;
                                                        case 'chonNvh':
                                                            $submitTypeValue = 'chonNvh';
                                                        break;
                                                        case 'nvhDangHoan':
                                                            $submitTypeValue = 'chonNvhKhac';
                                                        break;
                                                        case 'huyDon':
                                                            $submitTypeValue = 'huyDon';
                                                        break;
                                                    }
                                                    Modal::begin([
                                                        'header'=> '<h3 style="text-align : center;">Chọn nhân viên</h3>',
                                                        'id'    => 'nv'.$item->dh_id,
                                                        'size'  => 'modal-sm',
                                                        'options' => [
                                                            'tabindex' => false
                                                        ]
                                                    ]);
                                                ?>  
                                                    <div class="row">
                                                        <div class="col-md-12 chon-nv-wrapper">
                                                            <?php 
                                                            $form = ActiveForm::begin([
                                                                'enableAjaxValidation' => false,
                                                                'options' => [
                                                                    'enctype' => 'multipart/form-data',
                                                                    'class' => 'model-form',
                                                                    'id' => 'form-nv'.$item->dh_id]
                                                            ]);
                                                            ?>
                                                            <div class="col-md-12">
                                                                <?= Select2::widget([
                                                                        'name' => 'nv_id',
                                                                        'data' => ArrayHelper::map(Admin::find()->all(), 'admin_id', 'ten_hien_thi'),
                                                                        'options' => [
                                                                            'placeholder' => $placeHolderChonNv,
                                                                            'style' => 'text-align: left',
                                                                            'class' => 'form-control col-md-12 chon-nv-input',
                                                                        ],
                                                                        'pluginOptions' => [
                                                                            'allowClear' => true
                                                                        ],
                                                                        'pluginEvents' => [
                                                                            "select2:select" => "function(e) { showHideError(e.target, false) }",
                                                                        ]
                                                                    ]);
                                                                ?>
                                                                <div style='display: none; text-align: center; background-color: red; margin-top: 8px' class='chon-nv-error'><span style='color: white'>Bạn chưa chọn nhân viên</span></div>
                                                            </div>
                                                            <div class="col-md-12" style="margin-top: 10px">
                                                                <label>Chọn ngày</label>        
                                                                <?= DatePicker::widget([
                                                                        'name' => 'nv_date',
                                                                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                                                        'value' => date('d-m-Y'),
                                                                        'size' => 'md',
                                                                        'pluginOptions' => [
                                                                            'autoclose'=>true
                                                                        ]
                                                                    ]);
                                                                ?>
                                                            </div>
                                                            <?php
                                                                $currentHour = date('H');
                                                                $currentMinute = date('i');
                                                                $totalCurrentMinute = $currentHour * 60 + $currentMinute;
                                                            ?>
                                                            <div class="col-md-6" style="margin-top: 10px">
                                                                <input type="radio" name="ca" value="sang" <?= $totalCurrentMinute <= ((12*60) + 30) ? 'checked' : '' ?>/>
                                                                <label style="padding-top: 6px">Buổi sáng</label>
                                                            </div>
                                                            <div class="col-md-6" style="margin-top: 10px">
                                                                <input type="radio" name="ca" value="chieu" <?= $totalCurrentMinute > ((12*60) + 30) ? 'checked' : '' ?>/>
                                                                <label style="padding-top: 6px">Buổi chiều</label>
                                                            </div>
                                                            <div class='col-md-12' style='margin-top: 10'>
                                                                <input type="hidden" name="dh_id" value="<?= $item->dh_id ?>"/>
                                                                <input type="hidden" name="dh_trang_thai" value="<?= $item->trang_thai?>"/>
                                                                <?= Html::submitButton("Chọn nhân viên", ['class' => 'btn btn-success btn-block btn-chon-nv', 'value' => $submitTypeValue, 'name' => 'smForm']) ?>
                                                            </div>
                                                            <?php ActiveForm::end(); ?>
                                                        </div>
                                                    </div>    
                                                <?php
                                                    Modal::end();
                                                ?>
                                                <!--End modal chọn nhân viên-->
                                                <!--Modal huỷ đơn-->
                                                <?php
                                                    Modal::begin([
                                                        'header'=> '<h3 style="text-align : center;">Huỷ đơn hàng</h3>',
                                                        'id'    => 'huydon'.$item->dh_id,
                                                        'size'  => 'modal-sm',
                                                        'options' => [
                                                            'tabindex' => false
                                                        ]
                                                    ]);
                                                ?>
                                                    <div class="row">
                                                        <?php 
                                                            $huyDonform = ActiveForm::begin([
                                                                'enableAjaxValidation' => false
                                                            ]);
                                                        ?>
                                                            <div class="col-md-12">
                                                                <textarea rows="4" class="col-md-12" placeHolder="Lý do huỷ đơn" name='ly_do_huy_don'></textarea>
                                                                <input type="hidden" name="dh_id" value="<?= $item->dh_id ?>"/>
                                                            </div>
                                                            <div class='col-md-12' style='margin-top: 10px'>
                                                                <?= Html::submitButton("Xác nhận", ['class' => 'btn btn-success btn-block', 'value' => 'huyDon', 'name' => 'smForm']) ?>
                                                            </div>
                                                        <?php ActiveForm::end();?>
                                                        
                                                    </div>    
                                                <?php Modal::end();?>
                                                <!--End modal huỷ đơn-->
                                                <!--Modal thêm phụ phí-->
                                                <?php
                                                    Modal::begin([
                                                        'header'=> '<h3 style="text-align : center;">Thêm phụ phí</h3>',
                                                        'id'    => 'phuphi'.$item->dh_id,
                                                        'size'  => 'modal-wide',
                                                        'options' => [
                                                            'tabindex' => false
                                                        ]
                                                    ]);
                                                ?>
                                                    <div class="row">
                                                        <?php 
                                                            $phuPhiform = ActiveForm::begin([
                                                                'enableAjaxValidation' => false
                                                            ]);
                                                        ?>
                                                            <div class="col-md-12">
                                                                <label for='phu_phi'>Số tiền</label>
                                                                <br>
                                                                <input required
                                                                    oninvalid="this.setCustomValidity('Bạn chưa nhập phụ phí')"
                                                                    oninput="setCustomValidity('')"         
                                                                    type='number'
                                                                    value='0'
                                                                    class="form-control"
                                                                    id='phu_phi'
                                                                    name='phu_phi'>
                                                            </div>
                                                            <div class="col-md-12" style='margin-top: 10px'>
                                                                <label>Ghi chú</label>
                                                                <br>
                                                                <textarea rows='3' class="form-control" style='width: 100%' name='ghi_chu_phu_phi'></textarea>
                                                            </div>
                                                            <div class='col-md-12' style='margin-top: 10px'>
                                                                <input type="hidden" name="dh_id" value="<?= $item->dh_id ?>"/> 
                                                                <?= Html::submitButton("Lưu", ['class' => 'btn btn-success btn-block', 'value' => 'phuphi', 'name' => 'smForm']) ?>
                                                            </div>
                                                        <?php ActiveForm::end();?>
                                                    </div>

                                                    <div class='row' style='margin-top: 20px'>
                                                        <div class='col-md-12'>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Số tiền</th>
                                                                        <th>Ghi chú</th>
                                                                        <th>Ngày tháng</th>
                                                                    </tr>
                                                                </thead>
                                                                <?php if (!empty($item->phu_phi)):?>
                                                                    <tbody>
                                                                        <?php 
                                                                            $arrPhuPhi = json_decode($item->phu_phi, true);
                                                                            foreach($arrPhuPhi as $phuPhi):
                                                                                $ghiChu = '';
                                                                                $ghiChuDate = '';
                                                                                if ($phuPhi['ghi_chu']) {
                                                                                    $ghiChu = $phuPhi['ghi_chu'];
                                                                                }
                                                                                $ghiChuDate = date('d-m-Y H:i:s', $phuPhi['ghi_chu_thoi_gian']);
                                                                                $phuphi = (int)$phuPhi['phu_phi'];
                                                                        ?>
                                                                            <tr>
                                                                                <td><?= number_format($phuphi, 0, '', ',').' VNĐ'?></td>
                                                                                <td><?= $ghiChu?></td>
                                                                                <td><?= $ghiChuDate?></td>
                                                                            </tr>
                                                                        <?php endforeach;?>
                                                                    </tbody>
                                                                <?php endif;?>
                                                            </table>
                                                        </div>
                                                    </div>    
                                                <?php Modal::end();?>
                                                <!--End Modal thêm phụ phí-->
                                                <!-- Modal hoàn hàng-->
                                                <?php
                                                    Modal::begin([
                                                        'header'=> '<h3 style="text-align : center;">Lý do hoàn hàng</h3>',
                                                        'id'    => 'hh'.$item->dh_id,
                                                        'size'  => 'modal-sm',
                                                        'options' => [
                                                            'tabindex' => false
                                                        ]
                                                    ]);
                                                ?>
                                                    <?php echo Html::beginForm('donhang/index', 'post');?>
                                                        <textarea
                                                            required
                                                            rows="4"
                                                            class="col-md-12"
                                                            style="margin-bottom: 8px"
                                                            placeHolder="Lý do hoàn hàng"
                                                            name='ly_do_hoan_hang'
                                                            oninvalid = 'this.setCustomValidity("Bạn chưa nhập lý do hoàn hàng")'
                                                            oninput = 'setCustomValidity("")'
                                                        ></textarea>
                                                        <input type='hidden' value='<?= $item->dh_id?>' name='dh_id'/>
                                                        <input type='hidden' value='<?= $item->trang_thai?>' name='dh_trang_thai' />
                                                    <?php echo Html::submitButton('<i class="glyphicon glyphicon-remove"></i><span> Hoàn hàng</span>', ['class' => 'btn btn-success btn-block', 'value' => 'hoanhang', 'name' => 'smForm', 'style' => 'margin-top: 8px']);?>
                                                    <?php echo Html::endForm();?>
                                                <?php Modal::end()?>
                                                <!-- End Modal hoàn hàng-->
                                                <?php if ($item->trang_thai != 'Đã hoàn'):?> <!--Trạng thái "Đã hoàn" thì không hiển thị bất cứ button nào-->
                                                    <?php if ($item->trang_thai != 'Chờ hoàn hàng' && $item->trang_thai != 'Đang hoàn'):?>
                                                        <div>
                                                            <a href='<?= $baseUrl."/donhang/edit/".$item->dh_id?>'
                                                            target="_blank"
                                                            type="button"
                                                            style="width:100%; margin-bottom: 3px"
                                                            class="btn btn-sm btn-default">
                                                                <i class="glyphicon glyphicon-pencil" style="vertical-align: baseline !important"></i> Sửa
                                                            </a>
                                                        </div>
                                                    <?php endif;?>
                                                    <?php if ($item->trang_thai == 'Đã duyệt chờ lấy' || $item->trang_thai == 'Đang lấy' || $item->trang_thai == 'Chờ lấy lại' || $item->trang_thai == 'Chờ duyệt'):?>
                                                        <div>
                                                            <a data-toggle='modal'
                                                                type="button"
                                                                style="width:100%; margin-bottom: 3px"
                                                                class="btn btn-sm btn-default"
                                                                data-target='#<?= 'huydon'.$item->dh_id?>'>
                                                                Huỷ đơn
                                                            </a> 
                                                        </div>
                                                    <?php elseif ($item->trang_thai != 'Chờ hoàn hàng' && $item->trang_thai != 'Đang hoàn'):?>
                                                        <button
                                                            data-toggle='modal'
                                                            data-target='#hh<?= $item->dh_id?>'
                                                            type="button"
                                                            style='width:100%; margin-bottom: 3px;'
                                                            class='btn btn-sm btn-default'
                                                        >
                                                        Hoàn hàng
                                                        </button>
                                                    <?php endif;?>
                                                <?php endif;?>
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
<?php JSRegister::begin(); ?>
<script>
    console.log('aaaaa')
    function showHideError(element, isShow) {
        const parent = $(element).parents('.chon-nv-wrapper')
        const errorWarning = $(parent).find('.chon-nv-error');
        if (isShow) {
            $(errorWarning).show();
        } else {
            $(errorWarning).hide();
        }
    }

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    // Validate chon nhan vien
    $('.btn-chon-nv').click(e => {
        const target = e.target
        const parent = $(target).parents('.chon-nv-wrapper')
        const elementValidate = $(parent).find('.chon-nv-input')
        const elementValidateValue = $(elementValidate).val();
        const errorWarning = $(parent).find('.chon-nv-error');
        if (elementValidateValue) {
            $(errorWarning).hide();
        } else {
            e.preventDefault()
            $(errorWarning).show();
        }
    })

    $('.dropdown-toggle').click(e => {
        const target = e.target;
        const parent = $(target).parents('.dropdown');
        if ($(parent).hasClass('open')) {
            setTimeout(() => {
                $(parent).removeClass('open');
            }, 88)
        } else {
            setTimeout(() => {
                $(parent).addClass('open')
            }, 88)
            
        }
    })

    const scroll = getParameterByName('scroll'); 
    const scrollID = `#${parseInt(scroll)}`;
    if ($(scrollID).offset() !== undefined) {
        $('html, body').animate({
            scrollTop: $(scrollID).offset().top - 50
        }, 500)
    }
</script>
<?php JSRegister::end(); ?>
