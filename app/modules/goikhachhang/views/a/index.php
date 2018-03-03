<?php
use app\modules\goikhachhang\models\Goikhachhang;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\goidichvu\models\Goidichvu;
use kartik\switchinput\SwitchInput;
use richardfan\widget\JSRegister;
use yii\base\Component;

$this->title = "Gói khách hàng";

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
                        Danh sách gói khách hàng
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
                                            Tên gói
                                        </th>
                                        <th>
                                            Thời gian áp dụng
                                        </th>
                                        <th>
                                            Gói áp dụng
                                        </th>
                                        <th>
                                            Khu vực
                                        </th>
                                        <th>
                                            Giá trị
                                        </th>
                                        <th>
                                            Trạng thái
                                        </th>
                                        <th width="120px">
                                            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->models as $item):?>
                                        <tr data-id="<?= $item->primaryKey ?>">                                            
                                            <td>
                                                <a style="color : blue !important;" href="<?= Url::to(['/admin/'.$module.'/a/edit/', 'id' => $item->primaryKey]) ?>"><?= $item->ten_goi ?></a>
                                            </td>
                                            <td>
                                                <?php
                                                    if($item->hour_gio_ap_dung) //Hiển thị ngày bắt đầu áp dụng và thời gian
                                                    {
                                                        echo 'Ngày áp dụng : '.date('d/m/Y', $item->hour_thoi_gian_ap_dung).'<br>';
                                                        echo 'Thời gian áp dụng : '.$item->hour_gio_ap_dung.' giờ';
                                                    }else //KM theo ngày hiển thị ngày bắt đầu kết thúc
                                                    {
                                                        echo 'Từ '.date('d/m/Y', $item->day_ngay_bat_dau).' đến '.date('d/m/Y', $item->day_ngay_ket_thuc);
                                                    }
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
                                                    $str_kv = '';
                                                    $arr_kv = json_decode($item->khu_vuc, true);
                                                    foreach($arr_kv as $kv)
                                                    {
                                                        if($kv['value'] == 1)
                                                        {
                                                            $str_kv .= app\modules\khuvuc\models\Khuvuc::find()->where(['kv_id' => $kv['id']])->one()['ten_khu_vuc'].'<br>';
                                                        }
                                                        
                                                    }
                                                    echo $str_kv;
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?php
                                                    $giatri = '';
                                                    if($item->hinh_thuc == 'Giảm theo %')
                                                    {
                                                        $giatri = 'Giảm '.$item->gia_tri.' %';
                                                    }else if($item->hinh_thuc == 'Đồng giá')
                                                    {
                                                        $giatri = "Đồng giá ".$item->gia_tri.' VNĐ';
                                                    }else if($item->hinh_thuc == 'Giảm cước')
                                                    {
                                                        $giatri = "Giảm ".$item->gia_tri.' VNĐ';
                                                    }else if($item->hinh_thuc == 'Tặng tiền')
                                                    {
                                                        $giatri = "Tặng ".$item->gia_tri.' VNĐ';
                                                    }
                                                    echo $giatri;
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?=
                                                    SwitchInput::widget([
                                                        'name' => 'status',
                                                        'value' => $item->status,
                                                        'pluginOptions' => [
                                                            'size' => 'small'
                                                        ],
                                                        'pluginEvents' => [
                                                            "init.bootstrapSwitch" => "function() { console.log('init'); }",
                                                            "switchChange.bootstrapSwitch" => "function() { setStatus(".$item->gkh_id.") }",
                                                        ]
                                                    ])
                                                ?>
                                            </td>
                                            
                                            <td style="text-align: center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>" class="btn btn-default" title="Sửa gói khách hàng"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="Xóa gói khách hàng" onclick="return confirm('Bạn muốn xóa gói khách hàng này?');"><span class="glyphicon glyphicon-trash"></span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>Không tìm thấy gói khách hàng nào</p>
                        <?php endif;?>
                    </div>
                </section>
            </div>

        </div>
                
        </div>
    </div>
</div>

<?php JSRegister::begin();?>
    <script>
        function setStatus(gkh_id)
        {
            var url = '<?= Url::to(['/admin/goikhachhang/a/changestatus'])?>'
            var dataPost = { gkh_id : gkh_id };
            $.post(
                url,
                dataPost
            )
            .done((response) => {
                console.log(response);
            })
            .fail((e) => {
                console.log(e);
            })
        }
    </script>
<?php JSRegister::end();?>