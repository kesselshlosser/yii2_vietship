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

$this->title = "Thanh toán";

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
        <?php
            if (isset($models)):
        ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <section class="panel">
                        <header class="panel-heading panel-border">
                            Thông tin thanh toán
                            <span class="tools pull-right">
                                <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <div class='row' style='margin-top: 10px'>
                                <div class='col-md-5'>
                                    Tổng tiền chưa được thanh toán: <?= !empty($tong_tien_nhan_lai) && $tong_tien_nhan_lai > 0 ? number_format($tong_tien_nhan_lai, 0, '', ',').' VNĐ' : 0?>
                                </div>

                                <div class='col-md-2'>
                                    Số dư: <?= !empty($so_du) && $so_du > 0 ? number_format($so_du, 0, '', ',').' VNĐ' : 0;?>    
                                </div>

                                <div class='col-md-2'>
                                    Số nợ: <?= !empty($so_no) && $so_no > 0 ? number_format($so_no, 0, '', ',').' VNĐ' : 0;?>    
                                </div>

                                <div class='col-md-3'>
                                    <p>Thông tin thanh toán</p>
                                    <p><?php echo 'Tiền mặt'?></p>
                                </div>
                            </div>

                            <div class="row" style='margin-top: 10px'>
                                <div class='col-md-12'>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class='header'>Mã đơn hàng</th>
                                                <th class='header'>Thời gian tạo đơn</th>
                                                <th class='header'>Địa chỉ người nhận</th>
                                                <th class='header'>Gói cước</th>
                                                <th class='header'>Khu vực</th>
                                                <th class='header'>Phương thức trả ship</th>
                                                <th class='header'>Tiền ship</th>
                                                <th class='header'>Tiền thu hộ</th>
                                                <th class='header'>Tiền nhận lại</th>
                                                <th class='header'>Trạng thái</th>
                                                <th class='header'>Thời gian thanh toán</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($models as $model):?>
                                                <tr>
                                                    <td class='font14 header'>
                                                        <?= $model['ma_don_hang']?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= date('H:i d/m/Y', $model['thoi_gian_tao_don'])?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= $model['dia_chi_nguoi_nhan']?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= $model['goi_cuoc']?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= $model['khu_vuc']?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= $model['phuong_thuc_tra_ship']?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= !empty($model['tien_ship']) && $model['tien_ship'] > 0 ? number_format($model['tien_ship'], 0, '', ',').' VNĐ' : 0?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= !empty($model['tien_thu_ho']) && $model['tien_thu_ho'] > 0 ? number_format($model['tien_thu_ho'], 0, '', ',').' VNĐ' : 0?>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= !empty($model['tien_nhan_lai']) && $model['tien_nhan_lai'] > 0 ? number_format($model['tien_nhan_lai'], 0, '', ',').' VNĐ' : 0?>
                                                    </td>
                                                    
                                                    <?php
                                                        switch($model['trang_thai']) {
                                                            case 'Chưa thanh toán':
                                                                $color = 'red';
                                                            break;
                                                            case 'Đang thanh toán':
                                                                $color = 'orange';
                                                            break;
                                                            case 'Đã thanh toán':
                                                                $color = 'green';
                                                            break;
                                                        }
                                                    ?>
                                                    <td class='font14 header'>
                                                        <p style='background-color: <?= $color?>; color: white'><?= $model['trang_thai']?></p>
                                                    </td>

                                                    <td class='font14 header'>
                                                        <?= date('H:i d/m/Y', $model['thoi_gian_tao_don'])?>
                                                    </td>
                                                </tr>
                                            <?php endforeach?>
                                        </tbody>
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
    </script>
<?php JSRegister::end();?>
