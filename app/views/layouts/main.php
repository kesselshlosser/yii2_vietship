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
                        <span class="hidden-sm hidden-xs">Administrator</span>
                        <!--<i class="fa fa-angle-down"></i>-->
                        <span class="caret hidden-sm hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="#"><i class="fa fa-cogs"></i>  Settings</a></li>
                        <li><a href="#"><i class="fa fa-user"></i>  Profile</a></li>
                        <li><a href="#"><i class="fa fa-commenting-o"></i>  Feedback</a></li>
                        <li><a href="#"><i class="fa fa-life-ring"></i>  Help</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to(['/site/out'])?>"><i class="fa fa-sign-out"></i> Log Out</a></li>
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
            <li class="nav-head">
                <h5 class="nav-title text-uppercase light-txt">order</h5>
            </li>
            <li>
                <a href="#"><i class="fa fa-shopping-cart"></i><span>Đơn hàng</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                    <li class="nav-sub-header"><a href="#"><span>Đơn hàng</span></a></li>
                    <li style="padding-left:15px"><a href="<?= Url::to(['/admin/donhang/a/create'])?>"><i class="fa fa-plus"></i><span>Thêm đơn hàng</span></a></li>
                    <li style="padding-left:15px">
                        <!-- <a href=""><span>Level Two </span></a> -->
                        <a href="#"><i class="fa fa-shopping-cart"></i><span>Quản lý đơn hàng</span><i class="fa fa-angle-right pull-right"></i></a>
                        <ul class="nav nav-sub">
                            <li><a href="<?= Url::to(['/admin/donhang'])?>"><i class="fa fa-circle-o"></i><span>Danh sách đơn hàng</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="nav-head">
                <h5 class="nav-title text-uppercase light-txt">customer</h5>
            </li>
            <li>
                <a href="#"><i class="fa fa-users"></i><span>Thanh toán</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                    <li class="nav-sub-header"><a href="#"><span>Thanh toán</span></a></li>
                    <li style="padding-left:15px"><a href="<?= Url::to(['/khachhang/quanlytien'])?>"><i class="fa fa-circle-o"></i><span>Quản lý tiền</span></a></li>
                    <li style="padding-left:15px"><a href="<?= Url::to(['/khachhang/hoadon'])?>"><i class="fa fa-circle-o"></i><span>Hóa đơn thanh toán</span></a></li>
                </ul>
            </li>

            <li class="nav-head">
                <h5 class="nav-title text-uppercase light-txt">price</h5>
            </li>
            <li>
                <a href="#"><i class="fa fa-calculator"></i><span>Tính giá ship</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                    <li class="nav-sub-header"><a href="#"><span>Tính giá ship</span></a></li>
                    <li style="padding-left:15px"><a href="<?= Url::to(['/giashipnoithanh/calculate-price'])?>"><i class="fa fa-calculator"></i><span>Tính giá ship</span></a></li>
                </ul>
            </li>
            
            <li class="nav-head">
                <h5 class="nav-title text-uppercase light-txt">other</h5>
            </li>
            <li>
                <a href="#"><i class="fa fa-hashtag"></i><span>Tác vụ khác</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                    <li class="nav-sub-header"><a href="#"><span>Tác vụ khác</span></a></li>
                    <li style="padding-left:15px"><a href="<?= Url::to(['/admin/coupon'])?>"><i class="fa fa-gift"></i><span>Quản lý coupon</span></a></li>
                    <li style="padding-left:15px">
                        <!-- <a href=""><span>Level Two </span></a> -->
                        <a href="#"><i class="fa fa-envelope-o"></i><span>Email</span><i class="fa fa-angle-right pull-right"></i></a>
                        <ul class="nav nav-sub">
                            <li><a href="#"><i class="fa fa-circle-o"></i><span>Danh sách email</span></a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i><span>Gửi email mới</span></a></li>
                        </ul>
                    </li>
                    <li style="padding-left:15px"><a href="#"><i class="fa fa-pie-chart"></i><span>Thống kê</span></a></li>
                </ul>
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
