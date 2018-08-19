<?php
    use kartik\date\DatePicker;
    use richardfan\widget\JSRegister;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use \dosamigos\chartjs\ChartJs;
    $pie_chart_color = [
        [
            'color' => 'black',
            'label' => 'Đã duyệt chờ lấy'
        ],
        [
            'color' => '#4982BB',
            'label' => 'Đã giao'
        ],
        [
            'color' => '#6DE81C',
            'label' => 'Không phát được/hoàn'
        ],
        [
            'color' => '#5C6093',
            'label' => 'Chờ giao'
        ],
        [
            'color' => '#53B8D7',
            'label' => 'Đang giao'
        ],
        [
            'color' => '#F5144D',
            'label' => 'Huỷ/không lấy được'
        ],
        [
            'color' => 'green',
            'label' => 'Chờ giao'
        ],
    ]
?>

<style>
    .pie-chart {
        position: absolute;
        top: 50%;
        left: 30%;
        width: 40%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
    .pie-chart-color {
        position: absolute;
        top: 50%;
        left: 75%;
        width: 25%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
    .no-chart-data {    
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
    .btnSubmit {
        color: #ffffff;
        background-color: #23b9a9;
        border-color: #23b9a9;
        display: block;
        width: 100%;
        padding: 8px 0px 8px 0px
    }
    .header-wrapper {
        height: 48px;
        background-color: #337ab7;
        line-height: 48px;
    }
    .left {
        text-align: left
    }
    .right {
        text-align: right
    }
    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        right: 0px;
        padding: 8px;
        background-color: white;
        font-size: 16px;
        width: 200px
    }
    .active {
        display: inline-block;
    }
    .inactive {
        display: none;
    }
    .chart-wrapper {
        /* padding: 16px; */
        background-color: #D8D8D8;
        height: 400px
    }
    .chart-content {
        background-color: white;
        margin: 8px;
        height: 384px;
        position: 'relative'
    }
    .data-wrapper {
        /* padding: 16px; */
        background-color: #D8D8D8;
        height: 400px
    }
    .data-content {
        margin: 8px 8px 8px 0px
    }
    .filter-icon {
        color: white
    }
    .box-text {
        display: inline-block;
        vertical-align: middle;
        line-height: normal;
    }
    .box-padding {
        padding: 0px 4px 4px 0px
    }
    .info-text {
        height: 72px;
        line-height: 72px;
        text-align: center
    }
    .info-white {
        background-color: white
    }
    .info-black {
        background-color: black
    }
    .info-blue {
        background-color: #58ACFA
    }
    .info-violet {
        background-color: #9A2EFE
    }
    .info-yellow {
        background-color: #FFBF00
    }
    .text20 {
        font-size: 20px;
        font-family: Verdana,sans-serif;
    }
    .text-normal {
        font-size: 16px
    }
    .text-blue {
        color: #58ACFA
    }
    .text-white {
        color: white
    }
</style>

<div class='col-md-12 col-sm-12 col-xs-12'>
    <!--Header-->
    <div class='row'>
        <div class='col-md-12 col-sm-12 col-xs-12 header-wrapper'>
            <div class='col-md-6 col-sm-6 col-xs-6 left'>
                <span class='text20 text-white'>
                    Thống kê vận đơn và doanh thu
                </span>
            </div>

            <div class='col-md-6 col-sm-6 col-xs-6 right'>
                <span class='text20 text-white'>
                    Từ ngày <span id='header-from-date'><?= $from?></span> - <span id='header-to-date'><?= $to?></span>
                </span>
                <i class="fa fa-filter fa-2x filter-icon dropdown">
                    <div class="dropdown-content inactive">
                        <button
                            class='btn-default btn-block btn-filter'
                            id='btn-hom-qua'
                            filter-type='homqua'
                        >
                            Hôm qua
                        </button>

                        <button
                            class='btn-default btn-block btn-filter'
                            id='btn-hom-nay'
                            filter-type='homnay'
                        >
                            Hôm nay
                        </button>

                        <button
                            class='btn-success btn-block btn-filter'
                            id='btn-7-ngay-truoc'
                            filter-type='bayngaytruoc'
                        >
                            7 ngày trước
                        </button>

                        <button
                            class='btn-default btn-block btn-filter'
                            id='btn-30-ngay-truoc'
                            filter-type='bamuoingaytruoc'
                        >
                            30 ngày trước
                        </button>

                        <button
                            class='btn-default btn-block btn-filter'
                            id='btn-thang-nay'
                            filter-type='thangnay'
                        >
                            Tháng này
                        </button>

                        <button
                            class='btn-default btn-block btn-filter'
                            id='btn-custom-range'
                        >
                            Custom range
                        </button>

                        <?php echo Html::beginForm('thongke', 'post');?>                                                        
                            <br/>
                            <div style='text-align: left'>
                                <label style='color: black;'>FROM</label>   
                                <?= DatePicker::widget([
                                        'name' => 'fromDate',
                                        'type' => DatePicker::TYPE_INPUT,
                                        'value' => date('d-m-Y'),
                                        'size' => 'md',
                                        'options' => [
                                            'id' => 'from'
                                        ],
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy',
                                        ]
                                    ]);
                                ?>
                            </div>

                            <br/>
                            <div style='text-align: left'>
                                <label style='color: black'>TO</label>      
                                <?= DatePicker::widget([
                                        'name' => 'toDate',
                                        'type' => DatePicker::TYPE_INPUT,
                                        'value' => date('d-m-Y'),
                                        'size' => 'md',
                                        'options' => [
                                            'id' => 'to'
                                        ],
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy',
                                        ]
                                    ]);
                                ?>
                            </div>
                        <?php echo Html::submitButton('<i class="glyphicon glyphicon-ok"></i><span> Apply</span>', ['class' => 'btnSubmit', 'style' => 'margin-top: 8px']);?>
                        <?php echo Html::endForm();?>
                    </div>
                </i>
            </div>
        </div>
    </div>
    <!--End header-->

    <!--Content-->
    <div class='row'>
        <div class='col-md-7 col-sm-7 col-xs-7 chart-wrapper' style='padding-left: 0px !important'>
            <div class='chart-content'>
                <?php if ($model['tong_so_don'] > 0):?>
                    <div class='pie-chart'>
                        <?php
                            echo ChartJs::widget([
                                'type' => 'pie',
                                'id' => 'structurePie',
                                'options' => [
                                    'height' => 240,
                                    'width' => '100%'
                                ],
                                'data' => [
                                    // 'radius' =>  "90%",
                                    'labels' => $model['pie_chart']['labels'], // Your labels
                                    'datasets' => [
                                        [
                                            'data' => $model['pie_chart']['data'], // Your dataset
                                            'label' => '',
                                            'backgroundColor' => $model['pie_chart']['color'],
                                            'borderColor' =>  [
                                                    '#fff',
                                                    '#fff',
                                                    '#fff'
                                            ],
                                            'borderWidth' => 1,
                                            'hoverBorderColor'=>["#999","#999","#999"],                
                                        ]
                                    ]
                                ],
                                'clientOptions' => [
                                    'legend' => [
                                        'display' => false,
                                        'position' => 'bottom',
                                        'labels' => [
                                            'fontSize' => 14,
                                            'fontColor' => "white",
                                        ]
                                    ],
                                    'tooltips' => [
                                        'enabled' => true,
                                        'intersect' => true
                                    ],
                                    'hover' => [
                                        'mode' => false
                                    ],
                                    'maintainAspectRatio' => false,
                            
                                ],
                                'plugins' =>
                                    new \yii\web\JsExpression('
                                    [{
                                        afterDatasetsDraw: function(chart, easing) {
                                            var ctx = chart.ctx;
                                            chart.data.datasets.forEach(function (dataset, i) {
                                                var meta = chart.getDatasetMeta(i);
                                                if (!meta.hidden) {
                                                    meta.data.forEach(function(element, index) {
                                                        // Draw the text in black, with the specified font
                                                        var fontSize = 12;
                                                        var fontStyle = "normal";
                                                        var fontFamily = "Helvetica";
                                                        ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                            
                                                        // Just naively convert to string for now
                                                        var dataString = dataset.data[index].toString()+"%";
                            
                                                        // Make sure alignment settings are correct
                                                        ctx.textAlign = "center";
                                                        ctx.textBaseline = "middle";
                            
                                                        var padding = 5;
                                                        var position = element.tooltipPosition();
                                                        ctx.fillStyle = "white";
                                                        ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                                                    });
                                                }
                                            });
                                        }
                                    }]')
                            ]);
                        ?>
                    </div>

                    <div class='pie-chart-color'>
                        <?php foreach($pie_chart_color as $color):?>
                            <div class='row' style='margin-bottom: 8px; height: 16px; line-height: 16px'>
                                <div style='float: left; width: 16px; height: 16px; background-color: <?= $color['color']?>'></div>
                                <span style='float: left; margin-left: 8px; height: 16px'><?= $color['label']?></span>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php else:?>
                    <div class='no-chart-data'>
                        Không có đơn nào
                    </div>
                <?php endif;?>
            </div>
        </div>

        <div class='col-md-5 col-sm-5 col-xs-5 data-wrapper' style='padding-left: 0px !important'>
            <div class='data-content'>
                <div class='row'>
                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-right: 7px !important; padding-left: 7px !important'>
                        <div class=' info-text info-white'>
                            <span class='box-text'>
                                <p class='text-blue text-normal'><?= $model['tong_so_don']?></p>
                                <p>Tổng số đơn</p>
                            </span>
                        </div>
                    </div>

                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-left: 7px !important; padding-right: 7px !important'>
                        <div class=' info-text info-yellow'>
                            <span class='box-text'>
                                <p class='text-white text-normal'><?= number_format($model['tong_cuoc_van_chuyen'], 0, ',', '.').' VNĐ'?></p>
                                <p class='text-white text-normal'>Tổng cước v/c</p>
                            </span>
                        </div>
                        
                    </div>
                </div>

                <div class='row' style='margin-top: 6px'>
                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-right: 7px !important; padding-left: 7px !important'>
                        <div class='info-text info-blue'>
                            <span class='box-text'>
                                <p class='text-white text-normal'><?= number_format($model['tong_tien_thu_ho'], 0, ',', '.').' VNĐ' ?></p>
                                <p class='text-white text-normal'>Tổng tiền thu hộ</p>
                            </span>
                        </div>    
                    </div>

                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-left: 7px !important; padding-right: 7px !important'>
                        <div class='info-text info-violet'>
                            <span class='box-text'>
                                <p class='text-white text-normal'><?= $model['so_don_huy_khong_lay_duoc']?></p>
                                <p class='text-white text-normal'>Số đơn huỷ/không lấy được</p>
                            </span>
                        </div>
                    </div>
                </div>

                <div class='row' style='margin-top: 6px'>
                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-right: 7px !important; padding-left: 7px !important'>
                        <div class='info-text info-black'>
                            <span class='box-text'>
                                <p class='text-white text-normal'><?= $model['so_don_phat_that_bai']?></p>
                                <p class='text-white text-normal'>Số đơn phát thất bại</p>
                            </span>
                        </div>
                    </div>

                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-left: 7px !important; padding-right: 7px !important'>
                        <div class='info-text info-white'>
                            <span class='box-text'>
                                <p class='text-normal'><?= $model['ti_le_hoan_hang'].'%'?></p>
                                <p class='text-normal'>Tỷ lệ hoàn hàng</p>
                            </span>
                        </div>
                    </div>
                </div>

                <div class='row' style='margin-top: 6px'>
                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-right: 7px !important; padding-left: 7px !important'>
                        <div class='info-text info-white'>
                            <span class='box-text'>
                                <p class='text-normal'><?= $model['don_dang_giao']?></p>
                                <p class='text-normal'>Đơn đang giao hàng</p>
                            </span>
                        </div>
                    </div>

                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-left: 7px !important; padding-right: 7px !important'>
                        <div class='info-text info-white'>
                            <span class='box-text'>
                                <p class='text-normal'><?= $model['don_cho_giao']?></p>
                                <p class='text-normal'>Đơn chờ giao</p>
                            </span>
                        </div> 
                    </div>
                </div>

                <div class='row' style='margin-top: 6px'>
                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-right: 7px !important; padding-left: 7px !important'>
                        <div class='info-text info-white'>
                            <span class='box-text'>
                                <p class='text-normal'><?= $model['don_dang_lay_hang']?></p>
                                <p class='text-normal'>Đơn đang lấy hàng</p>
                            </span>
                        </div>
                    </div>

                    <div class='col-md-6 col-sm-6 col-xs-6' style='padding-left: 7px !important; padding-right: 7px !important'>
                        <div class='info-text info-white'>
                            <span class='box-text'>
                                <p class='text-normal'><?= $model['don_da_giao']?></p>
                                <p class='text-normal'>Đơn đã giao</p>
                            </span>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Content-->
</div>
<?php JSRegister::begin()?>
    <script>
        function changeBtnColor(e) {
            const target = e.target
            if ($(target).hasClass('btn-success')) {

            } else {
                const dropDownParent = $(target).parents('.dropdown-content');
                const btnSuccess = $(dropDownParent).find('.btn-success');
                if (btnSuccess) {
                    $(btnSuccess).removeClass('btn-success')
                    $(btnSuccess).addClass('btn-default');
                    $(target).removeClass('btn-default');
                    $(target).addClass('btn-success');
                }
            }
        }

        function updateCustomRange(type) {
            var d = new Date();
            let to = new Date();
            let from;
            switch (type) {
                case 'homqua':
                    d.setDate(d.getDate() - 1);
                    from = d;
                break;
                case 'homnay':
                    from = d;   
                break;
                case 'bayngaytruoc':
                    d.setDate(d.getDate() - 7);
                    from = d;
                break;
                case 'bamuoingaytruoc':
                    d.setDate(d.getDate() - 30);
                    from = d;
                break;
                case 'thangnay':
                    d.setDate(1);
                    from = d;
                break;
                default:
                    return false;
                break;
                
            }

            const fromDate = from.getDate();
            const fromMonth = from.getMonth();
            const fromYear = from.getFullYear()
            
            const toDate = to.getDate();
            const toMonth = to.getMonth();
            const toYear = to.getFullYear()
            
            $("#from").kvDatepicker("update", new Date(fromYear, fromMonth, fromDate));
            $("#to").kvDatepicker("update", new Date(toYear, toMonth, toDate));

            $('#header-from-date').text(`${fromDate}/${fromMonth}/${fromYear}`);
            $('#header-to-date').text(`${toDate}/${toMonth}/${toYear}`)
        }

        console.log('a')
        $('.filter-icon').click((e) => {
            e.stopPropagation();
            if ($('.dropdown-content').hasClass('active')) {
                $('.dropdown-content').removeClass('active');
                $('.dropdown-content').addClass('inactive');
            } else {
                $('.dropdown-content').removeClass('inactive');
                $('.dropdown-content').addClass('active');
            }
        })

        $('.dropdown-content').click((e) => {
            e.stopPropagation();
        })

        $(window).click(function() {
            if ($('.dropdown-content').hasClass('active')) {
                $('.dropdown-content').removeClass('active');
                $('.dropdown-content').addClass('inactive');
            }
        });

        $('.btn-filter').click(e => {
            const target = e.target
            const type = $(target).attr('filter-type');
            changeBtnColor(e)
            updateCustomRange(type)
        })

        $('.dropdown-toggle').click(e => {
            const target = e.target;
            const parent = $(target).parents('.dropdown');
            if ($(parent).hasClass('open')) {
                setTimeout(() => {
                    $(parent).removeClass('open');
                }, 100)
            } else {
                setTimeout(() => {
                    $(parent).addClass('open')
                }, 100)
            }
        })
    </script>
<?php JSRegister::end()?>
