<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\easyii\models\Admin;
use app\modules\khachhang\models\Khachhang;
use yii\bootstrap\Modal;
use richardfan\widget\JSRegister;
use \app\modules\goidichvu\models\Goidichvu;

$this->title = "Hoá đơn thanh toán";

?>
<style>
    .font14 {
        font-size: 14px;
        font-weight: 600;
    }
    .money {
        font-size: 16px;
        background-color: #f39c12;
        height: 20px;
        border-radius: 0.25em;
        color: #FFF;
        padding-left: 8px;
        padding-right: 8px;
    }
    .td14 {
        font-size: 14px;
    }
    .table thead tr, .table tbody tr td {
        vertical-align: baseline !important
    }
    .header {
        text-align: center
    }
    .bold {
        font-weight: bold
    }
    .left {
        text-align: left;
    }
    .right {
        text-align: right;
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
        
        <?php
            if (isset($models)):
        ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <section class="panel">
                        <header class="panel-heading panel-border">
                            Danh sách hoá đơn
                            <span class="tools pull-right">
                                <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <?php
                                $so_hoa_don_cho_xu_ly = count($models_choxuly);
                                $so_hoa_don_dang_thanh_toan = count($models_dangtt);
                                $so_hoa_don_da_thanh_toan = count($models_datt);
                            ?>
                            <div class="row" style='margin-top: 10px'>
                                <div class='col-md-12'>
                                    <ul class="nav nav-tabs" id='tab-ul'>
                                        <li class="active" id='tab-hd-cho-xu-ly'>
                                            <a data-toggle="tab" href="#choxuly" id='child-tab-cho-xu-ly'>
                                                Hoá đơn chờ xử lý 
                                                <?php if ($so_hoa_don_cho_xu_ly > 0):?>
                                                    <span class="badge badge-danger"><?= $so_hoa_don_cho_xu_ly?></span>
                                                <?php endif;?>
                                            </a>
                                        </li>
                                        <li id='tab-hd-dang-tt'>
                                            <a data-toggle="tab" href="#dangtt" id='child-tab-dang-tt'>
                                                Hoá đơn đang thanh toán 
                                                <?php if ($so_hoa_don_dang_thanh_toan > 0):?>
                                                    <span class="badge badge-danger"><?= $so_hoa_don_dang_thanh_toan?></span>
                                                <?php endif;?>
                                            </a>
                                        </li>
                                        <li id='tab-hd-datt'>
                                            <a data-toggle="tab" href="#datt" id='child-tab-da-tt'>
                                                Hoá đơn đã thanh toán 
                                                <?php if ($so_hoa_don_da_thanh_toan > 0):?>
                                                    <span class="badge badge-danger"><?= $so_hoa_don_da_thanh_toan?></span>
                                                <?php endif;?>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="choxuly" class="tab-pane fade in active">
                                        <?php if(isset($models_choxuly) && count($models_choxuly) > 0):?>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class='header'>Mã hoá đơn</th>
                                                    <th class='header'>Trạng thái hóa đơn</th>
                                                    <th class='header'>Ngày tháng</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($models_choxuly as $model):?>
                                                        <tr>
                                                            <td class='font14 header'>
                                                                <?php
                                                                    Modal::begin([
                                                                        'header'=> '<h3 style="text-align : center;">Thông tin chi tiết hoá đơn</h3>',
                                                                        'id'    => 'hd'.$model['id'],
                                                                        'size'  => 'modal-lg',
                                                                    ])
                                                                ?>
                                                                <div class='row'>
                                                                    <div class='col-md-12 col-sm-12 col-xs-12 modal-wrapper'>
                                                                        <div class='row'>
                                                                            <div class='col-md-4 col-sm-4 col-xs-4'>
                                                                                <p class='left'>Mã hoá đơn: <?= $model['ma_hoa_don']?></p>
                                                                                <p class='left'>Ngày lập: <?= date('d-m-Y', $model['time'])?></p>
                                                                            </div>
                                                                            <div class='col-md-8 col-sm-8 col-xs-8 lich-trinh-don-wrapper'>
                                                                                <div class='row'>
                                                                                    <button
                                                                                        style='float: right; margin-right: 8px'
                                                                                        class='btn btn-success btn-lich-trinh-don'
                                                                                    >
                                                                                        Lịch trình đơn
                                                                                    </button>
                                                                                </div>
                                                                                <div class='row lich-trinh-don' style='display: none'>
                                                                                    <?php if (!empty($model['ly_do_chua_thanh_toan'])):?>
                                                                                        <?php $ly_do_chua_thanh_toan = json_decode($model['ly_do_chua_thanh_toan'], true);
                                                                                            foreach ($ly_do_chua_thanh_toan as $key => $value):
                                                                                                $ldctt_nv_id = isset($value['nv_id']) ? $value['nv_id'] : '';
                                                                                                $nv = Admin::find()->where(['admin_id' => $ldctt_nv_id])->one();
                                                                                                $ldctt_nv_ten = isset($nv['ten_hien_thi']) ? $nv['ten_hien_thi'] : '';
                                                                                                $ldctt_status = isset($value['status']) ? $value['status'] : '';
                                                                                                $ldctt_ly_do = isset($value['ly_do']) ? $value['ly_do'] : '';
                                                                                                $ldctt_time = isset($value['time']) ? date('H:i d-m-Y', $value['time']) : '';
                                                                                        ?>
                                                                                            <p>*Thanh toán lần <?= $key + 1?> vào <?= $ldctt_time?> - <?= $ldctt_status?> - Nhân viên <?= $ldctt_nv_id.'_'.$ldctt_nv_ten?> - <?= $ldctt_status == 'Thành công' ? '' : $ldctt_ly_do?></p>
                                                                                        <?php endforeach;?>
                                                                                    <?php endif;?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class='row'>
                                                                            <table style='display:none' class="table table-bordered table-striped table-chi-tiet-hoa-don" id="cho-xu-ly<?= $model['id']?>">
                                                                                <thead>
                                                                                    <tr style='text-align: center'>
                                                                                        <td colspan="11">Vietship thanh toán tiền thu hộ ngày <?= date('d/m/Y')?></td>
                                                                                    </tr>
                                                                                    <tr style='text-align: center'>
                                                                                        <td colspan="11">   
                                                                                            <?php 
                                                                                                $model_kh = Khachhang::find()->where(['kh_id' => $model['kh_id']])->one();
                                                                                                $kh_ten = $model_kh['ten_hien_thi'];
                                                                                                $kh_sdt = $model_kh['so_dien_thoai'];
                                                                                                $kh_dia_chi = $model_kh['dia_chi'];
                                                                                                echo '('.$kh_ten.' - '.$kh_sdt.' - '.$kh_dia_chi.')';
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th class='header'>STT</th>
                                                                                        <th class='header'>Mã vận đơn</th>
                                                                                        <th class='header'>Ngày tháng</th>
                                                                                        <th class='header'>Địa chỉ</th>
                                                                                        <th class='header'>Gói cước vận chuyển</th>
                                                                                        <th class='header'>Khu vực giao hàng</th>
                                                                                        <th class='header'>Phương thức trả ship</th>
                                                                                        <th class='header'>Tiền ship</th>
                                                                                        <th class='header'>Tiền thu hộ</th>
                                                                                        <th class='header'>Chuyển trả khách</th>
                                                                                        <th class='header'>Ghi chú</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php $tong_chuyen_tra_khach = 0?>
                                                                                    <?php foreach($model['hoadonchitiet'] as $key => $hdct):?>
                                                                                        <tr>
                                                                                            <td class='font14'>
                                                                                                <?= $key + 1?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['ma_don_hang']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= date('H:i d/m/Y', $hdct['time'])?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= Goidichvu::find()->where(['gdv_id' => $hdct['gdv_id']])->one()['ten_goi_dich_vu']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['hinh_thuc_thanh_toan']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['tong_tien'] > 0 ? number_format($hdct['tong_tien'], 0, '', ',').' VNĐ' : 0?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?php
                                                                                                    $tien_thu_ho = $hdct['tien_thu_ho'] && $hdct['tien_thu_ho'] > 0 ? number_format($hdct['tien_thu_ho'], 0, '', ',').' VNĐ' : '0 VNĐ';
                                                                                                    echo $tien_thu_ho;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?php
                                                                                                    $httt = $hdct['hinh_thuc_thanh_toan'];
                                                                                                    switch($httt) {
                                                                                                        case 'Người gửi thanh toán':
                                                                                                        case 'Người nhận thanh toán':
                                                                                                        case 'Thanh toán sau':
                                                                                                            $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                            $chuyen_tra_khach = $tien_thu_ho;  
                                                                                                        break;
                                                                                                        case 'Thanh toán sau COD':
                                                                                                            $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                            $tong_tien = $hdct['tong_tien'] > 0 ? $hdct['tong_tien'] : 0;
                                                                                                            $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                                                                                            $chuyen_tra_khach = $tien_thu_ho_phai_tra;
                                                                                                        break;
                                                                                                    }
                                                                                                    echo $chuyen_tra_khach > 0 ? number_format($chuyen_tra_khach, 0, '', ',').' VNĐ' : 0;
                                                                                                    $tong_chuyen_tra_khach += $chuyen_tra_khach;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                            
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endforeach;?>    
                                                                                    <tr style='text-align: center'>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td colspan="3">
                                                                                            Số dư
                                                                                        </td>
                                                                                        <td>
                                                                                            0
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr style='text-align: center'>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td colspan="2">
                                                                                            Xác nhận của khách hàng
                                                                                        </td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td colspan="3">
                                                                                            Số nợ
                                                                                        </td>
                                                                                        <td rowspan="3">0</td>
                                                                                    </tr>  
                                                                                    <tr>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td colspan="2">Thanh toán sau</td>
                                                                                        <td>0 VNĐ</td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td colspan="2">Hoàn hàng</td>
                                                                                        <td>0 VNĐ</td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td colspan="3">TỔNG</td>
                                                                                        <td><?= $tong_chuyen_tra_khach. 'VNĐ'?></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>

                                                                            <table class="table table-bordered table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class='header'>STT</th>
                                                                                        <th class='header'>Mã vận đơn</th>
                                                                                        <th class='header'>Ngày tháng</th>
                                                                                        <th class='header'>Địa chỉ</th>
                                                                                        <th class='header'>Gói cước vận chuyển</th>
                                                                                        <th class='header'>Khu vực giao hàng</th>
                                                                                        <th class='header'>Phương thức trả ship</th>
                                                                                        <th class='header'>Tiền ship</th>
                                                                                        <th class='header'>Tiền thu hộ</th>
                                                                                        <th class='header'>Chuyển trả khách</th>
                                                                                        <th class='header'>Ghi chú</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php $tong_chuyen_tra_khach = 0?>
                                                                                    <?php foreach($model['hoadonchitiet'] as $key => $hdct):?>
                                                                                        <tr>
                                                                                            <td class='font14'>
                                                                                                <?= $key + 1?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['ma_don_hang']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= date('H:i d/m/Y', $hdct['time'])?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= Goidichvu::find()->where(['gdv_id' => $hdct['gdv_id']])->one()['ten_goi_dich_vu']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['hinh_thuc_thanh_toan']?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?= $hdct['tong_tien'] > 0 ? number_format($hdct['tong_tien'], 0, '', ',').' VNĐ' : 0?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?php
                                                                                                    $tien_thu_ho = $hdct['tien_thu_ho'] && $hdct['tien_thu_ho'] > 0 ? number_format($hdct['tien_thu_ho'], 0, '', ',').' VNĐ' : '0 VNĐ';
                                                                                                    echo $tien_thu_ho;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                                <?php
                                                                                                    $httt = $hdct['hinh_thuc_thanh_toan'];
                                                                                                    switch($httt) {
                                                                                                        case 'Người gửi thanh toán':
                                                                                                        case 'Người nhận thanh toán':
                                                                                                        case 'Thanh toán sau':
                                                                                                            $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                            $chuyen_tra_khach = $tien_thu_ho;  
                                                                                                        break;
                                                                                                        case 'Thanh toán sau COD':
                                                                                                            $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                            $tong_tien = $hdct['tong_tien'] > 0 ? $hdct['tong_tien'] : 0;
                                                                                                            $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                                                                                            $chuyen_tra_khach = $tien_thu_ho_phai_tra;
                                                                                                        break;
                                                                                                    }
                                                                                                    echo $chuyen_tra_khach > 0 ? number_format($chuyen_tra_khach, 0, '', ',').' VNĐ' : 0;
                                                                                                    $tong_chuyen_tra_khach += $chuyen_tra_khach;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td class='font14'>
                                                                                            
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endforeach;?>    
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class='row'>
                                                                            <div class='col-md-6 col-sm-6 col-xs-12'>
                                                                                <div class='col-md-6 col-sm-6 col-xs-6'>
                                                                                    <p class='bold left'>Số dư</p>
                                                                                    <p class='bold left'>Số nợ</p>
                                                                                    <p class='bold left'>Tổng</p>
                                                                                </div>
                                                                                <div class='col-md-6 col-sm-6 col-xs-6'>
                                                                                    <?php
                                                                                        $so_du = 0;
                                                                                        $so_no = 0;
                                                                                        $model_kh = Khachhang::find()->with('donhang')->where(['kh_id' => $model['kh_id']])->asArray()->one();
                                                                                        $model_dh = $model_kh['donhang'];
                                                                                        if (count($model_dh) > 0) {
                                                                                            foreach($model_dh as $dh) {
                                                                                                $so_no += (int)$dh['so_no'];
                                                                                            }
                                                                                        }
                                                                                        $tong = $tong_chuyen_tra_khach + $so_du - $so_no;
                                                                                    ?>
                                                                                    <p class='right'>
                                                                                        <?= $so_du > 0 ? number_format($so_du, 0, '', ',').' VNĐ' : '0 VNĐ'?>
                                                                                    </p>
                                                                                    <p class='right'>
                                                                                        <?= $so_no > 0 ? number_format($so_no, 0, '', ',').' VNĐ' : '0 VNĐ'?>
                                                                                    </p>
                                                                                    <p class='right'>
                                                                                        <?= number_format($tong, 0, '', ',').' VNĐ'?>
                                                                                    </p>
                                                                                    <p class='right'>
                                                                                        <button
                                                                                            type="button"
                                                                                            style='margin-top: 3px;'
                                                                                            class='btn btn-sm btn-default btn-xuat-excel'
                                                                                        >
                                                                                        <i class='glyphicon glyphicon-log-out' style="vertical-align: baseline !important"></i>
                                                                                        Xuất excel
                                                                                        </button>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php Modal::end()?>
                                                                <?= $model['ma_hoa_don']?>
                                                                <button
                                                                    data-toggle='modal'
                                                                    data-target='#hd<?= $model['id']?>'
                                                                    type="button"
                                                                    style='margin-top: 3px;'
                                                                    class='btn btn-sm btn-success btn-block'
                                                                >
                                                                <i class='glyphicon glyphicon-th-list' style="vertical-align: baseline !important"></i>
                                                                Xem chi tiết
                                                                </button>
                                                            </td>
                                                            <td class='font14 header' id='tt-<?= $model['id']?>'>
                                                                <?= $model['trang_thai']?>
                                                            </td>
                                                            <td class='font14 header'>
                                                                <?= date('H:i d-m-Y', $model['time'])?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        <?php endif;?>    
                                        </div>
                                        <div id="dangtt" class="tab-pane fade">
                                        <?php if(isset($models_dangtt) && count($models_dangtt) > 0):?>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class='header'>Mã hoá đơn</th>
                                                    <th class='header'>Trạng thái hóa đơn</th>
                                                    <th class='header'>Ngày tháng</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($models_dangtt as $model):?>
                                                        <tr>
                                                            <td class='font14 header'>
                                                                <?php
                                                                    Modal::begin([
                                                                        'header'=> '<h3 style="text-align : center;">Thông tin chi tiết hoá đơn</h3>',
                                                                        'id'    => 'hd'.$model['id'],
                                                                        'size'  => 'modal-lg',
                                                                    ])
                                                                ?>
                                                                <div class='row'>
                                                                    <div class='col-md-12 col-sm-12 col-xs-12 modal-wrapper'>
                                                                        <div class='row'>
                                                                            <div class='col-md-4 col-sm-4 col-xs-4'>
                                                                                <p class='left'>Mã hoá đơn: <?= $model['ma_hoa_don']?></p>
                                                                                <p class='left'>Ngày lập: <?= date('d-m-Y', $model['time'])?></p>
                                                                            </div>
                                                                            <div class='col-md-8 col-sm-8 col-xs-8 lich-trinh-don-wrapper' style='display: none'>
                                                                                <div class='row'>
                                                                                    <button
                                                                                        style='float: right; margin-right: 8px'
                                                                                        class='btn btn-success btn-lich-trinh-don'
                                                                                    >
                                                                                        Lịch trình đơn
                                                                                    </button>
                                                                                </div>
                                                                                <div class='row lich-trinh-don' style='display: none'>
                                                                                    <?php if (!empty($model['ly_do_chua_thanh_toan'])):?>
                                                                                        <?php $ly_do_chua_thanh_toan = json_decode($model['ly_do_chua_thanh_toan'], true);
                                                                                            foreach ($ly_do_chua_thanh_toan as $key => $value):
                                                                                                $ldctt_nv_id = isset($value['nv_id']) ? $value['nv_id'] : '';
                                                                                                $nv = Admin::find()->where(['admin_id' => $ldctt_nv_id])->one();
                                                                                                $ldctt_nv_ten = isset($nv['ten_hien_thi']) ? $nv['ten_hien_thi'] : '';
                                                                                                $ldctt_status = isset($value['status']) ? $value['status'] : '';
                                                                                                $ldctt_ly_do = isset($value['ly_do']) ? $value['ly_do'] : '';
                                                                                                $ldctt_time = isset($value['time']) ? date('H:i d-m-Y', $value['time']) : '';
                                                                                        ?>
                                                                                            <p>*Thanh toán lần <?= $key + 1?> vào <?= $ldctt_time?> - <?= $ldctt_status?> - Nhân viên <?= $ldctt_nv_id.'_'.$ldctt_nv_ten?> - <?= $ldctt_status == 'Thành công' ? '' : $ldctt_ly_do?></p>
                                                                                        <?php endforeach;?>
                                                                                    <?php endif;?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--Table Excel-->
                                                                        <table style='display:none' class="table table-bordered table-striped table-chi-tiet-hoa-don" id="dang-thanh-toan<?= $model['id']?>">
                                                                            <thead>
                                                                                <tr style='text-align: center'>
                                                                                    <td colspan="11">Vietship thanh toán tiền thu hộ ngày <?= date('d/m/Y')?></td>
                                                                                </tr>
                                                                                <tr style='text-align: center'>
                                                                                    <td colspan="11">   
                                                                                        <?php 
                                                                                            $model_kh = Khachhang::find()->where(['kh_id' => $model['kh_id']])->one();
                                                                                            $kh_ten = $model_kh['ten_hien_thi'];
                                                                                            $kh_sdt = $model_kh['so_dien_thoai'];
                                                                                            $kh_dia_chi = $model_kh['dia_chi'];
                                                                                            echo '('.$kh_ten.' - '.$kh_sdt.' - '.$kh_dia_chi.')';
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th class='header'>STT</th>
                                                                                    <th class='header'>Mã vận đơn</th>
                                                                                    <th class='header'>Ngày tháng</th>
                                                                                    <th class='header'>Địa chỉ</th>
                                                                                    <th class='header'>Gói cước vận chuyển</th>
                                                                                    <th class='header'>Khu vực giao hàng</th>
                                                                                    <th class='header'>Phương thức trả ship</th>
                                                                                    <th class='header'>Tiền ship</th>
                                                                                    <th class='header'>Tiền thu hộ</th>
                                                                                    <th class='header'>Chuyển trả khách</th>
                                                                                    <th class='header'>Ghi chú</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php $tong_chuyen_tra_khach = 0?>
                                                                                <?php foreach($model['hoadonchitiet'] as $key => $hdct):?>
                                                                                    <tr>
                                                                                        <td class='font14'>
                                                                                            <?= $key + 1?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['ma_don_hang']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= date('H:i d/m/Y', $hdct['time'])?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= Goidichvu::find()->where(['gdv_id' => $hdct['gdv_id']])->one()['ten_goi_dich_vu']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['hinh_thuc_thanh_toan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['tong_tien'] > 0 ? number_format($hdct['tong_tien'], 0, '', ',').' VNĐ' : 0?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $tien_thu_ho = $hdct['tien_thu_ho'] && $hdct['tien_thu_ho'] > 0 ? number_format($hdct['tien_thu_ho'], 0, '', ',').' VNĐ' : '0 VNĐ';
                                                                                                echo $tien_thu_ho;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $httt = $hdct['hinh_thuc_thanh_toan'];
                                                                                                switch($httt) {
                                                                                                    case 'Người gửi thanh toán':
                                                                                                    case 'Người nhận thanh toán':
                                                                                                    case 'Thanh toán sau':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho;  
                                                                                                    break;
                                                                                                    case 'Thanh toán sau COD':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $tong_tien = $hdct['tong_tien'] > 0 ? $hdct['tong_tien'] : 0;
                                                                                                        $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho_phai_tra;
                                                                                                    break;
                                                                                                }
                                                                                                echo $chuyen_tra_khach > 0 ? number_format($chuyen_tra_khach, 0, '', ',').' VNĐ' : 0;
                                                                                                $tong_chuyen_tra_khach += $chuyen_tra_khach;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                        
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach;?>    
                                                                                <tr style='text-align: center'>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="3">
                                                                                        Số dư
                                                                                    </td>
                                                                                    <td>
                                                                                        0
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style='text-align: center'>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="2">
                                                                                        Xác nhận của khách hàng
                                                                                    </td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="3">
                                                                                        Số nợ
                                                                                    </td>
                                                                                    <td rowspan="3">0</td>
                                                                                </tr>  
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="2">Thanh toán sau</td>
                                                                                    <td>0 VNĐ</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="2">Hoàn hàng</td>
                                                                                    <td>0 VNĐ</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="3">TỔNG</td>
                                                                                    <td><?= $tong_chuyen_tra_khach. 'VNĐ'?></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class='header'>STT</th>
                                                                                    <th class='header'>Mã vận đơn</th>
                                                                                    <th class='header'>Ngày tháng</th>
                                                                                    <th class='header'>Địa chỉ</th>
                                                                                    <th class='header'>Gói cước vận chuyển</th>
                                                                                    <th class='header'>Khu vực giao hàng</th>
                                                                                    <th class='header'>Phương thức trả ship</th>
                                                                                    <th class='header'>Tiền ship</th>
                                                                                    <th class='header'>Tiền thu hộ</th>
                                                                                    <th class='header'>Chuyển trả khách</th>
                                                                                    <th class='header'>Ghi chú</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach($model['hoadonchitiet'] as $key => $hdct):?>
                                                                                    <tr>
                                                                                        <td class='font14'>
                                                                                            <?= $key + 1?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['ma_don_hang']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= date('H:i d/m/Y', $hdct['time'])?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= Goidichvu::find()->where(['gdv_id' => $hdct['gdv_id']])->one()['ten_goi_dich_vu']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['hinh_thuc_thanh_toan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['tong_tien'] > 0 ? number_format($hdct['tong_tien'], 0, '', ',').' VNĐ' : 0?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $tien_thu_ho = $hdct['tien_thu_ho'] && $hdct['tien_thu_ho'] > 0 ? number_format($hdct['tien_thu_ho'], 0, '', ',').' VNĐ' : '0 VNĐ';
                                                                                                echo $tien_thu_ho;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $httt = $hdct['hinh_thuc_thanh_toan'];
                                                                                                switch($httt) {
                                                                                                    case 'Người gửi thanh toán':
                                                                                                    case 'Người nhận thanh toán':
                                                                                                    case 'Thanh toán sau':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho;  
                                                                                                    break;
                                                                                                    case 'Thanh toán sau COD':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $tong_tien = $hdct['tong_tien'] > 0 ? $hdct['tong_tien'] : 0;
                                                                                                        $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho_phai_tra;
                                                                                                    break;
                                                                                                }
                                                                                                echo $chuyen_tra_khach > 0 ? number_format($chuyen_tra_khach, 0, '', ',').' VNĐ' : 0;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                        
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach;?>        
                                                                            </tbody>
                                                                        </table>
                                                                        <div class='col-md-6 col-sm-6 col-xs-12'>
                                                                            <div class='col-md-6 col-sm-6 col-xs-6'>
                                                                                <p class='bold left'>Số dư</p>
                                                                                <p class='bold left'>Số nợ</p>
                                                                                <p class='bold left'>Tổng</p>
                                                                            </div>
                                                                            <div class='col-md-6 col-sm-6 col-xs-6'>
                                                                                <?php
                                                                                    $so_du = 0;
                                                                                    $so_no = 0;
                                                                                    $model_kh = Khachhang::find()->with('donhang')->where(['kh_id' => $model['kh_id']])->asArray()->one();
                                                                                    $model_dh = $model_kh['donhang'];
                                                                                    if (count($model_dh) > 0) {
                                                                                        foreach($model_dh as $dh) {
                                                                                            $so_no += (int)$dh['so_no'];
                                                                                        }
                                                                                    }
                                                                                    $tong = $tong_chuyen_tra_khach + $so_du - $so_no;
                                                                                ?>
                                                                                <p class='right'>
                                                                                    <?= $so_du > 0 ? number_format($so_du, 0, '', ',').' VNĐ' : '0 VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= $so_no > 0 ? number_format($so_no, 0, '', ',').' VNĐ' : '0 VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= number_format($tong, 0, '', ',').' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <button
                                                                                        data-toggle='modal'
                                                                                        data-target=''
                                                                                        type="button"
                                                                                        style='margin-top: 3px;'
                                                                                        class='btn btn-sm btn-default btn-xuat-excel'
                                                                                    >
                                                                                    <i class='glyphicon glyphicon-log-out' style="vertical-align: baseline !important"></i>
                                                                                    Xuất excel
                                                                                    </button>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php Modal::end()?>
                                                                <?= $model['ma_hoa_don']?>
                                                                <button
                                                                    data-toggle='modal'
                                                                    data-target='#hd<?= $model['id']?>'
                                                                    type="button"
                                                                    style='margin-top: 3px;'
                                                                    class='btn btn-sm btn-success btn-block'
                                                                >
                                                                <i class='glyphicon glyphicon-th-list' style="vertical-align: baseline !important"></i>
                                                                Xem chi tiết
                                                                </button>
                                                            </td>
                                                            <td class='font14 header' id='tt-<?= $model['id']?>'>
                                                                <?= $model['trang_thai']?>
                                                            </td>
                                                            <td class='font14 header'>
                                                                <?= date('H:i d-m-Y', $model['time'])?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                
                                                </tbody>
                                            </table>
                                            <?php endif;?>
                                        </div>
                                        <div id="datt" class="tab-pane fade">
                                        <?php if(isset($models_datt) && count($models_datt) > 0):?>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class='header'>Mã hoá đơn</th>
                                                    <th class='header'>Trạng thái hóa đơn</th>
                                                    <th class='header'>Ngày tháng</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($models_datt as $model):?>
                                                        <tr>
                                                            <td class='font14 header'>
                                                                <?php
                                                                    Modal::begin([
                                                                        'header'=> '<h3 style="text-align : center;">Thông tin chi tiết hoá đơn</h3>',
                                                                        'id'    => 'hd'.$model['id'],
                                                                        'size'  => 'modal-lg',
                                                                    ])
                                                                ?>
                                                                <div class='row'>
                                                                    <div class='col-md-12 col-sm-12 col-xs-12 modal-wrapper'>
                                                                        <div class='row'>
                                                                            <div class='col-md-4 col-sm-4 col-xs-4'>
                                                                                <p class='left'>Mã hoá đơn: <?= $model['ma_hoa_don']?></p>
                                                                                <p class='left'>Ngày lập: <?= date('d-m-Y', $model['time'])?></p>
                                                                            </div>
                                                                            <div class='col-md-8 col-sm-8 col-xs-8 lich-trinh-don-wrapper' style='display: none'>
                                                                                <div class='row'>
                                                                                    <button
                                                                                        style='float: right; margin-right: 8px'
                                                                                        class='btn btn-success btn-lich-trinh-don'
                                                                                    >
                                                                                        Lịch trình đơn
                                                                                    </button>
                                                                                </div>
                                                                                <div class='row lich-trinh-don' style='display: none'>
                                                                                    <?php if (!empty($model['ly_do_chua_thanh_toan'])):?>
                                                                                        <?php $ly_do_chua_thanh_toan = json_decode($model['ly_do_chua_thanh_toan'], true);
                                                                                            foreach ($ly_do_chua_thanh_toan as $key => $value):
                                                                                                $ldctt_nv_id = isset($value['nv_id']) ? $value['nv_id'] : '';
                                                                                                $nv = Admin::find()->where(['admin_id' => $ldctt_nv_id])->one();
                                                                                                $ldctt_nv_ten = isset($nv['ten_hien_thi']) ? $nv['ten_hien_thi'] : '';
                                                                                                $ldctt_status = isset($value['status']) ? $value['status'] : '';
                                                                                                $ldctt_ly_do = isset($value['ly_do']) ? $value['ly_do'] : '';
                                                                                                $ldctt_time = isset($value['time']) ? date('H:i d-m-Y', $value['time']) : '';
                                                                                        ?>
                                                                                            <p>*Thanh toán lần <?= $key + 1?> vào <?= $ldctt_time?> - <?= $ldctt_status?> - Nhân viên <?= $ldctt_nv_id.'_'.$ldctt_nv_ten?> - <?= $ldctt_status == 'Thành công' ? '' : $ldctt_ly_do?></p>
                                                                                        <?php endforeach;?>
                                                                                    <?php endif;?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--Table Excel-->
                                                                        <table style='display:none' class="table table-bordered table-striped table-chi-tiet-hoa-don" id="da-thanh-toan<?= $model['id']?>">
                                                                            <thead>
                                                                                <tr style='text-align: center'>
                                                                                    <td colspan="11">Vietship thanh toán tiền thu hộ ngày <?= date('d/m/Y')?></td>
                                                                                </tr>
                                                                                <tr style='text-align: center'>
                                                                                    <td colspan="11">   
                                                                                        <?php 
                                                                                            $model_kh = Khachhang::find()->where(['kh_id' => $model['kh_id']])->one();
                                                                                            $kh_ten = $model_kh['ten_hien_thi'];
                                                                                            $kh_sdt = $model_kh['so_dien_thoai'];
                                                                                            $kh_dia_chi = $model_kh['dia_chi'];
                                                                                            echo '('.$kh_ten.' - '.$kh_sdt.' - '.$kh_dia_chi.')';
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th class='header'>STT</th>
                                                                                    <th class='header'>Mã vận đơn</th>
                                                                                    <th class='header'>Ngày tháng</th>
                                                                                    <th class='header'>Địa chỉ</th>
                                                                                    <th class='header'>Gói cước vận chuyển</th>
                                                                                    <th class='header'>Khu vực giao hàng</th>
                                                                                    <th class='header'>Phương thức trả ship</th>
                                                                                    <th class='header'>Tiền ship</th>
                                                                                    <th class='header'>Tiền thu hộ</th>
                                                                                    <th class='header'>Chuyển trả khách</th>
                                                                                    <th class='header'>Ghi chú</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php $tong_chuyen_tra_khach = 0?>
                                                                                <?php foreach($model['hoadonchitiet'] as $key => $hdct):?>
                                                                                    <tr>
                                                                                        <td class='font14'>
                                                                                            <?= $key + 1?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['ma_don_hang']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= date('H:i d/m/Y', $hdct['time'])?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= Goidichvu::find()->where(['gdv_id' => $hdct['gdv_id']])->one()['ten_goi_dich_vu']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['hinh_thuc_thanh_toan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['tong_tien'] > 0 ? number_format($hdct['tong_tien'], 0, '', ',').' VNĐ' : 0?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $tien_thu_ho = $hdct['tien_thu_ho'] && $hdct['tien_thu_ho'] > 0 ? number_format($hdct['tien_thu_ho'], 0, '', ',').' VNĐ' : '0 VNĐ';
                                                                                                echo $tien_thu_ho;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $httt = $hdct['hinh_thuc_thanh_toan'];
                                                                                                switch($httt) {
                                                                                                    case 'Người gửi thanh toán':
                                                                                                    case 'Người nhận thanh toán':
                                                                                                    case 'Thanh toán sau':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho;  
                                                                                                    break;
                                                                                                    case 'Thanh toán sau COD':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $tong_tien = $hdct['tong_tien'] > 0 ? $hdct['tong_tien'] : 0;
                                                                                                        $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho_phai_tra;
                                                                                                    break;
                                                                                                }
                                                                                                echo $chuyen_tra_khach > 0 ? number_format($chuyen_tra_khach, 0, '', ',').' VNĐ' : 0;
                                                                                                $tong_chuyen_tra_khach += $chuyen_tra_khach;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                        
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach;?>    
                                                                                <tr style='text-align: center'>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="3">
                                                                                        Số dư
                                                                                    </td>
                                                                                    <td>
                                                                                        0
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style='text-align: center'>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="2">
                                                                                        Xác nhận của khách hàng
                                                                                    </td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="3">
                                                                                        Số nợ
                                                                                    </td>
                                                                                    <td rowspan="3">0</td>
                                                                                </tr>  
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="2">Thanh toán sau</td>
                                                                                    <td>0 VNĐ</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="2">Hoàn hàng</td>
                                                                                    <td>0 VNĐ</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="3">TỔNG</td>
                                                                                    <td><?= $tong_chuyen_tra_khach. 'VNĐ'?></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class='header'>STT</th>
                                                                                    <th class='header'>Mã vận đơn</th>
                                                                                    <th class='header'>Ngày tháng</th>
                                                                                    <th class='header'>Địa chỉ</th>
                                                                                    <th class='header'>Gói cước vận chuyển</th>
                                                                                    <th class='header'>Khu vực giao hàng</th>
                                                                                    <th class='header'>Phương thức trả ship</th>
                                                                                    <th class='header'>Tiền ship</th>
                                                                                    <th class='header'>Tiền thu hộ</th>
                                                                                    <th class='header'>Chuyển trả khách</th>
                                                                                    <th class='header'>Ghi chú</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach($model['hoadonchitiet'] as $key => $hdct):?>
                                                                                    <tr>
                                                                                        <td class='font14'>
                                                                                            <?= $key + 1?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['ma_don_hang']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= date('H:i d/m/Y', $hdct['time'])?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= Goidichvu::find()->where(['gdv_id' => $hdct['gdv_id']])->one()['ten_goi_dich_vu']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['dia_chi_nguoi_nhan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['hinh_thuc_thanh_toan']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['tong_tien'] > 0 ? number_format($hdct['tong_tien'], 0, '', ',').' VNĐ' : 0?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $tien_thu_ho = $hdct['tien_thu_ho'] && $hdct['tien_thu_ho'] > 0 ? number_format($hdct['tien_thu_ho'], 0, '', ',').' VNĐ' : '0 VNĐ';
                                                                                                echo $tien_thu_ho;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?php
                                                                                                $httt = $hdct['hinh_thuc_thanh_toan'];
                                                                                                switch($httt) {
                                                                                                    case 'Người gửi thanh toán':
                                                                                                    case 'Người nhận thanh toán':
                                                                                                    case 'Thanh toán sau':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho;  
                                                                                                    break;
                                                                                                    case 'Thanh toán sau COD':
                                                                                                        $tien_thu_ho = $hdct['tien_thu_ho'] > 0 ? $hdct['tien_thu_ho'] : 0;
                                                                                                        $tong_tien = $hdct['tong_tien'] > 0 ? $hdct['tong_tien'] : 0;
                                                                                                        $tien_thu_ho_phai_tra = $tien_thu_ho - $tong_tien;
                                                                                                        $chuyen_tra_khach = $tien_thu_ho_phai_tra;
                                                                                                    break;
                                                                                                }
                                                                                                echo $chuyen_tra_khach > 0 ? number_format($chuyen_tra_khach, 0, '', ',').' VNĐ' : 0;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                        
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach;?>        
                                                                            </tbody>
                                                                        </table>
                                                                        <div class='col-md-6 col-sm-6 col-xs-12'>
                                                                            <div class='col-md-6 col-sm-6 col-xs-6'>
                                                                                <p class='bold left'>Số dư</p>
                                                                                <p class='bold left'>Số nợ</p>
                                                                                <p class='bold left'>Tổng</p>
                                                                            </div>
                                                                            <div class='col-md-6 col-sm-6 col-xs-6'>
                                                                                <?php
                                                                                    $so_du = 0;
                                                                                    $so_no = 0;
                                                                                    $model_kh = Khachhang::find()->with('donhang')->where(['kh_id' => $model['kh_id']])->asArray()->one();
                                                                                    $model_dh = $model_kh['donhang'];
                                                                                    if (count($model_dh) > 0) {
                                                                                        foreach($model_dh as $dh) {
                                                                                            $so_no += (int)$dh['so_no_da_thanh_toan'];
                                                                                        }
                                                                                    }
                                                                                    $tong = $tong_chuyen_tra_khach + $so_du - $so_no;
                                                                                ?>
                                                                                <p class='right'>
                                                                                    <?= $so_du > 0 ? number_format($so_du, 0, '', ',').' VNĐ' : '0 VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= $so_no > 0 ? number_format($so_no, 0, '', ',').' VNĐ' : '0 VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= number_format($tong, 0, '', ',').' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <a id="dlink"  style="display:none;"></a>
                                                                                    <button
                                                                                        type="button"
                                                                                        style='margin-top: 3px;'
                                                                                        class='btn btn-sm btn-default btn-xuat-excel'
                                                                                    >
                                                                                    <i class='glyphicon glyphicon-log-out' style="vertical-align: baseline !important"></i>
                                                                                    Xuất excel
                                                                                    </button>
                                                                                </p>
                                                                            </div>
                                                                        </div>                                                                        
                                                                    </div>
                                                                </div>
                                                                <?php Modal::end()?>
                                                                <?= $model['ma_hoa_don']?>
                                                                <button
                                                                    data-toggle='modal'
                                                                    data-target='#hd<?= $model['id']?>'
                                                                    type="button"
                                                                    style='margin-top: 3px;'
                                                                    class='btn btn-sm btn-success btn-block'
                                                                >
                                                                <i class='glyphicon glyphicon-th-list' style="vertical-align: baseline !important"></i>
                                                                Xem chi tiết
                                                                </button>
                                                            </td>
                                                            <td class='font14 header' id='tt-<?= $model['id']?>'>
                                                                <?= $model['trang_thai']?>
                                                            </td>
                                                            <td class='font14 header'>
                                                                <?= date('H:i d-m-Y', $model['time'])?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        <?php
            endif;
        ?>
    </div>
</div>

<?php JSRegister::begin()?>
    <script>
        console.log('hoa don')
        // Ẩn hiện lịch trình đơn
        $('.btn-lich-trinh-don').click(e => {
            const target = e.target;
            const wrapper = $(target).parents('.lich-trinh-don-wrapper')
            const lichTrinhDon = $(wrapper).find('.lich-trinh-don')
            if ($(lichTrinhDon).is(':hidden')) {
                $(lichTrinhDon).show();
            } else {
                $(lichTrinhDon).hide();
            }
        })
        // Chọn nhân viên thanh toán hoá đơn
        $('.chon-nv-submit').click(e => {
            console.log('Duyet thanh toan')
            var target = e.target
            var divContainer = $(target).parents('.form-chon-nv')
            var nv = $(divContainer).find('.chon-nv').val()
            var date = $(divContainer).find('.chon-nv-date').val()
            // var caElement = $(divContainer).find('.chon-nv-ca')
            var id = $(divContainer).find('.hd-id').val()
            var ca = $(`input[name=ca${id}]:checked`, '.chon-nv-ca').val()
            var url = '<?= Url::to(['/admin/admins/chon-nhan-vien-thanh-toan'])?>';
            var data = "nv_id=" + nv + "&date=" + date + "&ca=" + ca + "&hd_id=" + id
            ajax(url, data, response => {
                console.log(response)
                var message = response.message
                var hd_id = response.hd_id
                var new_trang_thai = response.new_trang_thai
                if (message === 'success') {
                    var hd_trang_thai = $('#tt-'+hd_id)
                    $(hd_trang_thai).html(new_trang_thai)
                    swal("Thành công!", "Chọn nhân viên thanh toán thành công", "success")
                    .then((value) => {
                        window.location.reload();
                    });
                } else {
                    swal("Thất bại!", "Có lỗi trong quá trình chọn nhân viên thanh toán", "error");
                }
            })
        })

        // Export excel
        $('.btn-xuat-excel').click(e => {
            const target = e.target;
            const parent = $(e.target).parents('.modal-wrapper')
            const table = $(parent).find('.table-chi-tiet-hoa-don')
            const tableID = $(table).attr('id');
            tableToExcel(tableID, 'HOA DON CHI TIET', `hoadonchitiet.xls`)
        })

        // Function AJAX
        function ajax(url, data, cb)
        {
            $.post(
                url,
                data
            )
            .done((response) => {
                // Cập nhật giá cho đơn hàng
                const result = JSON.parse(response)
                cb && cb(result)
            })
            .fail((e) => {
                console.log(e);
            })
        }
        // End function ajax

        // Function export to excel
        var tableToExcel = (function () {
            var uri = 'data:application/vnd.ms-excel;base64,'
            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
            var base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
            var format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            return function (table, name, filename) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }

                var link = document.createElement('a');
                link.download = filename;
                link.href = uri + base64(format(template, ctx));
                link.click();

            }
        })()
    </script>
<?php JSRegister::end()?>
