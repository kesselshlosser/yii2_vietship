<?php
use yii\easyii\modules\shopcart\api\Shopcart;
use yii\easyii\modules\subscribe\api\Subscribe;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;

$baseUrl = Url::base(true);
$pathToTemplate = $baseUrl.'/vendor/noumo/easyii/media/admin_template/';
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
<div id="ui" class="ui">
    <!--header start-->
    <header id="header" class="ui-header">

        <div class="navbar-header">
            <!--logo start-->
            <a href="index.html" class="navbar-brand">
                
            </a>
            <!--logo end-->
        </div>

<!--            <div class="search-dropdown dropdown pull-right visible-xs">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-search"></i></button>
            <div class="dropdown-menu">
                <form action="#">
                    <input class="form-control" placeholder="Search here..." type="text">
                </form>
            </div>
        </div>-->

        <div class="navbar-collapse nav-responsive-disabled">

            <!--toggle buttons start-->
            <ul class="nav navbar-nav">
                <li>
                    <a class="toggle-btn" data-toggle="ui-nav" href="#">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
            </ul>
            <!-- toggle buttons end -->

            <!--search start-->
<!--                <form class="search-content hidden-xs" action="#">
                <button type="submit" name="search" class="btn srch-btn">
                    <i class="fa fa-search"></i>
                </button>
                <input type="text" class="form-control" name="keyword" placeholder="Search here...">
            </form>-->
            <!--search end-->

            <!--notification start-->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-usermenu">
                    <a href="#" class=" dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        <div class="user-avatar"><img src="<?= $pathToTemplate?>imgs/a0.jpg" alt="..."></div>
                        <span class="hidden-sm hidden-xs">
                            <?php
                                if (\Yii::$app->session->has('user')) {
                                    $user = \Yii::$app->session->get('user');
                                }
                                $kh_email = $user['email'];
                                echo $kh_email;
                            ?>
                        </span>
                        <!--<i class="fa fa-angle-down"></i>-->
                        <span class="caret hidden-sm hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <!-- <li><a href="#"><i class="fa fa-cogs"></i>  Settings</a></li>
                        <li><a href="#"><i class="fa fa-user"></i>  Profile</a></li>
                        <li><a href="#"><i class="fa fa-commenting-o"></i>  Feedback</a></li>
                        <li><a href="#"><i class="fa fa-life-ring"></i>  Help</a></li>
                        <li class="divider"></li> -->
                        <?php
                            if (\Yii::$app->session->has('user')) {
                                $user = \Yii::$app->session->get('user');
                            }
                            $kh_id = $user['kh_id'];
                        ?>
                        <li><a href="<?= Url::to(['/khachhang/doimatkhau/'.$kh_id])?>"><i class="fa fa-user"></i>  Đổi mật khẩu</a></li>
                        <li><a href="<?= Url::to(['/site/out'])?>"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                    </ul>
                </li>
            </ul>
            <!--notification end-->

        </div>

    </header>
    <!--header end-->

    <!--sidebar start-->
    <aside id="aside" class="ui-aside">
        <ul class="nav" ui-nav>
            <li>
                <a href="<?= Url::to(['/giashipnoithanh/calculate-price'])?>"><i class="fa fa-calculator"></i><span>Tính giá cước (Tính ship)</span></a>
            </li>

            <li>
                <a href="<?= Url::to(['/donhang/create'])?>"><i class="fa fa-shopping-cart"></i><span>Thêm đơn hàng</span></a>
            </li>

            <li>
                <a href="<?= Url::to(['/donhang'])?>"><i class="fa fa-shopping-cart"></i><span>Danh sách đơn hàng</span></a>
            </li>

            <li>
                <a href="<?= Url::to(['/khachhang/quanlytien'])?>"><i class="fa fa-circle-o"></i><span>Quản lý tiền thu hộ</span></a>
            </li>

            <li>
                <a href="<?= Url::to(['/khachhang/hoadon'])?>"><i class="fa fa-circle-o"></i><span>Hóa đơn thanh toán</span></a>
            </li>

            <li>
                <a href="<?= Url::to(['/khachhang/edit'])?>"><i class="fa fa-user"></i><span>Quản lý tài khoản</span></a>
            </li>

            <li>
                <a href="<?= Url::to(['/khachhang/thongke'])?>"><i class="fa fa-pencil-square-o"></i><span>Thống kê</span></a>
            </li>
        </ul>
    </aside>
    <!--sidebar end-->
    
    <!--main content start-->
    <div id="content" class="ui-content ui-content-aside-overlay">
        <?= $content;?>
    </div>
    <!--main content end-->
    
    <!--footer start-->
<!--        <div id="footer" class="ui-footer">
        2017 &copy; vietship by TTE.
    </div>-->
    <!--footer end-->
</div>
<?php $this->endContent(); ?>
