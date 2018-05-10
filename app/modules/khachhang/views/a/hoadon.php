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
                            <div class="row" style='margin-top: 10px'>
                                <div class='col-md-12'>
                                    <ul class="nav nav-tabs" id='tab-ul'>
                                        <li id='tab-hd-gan-nhat'>
                                            <a data-toggle="tab" href="#gannhat" id='child-tab-gan-nhat'>
                                                Hoá đơn gần nhất
                                            </a>
                                        </li>
                                        <li class="active" id='tab-hd-dang-tt'>
                                            <a data-toggle="tab" href="#dangtt" id='child-tab-dang-tt'>
                                                Hoá đơn đang thanh toán
                                            </a>
                                        </li>
                                        <li id='tab-hd-datt'>
                                            <a data-toggle="tab" href="#datt" id='child-tab-da-tt'>
                                                Hoá đơn đã thanh toán
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="gannhat" class="tab-pane fade">
                                        </div>
                                        <div id="dangtt" class="tab-pane fade in active">
                                        <?php if(isset($models_dangtt) && count($models_dangtt) > 0):?>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class='header'>Mã hoá đơn</th>
                                                    <th class='header'>Tên khách hàng</th>
                                                    <th class='header'>Số điện thoại</th>
                                                    <th class='header'>Địa chỉ khách hàng</th>
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
                                                                    <div class='col-md-12 col-sm-12 col-xs-12'>
                                                                        <p class='left'>Mã hoá đơn: <?= $model['ma_hoa_don']?></p>
                                                                        <p class='left'>Ngày lập: <?= date('d-m-Y', $model['time'])?></p>
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
                                                                                            <?= $hdct['tong_tien'].' VNĐ' ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['tien_thu_ho'].' VNĐ'?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $model['chuyen_tra_khach'].' VNĐ'?>
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
                                                                                    $model_kh = Khachhang::find()->where(['kh_id' => $model['kh_id']])->one();
                                                                                    $sodu = $model_kh['sodu'];
                                                                                    $sono = $model_kh['sodu'];
                                                                                    $tong = 0;
                                                                                ?>
                                                                                <p class='right'>
                                                                                    <?= $sodu.' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= $sono.' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= $tong.' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <button
                                                                                        data-toggle='modal'
                                                                                        data-target=''
                                                                                        type="button"
                                                                                        style='margin-top: 3px;'
                                                                                        class='btn btn-sm btn-default'
                                                                                    >
                                                                                    <i class='glyphicon glyphicon-log-out' style="vertical-align: baseline !important"></i>
                                                                                    Xuất excel
                                                                                    </button>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class='col-md-6 col-sm-6 col-xs-12 form-chon-nv'>
                                                                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                                                            <?php
                                                                                echo Select2::widget(
                                                                                    [
                                                                                        'name' => 'chon-nv',
                                                                                        'data' => ArrayHelper::map(Admin::find()->all(), 'admin_id', 'ten_hien_thi'),
                                                                                        'options' => [
                                                                                            'placeholder' => 'Chọn n/v thanh toán',
                                                                                            'id'=>'chon-nv'.$model['id'],
                                                                                            'class'=>'chon-nv'
                                                                                        ],
                                                                                        'pluginOptions' => [
                                                                                           'allowClear' => true
                                                                                        ],
                                                                                    ]
                                                                                )
                                                                            ?>
                                                                            </div>

                                                                            <div class='col-md-12 col-sm-12 col-xs-12' style='text-align: left; margin-top: 2px'>
                                                                                <label>Chọn ngày</label>        
                                                                                <?= DatePicker::widget([
                                                                                        'name'=>'nv_date',
                                                                                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                                                                        'value' => date('d-m-Y'),
                                                                                        'size' => 'md',
                                                                                        'options' => [
                                                                                            'class'=>'chon-nv-date',
                                                                                        ],
                                                                                        'pluginOptions' => [
                                                                                            'autoclose'=>true
                                                                                        ]
                                                                                    ]);
                                                                                ?>
                                                                            </div>

                                                                            <div class='col-md-12 col-sm-12 col-xs-12 chon-nv-ca'>
                                                                                <?php
                                                                                    $currentHour = date('H');
                                                                                    $currentMinute = date('i');
                                                                                    $totalCurrentMinute = $currentHour * 60 + $currentMinute;
                                                                                ?>
                                                                                <div class="col-md-6" style="margin-top: 4px">
                                                                                    <input type="radio" name="ca" value="sang" <?= $totalCurrentMinute <= ((12*60) + 30) ? 'checked' : '' ?>/>
                                                                                    <label style="padding-top: 8px">Buổi sáng</label>
                                                                                </div>
                                                                                <div class="col-md-6" style="margin-top: 4px">
                                                                                    <input type="radio" name="ca" value="chieu" <?= $totalCurrentMinute > ((12*60) + 30) ? 'checked' : '' ?>/>
                                                                                    <label style="padding-top: 8px">Buổi chiều</label>
                                                                                </div>
                                                                            </div>

                                                                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                                                                <input class='hd-id' type='hidden' value='<?= $model['id']?>'/>
                                                                                <button
                                                                                    class='chon-nv-submit btn btn-success btn-block'
                                                                                >
                                                                                Duyệt thanh toán
                                                                                </button>
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
                                                            <td class='font14'>
                                                                <?php
                                                                    $model_kh = Khachhang::find()->where(['kh_id' => $model['kh_id']])->one();
                                                                    $kh_ten = $model_kh['ten_hien_thi'];
                                                                    $kh_sdt = $model_kh['so_dien_thoai'];
                                                                    $kh_dia_chi = $model_kh['dia_chi'];
                                                                    echo $kh_ten;
                                                                ?>
                                                            </td>
                                                            <td class='font14 header'>
                                                                <?= $kh_sdt?>
                                                            </td>
                                                            <td class='font14'>
                                                                <?= $kh_dia_chi?>
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
                                                    <th class='header'>Tên khách hàng</th>
                                                    <th class='header'>Số điện thoại</th>
                                                    <th class='header'>Địa chỉ khách hàng</th>
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
                                                                    <?php
                                                                        Modal::begin([
                                                                            'header'=> '<h3 style="text-align : center;">Chọn nhân viên thanh toán</h3>',
                                                                            'id'    => 'nv'.$model['id'],
                                                                            'size'  => 'modal-sm',
                                                                        ])
                                                                    ?>
                                                                    Hello World
                                                                    <?php Modal::end()?>
                                                                    <div class='col-md-12 col-sm-12 col-xs-12'>
                                                                        <p class='left'>Mã hoá đơn: <?= $model['ma_hoa_don']?></p>
                                                                        <p class='left'>Ngày lập: <?= date('d-m-Y', $model['time'])?></p>
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
                                                                                            <?= $hdct['tong_tien'] ?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $hdct['tien_thu_ho']?>
                                                                                        </td>
                                                                                        <td class='font14'>
                                                                                            <?= $model['chuyen_tra_khach']?>
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
                                                                                    $model_kh = Khachhang::find()->where(['kh_id' => $model['kh_id']])->one();
                                                                                    $sodu = $model_kh['sodu'];
                                                                                    $sono = $model_kh['sodu'];
                                                                                    $tong = 0;
                                                                                ?>
                                                                                <p class='right'>
                                                                                    <?= $sodu.' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= $sono.' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <?= $tong.' VNĐ'?>
                                                                                </p>
                                                                                <p class='right'>
                                                                                    <button
                                                                                        data-toggle='modal'
                                                                                        data-target=''
                                                                                        type="button"
                                                                                        style='margin-top: 3px;'
                                                                                        class='btn btn-sm btn-default'
                                                                                    >
                                                                                    <i class='glyphicon glyphicon-log-out' style="vertical-align: baseline !important"></i>
                                                                                    Xuất excel
                                                                                    </button>
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-md-6 col-sm-6 col-xs-12 form-chon-nv'>
                                                                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                                                                <?php
                                                                                    echo Select2::widget(
                                                                                        [
                                                                                            'name' => 'chon-nv',
                                                                                            'data' => ArrayHelper::map(Admin::find()->all(), 'admin_id', 'ten_hien_thi'),
                                                                                            'options' => [
                                                                                                'placeholder' => 'Chọn n/v thanh toán',
                                                                                                'id'=>'chon-nv'.$model['id'],
                                                                                                'class'=>'chon-nv'
                                                                                            ],
                                                                                            'pluginOptions' => [
                                                                                            'allowClear' => true
                                                                                            ],
                                                                                        ]
                                                                                    )
                                                                                ?>
                                                                            </div>

                                                                            <div class='col-md-12 col-sm-12 col-xs-12' style='text-align: left; margin-top: 2px'>
                                                                                <label>Chọn ngày</label>        
                                                                                <?= DatePicker::widget([
                                                                                        'name'=>'nv_date',
                                                                                        'class'=>'chon-nv-date',
                                                                                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                                                                        'value' => date('d-m-Y'),
                                                                                        'size' => 'md',
                                                                                        'options' => [
                                                                                            'class'=>'chon-nv-date',
                                                                                        ],
                                                                                        'pluginOptions' => [
                                                                                            'autoclose'=>true
                                                                                        ]
                                                                                    ]);
                                                                                ?>
                                                                            </div>

                                                                            <div class='col-md-12 col-sm-12 col-xs-12 chon-nv-ca'>
                                                                                <?php
                                                                                    $currentHour = date('H');
                                                                                    $currentMinute = date('i');
                                                                                    $totalCurrentMinute = $currentHour * 60 + $currentMinute;
                                                                                ?>
                                                                                <div class="col-md-6" style="margin-top: 4px">
                                                                                    <input type="radio" name="ca" value="sang" <?= $totalCurrentMinute <= ((12*60) + 30) ? 'checked' : '' ?>/>
                                                                                    <label style="padding-top: 8px">Buổi sáng</label>
                                                                                </div>
                                                                                <div class="col-md-6" style="margin-top: 4px">
                                                                                    <input type="radio" name="ca" value="chieu" <?= $totalCurrentMinute > ((12*60) + 30) ? 'checked' : '' ?>/>
                                                                                    <label style="padding-top: 8px">Buổi chiều</label>
                                                                                </div>
                                                                            </div>

                                                                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                                                                <input class='hd-id' type='hidden' value='<?= $model['id']?>'/>
                                                                                <button
                                                                                    class='chon-nv-submit btn btn-success btn-block'
                                                                                >
                                                                                Duyệt thanh toán
                                                                                </button>
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
                                                            <td class='font14'>
                                                                <?php
                                                                    $model_kh = Khachhang::find()->where(['kh_id' => $model['kh_id']])->one();
                                                                    $kh_ten = $model_kh['ten_hien_thi'];
                                                                    $kh_sdt = $model_kh['so_dien_thoai'];
                                                                    $kh_dia_chi = $model_kh['dia_chi'];
                                                                    echo $kh_ten;
                                                                ?>
                                                            </td>
                                                            <td class='font14 header'>
                                                                <?= $kh_sdt?>
                                                            </td>
                                                            <td class='font14'>
                                                                <?= $kh_dia_chi?>
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
        $('.chon-nv-submit').click(e => {
            console.log('Duyet thanh toan')
            var target = e.target
            var divContainer = $(target).parents('.form-chon-nv')
            var nv = $(divContainer).find('.chon-nv').val()
            var date = $(divContainer).find('.chon-nv-date').val()
            // var caElement = $(divContainer).find('.chon-nv-ca')
            var ca = $('input[name=ca]:checked', '.chon-nv-ca').val()
            var id = $(divContainer).find('.hd-id').val()
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
                    swal("Thành công!", "Chọn nhân viên thanh toán thành công", "success");
                } else {
                    swal("Thất bại!", "Có lỗi trong quá trình chọn nhân viên thanh toán", "error");
                }
            })
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
    </script>
<?php JSRegister::end()?>
