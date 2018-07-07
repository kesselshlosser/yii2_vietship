<?php
    use app\modules\khachhang\models\Khachhang;
    use yii\helpers\Html;
    use richardfan\widget\JSRegister;
?>

<?php $this->beginPage();?>
<html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <?= Html::csrfMetaTags() ?>
        <title>In đơn hàng</title>
<!--        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">-->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
    </head>
    
    <body>
    <?php $this->beginBody() ?>
    <div class='col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1' style='margin-top: 10px'>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4" style="border: 1px solid black">
                <?php 
                    $model_khach_hang = Khachhang::find()->where(['kh_id' => $model->kh_id])->one();
                    $ten_kh = $model_khach_hang['ten_hien_thi'];
                    $so_dien_thoai = $model_khach_hang['so_dien_thoai'];
                ?>
                <p><?= $ten_kh.' - '.$so_dien_thoai;?></p>
                <p><?= $model->dia_chi_lay_hang;?></p>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4" style='text-align: center; font-weight: bold'>
                <p>VIETSHIPVN.COM</p>
                <p>PHIẾU GIAO HÀNG</p>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4" style="border: 1px solid black">
                <p style='font-weight: bold'>Mã đơn hàng : <?= $model->ma_don_hang;?></p>
                <p><?= date('d-m-Y', $model->time);?></p>
            </div>
        </div>

        <div class="row" style='margin-top: 10px'>
            <div class='col-md-4 col-sm-4 col-xs-4' style='border : 1px solid black;'>
                <?php
                    $sanpham = json_decode($model->san_pham, true);
                    $ten_san_pham = $sanpham['ten'];
                    $so_luong = $sanpham['so_luong'];
                ?>
                <p><strong>Sản phẩm :</strong> <?= $ten_san_pham;?></p>
                <p><strong>Số lượng :</strong> <?= $so_luong;?></p>
            </div>
        </div>

        <div class='row' style='margin-top: 10px'>
            <?php
                $nguoinhan = json_decode($model->nguoi_nhan, true);
                $ten_nguoi_nhan = $nguoinhan['ten'];
                $dia_chi_giao_hang = $nguoinhan['dia_chi_giao_hang'];
                $nguoinhan_sdt = $nguoinhan['so_dien_thoai'];
            ?>

            <p>TÊN NGƯỜI NHẬN : <?= $ten_nguoi_nhan;?></p>
            <p>ĐỊA CHỈ : <?= $dia_chi_giao_hang;?></p>
            <p>SỐ ĐIỆN THOẠI : <?= $nguoinhan_sdt;?></p>
            <p>GHI CHÚ : <?= $model->ghi_chu;?></p>
        </div>

        <div class='row' style='margin-top: 10px'>
            <table style='border : 1px solid black; padding: 5px' class='col-md-12 col-sm-12 col-xs-12'>
                <tbody>
                    <tr style='margin-top: 10px'>
                        <td colspan="2" style='padding:5px'>
                            <p><strong>Tiền thu hộ : </strong><?php 
                                if($model->tien_thu_ho)
                                {
                                    echo $model->tien_thu_ho.' VNĐ';
                                }
                            ?></p>
                        </td>
                        <td rowspan="3" style='vertical-align: top; border: 1px solid black; border-bottom: none'>
                            <p style='text-align: center; font-family: "Times New Roman", Georgia, Serif; padding-top: 30px'><strong>KÝ NHẬN</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style='padding:5px'>
                            <p><strong>Cước vận chuyển : </strong>
                                <?php 
                                if($model->tong_tien)
                                    {
                                        echo $model->tong_tien.' VNĐ';
                                    }
                                ?>
                            </p>
                        </td>
<!--                        <td></td>-->
                    </tr>
                    <tr>
                        <td colspan="2" style='padding:5px'>
                            <p><strong>Hình thức thanh toán : </strong><?= $model->hinh_thuc_thanh_toan;?></p>
                        </td>
                        <!--<td></td>-->
                    </tr>
                    <tr>
                        <?php
                            $dvpt = json_decode($model->dich_vu_phu_troi, true);
                        ?>
                        <td colspan="2" style='padding:5px'>
                            <p><strong>Dịch vụ phụ trội : </strong></p>
                            <?php foreach($dvpt as $item):?>
                                <?php if($item['value'] == 1):?>
                                    <?php if($item['key'] == 'dvpt1'):?>
                                        <p style='padding-left: 10px'>
                                            -<?php
                                                $note = '';
                                                if($item['note'])
                                                {
                                                    $note = ' ('.$item['note'].')';
                                                }
                                                echo $item['content'].$note;
                                            ?> 
                                        </p>
                                    <?php elseif($item['key'] == 'dvpt2'):?>    
                                        <p style='padding-left: 10px'>
                                            -<?php
                                                $note = '';
                                                if($item['note'])
                                                {
                                                    $note = $item['note'].' giờ';
                                                }
                                                echo $item['content'].$note;
                                            ?> 
                                        </p>
                                    <?php elseif($item['key'] == 'dvpt3'):?>
                                        <p style='padding-left: 10px'>
                                            -<?php
                                                echo $item['content'];
                                            ?> 
                                        </p>
                                    <?php elseif($item['key'] == 'dvpt4'):?>
                                        <p style='padding-left: 10px'>
                                            -<?php
                                                $dai = $item['note']['dai'];
                                                $rong = $item['note']['rong'];
                                                $cao = $item['note']['cao'];
                                                $nang = $item['note']['nang'];
                                                $note = ' (dài : '.$dai.' cm rộng : '.$rong.' cm cao :'.$cao.' cm nặng :'.$nang.' kg)';
                                                echo $item['content'].$note;
                                            ?> 
                                        </p>
                                    <?php endif;?>
                                <?php endif;?>
                            <?php endforeach;?>
                        </td>
                        <td style='border: 1px solid black; border-top: none; vertical-align: bottom'>
                            <p style='font-style: italic; text-align: center;'>Đã nhận hàng</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>    
    <?php JSRegister::begin();?>
        <script>
            window.print();
        </script>
    <?php JSRegister::end();?>
    <?php $this->endBody();?>
    </body>    
</html>
<?php $this->endPage()?>

