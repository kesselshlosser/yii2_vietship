<?php

namespace app\controllers;

use Yii;
use yii\easyii\modules\page\models\Page;
use yii\web\Controller;
use \yii\easyii\models\LoginFormFrontEnd;
use app\modules\khachhang\models\Khachhang;
use \yii\easyii\models\Diachilayhang;
use \yii\easyii\models\Hinhthucthanhtoan;
use app\components\AuthHandler;
use yii\easyii\helpers\Mail;

class SiteController extends Controller
{
    public $captcha;
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ]
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    public function actionIndex()
    {
        $model = new \yii\easyii\models\LoginFormFrontEnd();
        $baseUrl = \yii\helpers\Url::base(true);
        // Nếu đã đăng nhập rồi, có session thì vào thẳng luôn trang /donhang
        if (\Yii::$app->session->has('user')) {
            return $this->redirect($baseUrl.'/donhang');
        }
        if (Yii::$app->request->post()) {
            $dataPost = Yii::$app->request->post();
            $typeSubmit = $dataPost['smForm'];
            $model_kh = new Khachhang();
            if ($typeSubmit == 'dangky') {
                $loginFormData = Yii::$app->request->post()['LoginFormFrontEnd'];
                $email = $loginFormData['username'];
                $password = md5($loginFormData['password']);
                $model_kh->ten_dang_nhap = $email;
                $model_kh->mat_khau = $password;
                $model_kh->email = $email;
                $model_kh->time = time();
                if ($model_kh->save(false)) {
                    $model_khach_hang = new Khachhang();
                    $model_dclh = new Diachilayhang();
                    $model_httt = new Hinhthucthanhtoan();
                    return $this->renderPartial('profile', [
                        'model_khach_hang' => $model_khach_hang,
                        'model_dclh' => $model_dclh,
                        'model_httt' => $model_httt,
                        'email' => $email
                    ]);
                    // Yii::$app->session->setFlash('success', "Tạo tài khoản thành công");
                } else {
                  Yii::$app->session->setFlash('error', "Tạo tài khoản thất bại");
                }
            } elseif ($typeSubmit == 'dangnhap'){
                $loginFormData = Yii::$app->request->post()['LoginFormFrontEnd'];
                $email = $loginFormData['username'];
                $password = md5($loginFormData['password']);
                $checkLogin = Khachhang::checkLogin($email, $password);
                if ($checkLogin) {
                    $user = Khachhang::find()
                    ->where(['ten_dang_nhap' => $email])
                    ->andWhere(['mat_khau' => $password])
                    ->asArray()
                    ->one();
                    \Yii::$app->session->set('user', $user);
                    // Nếu chưa thêm thông tin thì vào trang thông tin
                    $setting = (int)$user['setting'];
                    if ($setting === 0) {
                        $model_khach_hang = new Khachhang();
                        $model_dclh = new Diachilayhang();
                        $model_httt = new Hinhthucthanhtoan();
                        return $this->renderPartial('profile', [
                            'model_khach_hang' => $model_khach_hang,
                            'model_dclh' => $model_dclh,
                            'model_httt' => $model_httt,
                            'email' => $email
                        ]);
                    }
                    return $this->redirect($baseUrl.'/donhang');
                } else {
                    Yii::$app->session->setFlash('sign_in_error', "Email hoặc mật khẩu không đúng");
                }
            } elseif ($typeSubmit == 'khachhang') {
                $khachHangData = Yii::$app->request->post()['Khachhang'];
                $so_dien_thoai = $khachHangData['so_dien_thoai'];
                $dia_chi = $khachHangData['dia_chi'];
                $ten_shop = $khachHangData['ten_shop'];
                $website = $khachHangData['website'];
                $facebook = $khachHangData['facebook'];
                $email = Yii::$app->request->post()['email'];
                $kh_id = Khachhang::find()->where(['ten_dang_nhap' => $email])->one()['kh_id'];
                // Lưu vào bảng khách hàng
                $model_kh = Khachhang::findOne($kh_id);
                $model_kh->so_dien_thoai = $so_dien_thoai;
                $model_kh->dia_chi = $dia_chi;
                if (!empty($ten_shop)) {
                    $model_kh->ten_shop = $ten_shop;
                }
                if (!empty($website)) {
                    $model_kh->website = $website;
                }
                if (!empty($facebook)) {
                    $model_kh->facebook = $facebook;
                }

                if ($model_kh->save(false)) {
                    $result = [
                        'message' => 'success'
                    ];
                } else {
                    $result = [
                        'message' => 'error'
                    ];
                }
                return json_encode($result, JSON_UNESCAPED_UNICODE);
            } elseif ($typeSubmit == 'dclh') {
                $success = 1;
                $dclhData = Yii::$app->request->post()['Diachilayhang']['arr_dclh'];
                $email = Yii::$app->request->post()['email'];
                $kh_id = Khachhang::find()->where(['ten_dang_nhap' => $email])->one()['kh_id'];
                foreach($dclhData as $dclh)
                {
                    $model_dclh = new Diachilayhang();
                    $model_dclh->ten_goi_nho = $dclh['ten_goi_nho'];
                    $model_dclh->ten_nguoi_ban_giao_hang = $dclh['ten_nguoi_ban_giao_hang'];
                    $model_dclh->so_dien_thoai = $dclh['so_dien_thoai'];
                    $model_dclh->dia_chi_text = $dclh['dia_chi_text'];
                    $model_dclh->dp_id = $dclh['dp_id'];
                    $model_dclh->kh_id = $kh_id;
                    if ($model_dclh->save(false)) {
                        
                    } else {
                        $success = 0;
                    }
                }
                if ($success == 1) {
                    $result = [
                        'message' => 'success'
                    ];
                } elseif ($success == 0) {
                    $result = [
                        'message' => 'error'
                    ];
                }
                return json_encode($result, JSON_UNESCAPED_UNICODE);
            } elseif ($typeSubmit == 'httt') {
                $htttData = Yii::$app->request->post()['Hinhthucthanhtoan'];
                $email = Yii::$app->request->post()['email'];
                $kh_id = Khachhang::find()->where(['ten_dang_nhap' => $email])->one()['kh_id'];
                $httt = $htttData['hinh_thuc_thanh_toan'];
                $model_httt = new Hinhthucthanhtoan();
                if ($httt == 'Tiền mặt') {
                    $model_httt->ten_nguoi_nhan = $htttData['ten_nguoi_nhan'];
                    $model_httt->dia_chi = $htttData['dia_chi'];
                    $model_httt->so_dien_thoai = $htttData['so_dien_thoai'];
                } else {
                    $model_httt->thong_tin_ngan_hang = json_encode($htttData['arr_ttck'], JSON_UNESCAPED_UNICODE);
                }
                // Xử lý đến thời gian thanh toán
                $arr_tgtt = [];
                if($htttData['json_thoi_gian_thanh_toan'] == 'Mỗi tuần 1 lần')
                {
                    $arr_tgtt['type'] = $htttData['json_thoi_gian_thanh_toan'];
                    $arr_tgtt['time'] = $htttData['thanh_toan_theo_tuan'];
                }  else {
                    $arr_tgtt['type'] = $htttData['json_thoi_gian_thanh_toan'];
                    $arr_tgtt['time'] = -1;
                }
                $model_httt->thoi_gian_thanh_toan = json_encode($arr_tgtt, JSON_UNESCAPED_UNICODE);
                $model_httt->hinh_thuc_thanh_toan = $htttData['hinh_thuc_thanh_toan'];
                $model_httt->kh_id = $kh_id;
                if ($model_httt->save(false)) {
                    // set setting cua phan khach hang thanh 1 -> da setting xong thong tin ban dau
                    $model_kh = Khachhang::findOne($kh_id);
                    $model_kh->setting = 1;
                    $model_kh->save(false);
                    $baseUrl = \yii\helpers\Url::base(true);
                    return $this->redirect($baseUrl);
                } else {
                    $result = [
                        'message' => 'error'
                    ];
                    return json_encode($result, JSON_UNESCAPED_UNICODE);
                }
            }
            return $this->renderPartial('index', ['model' => $model]);
        }
        return $this->renderPartial('index', ['model' => $model]);
    }

    public function actionProfile() {
        if (\Yii::$app->session->has('user')) {
            $email = \Yii::$app->session->get('user')['email'];
        }
        $model_khach_hang = new Khachhang();
        $model_dclh = new Diachilayhang();
        $model_httt = new Hinhthucthanhtoan();
        return $this->renderPartial('profile', [
            'model_khach_hang' => $model_khach_hang,
            'model_dclh' => $model_dclh,
            'model_httt' => $model_httt,
            'email' => $email
        ]);
    }

    public function actionOut() {
        $baseUrl = \yii\helpers\Url::base(true);
        Yii::$app->session->destroy();
        return $this->redirect($baseUrl);
    }

    public function actionQuenmatkhau() {
        if (Yii::$app->request->post()) {
            $dataPost = Yii::$app->request->post();
            $email = $dataPost['email'];
            $isExit = false;
            $model_kh = Khachhang::find()->asArray()->all();
            foreach($model_kh as $key => $value) {
                if ($value['email'] == $email) {
                    $isExit = true;
                    break;
                }
            }
            // Email không có trong db -> thông báo bằng session
            if (!$isExit) {
                Yii::$app->session->setFlash('quenmatkhau-error', "Không tìm thấy email.");
            } else {
                // Email có trong db -> gửi email lấy lại mật khẩu
                $rand = substr(uniqid('', true), -5);

                $model_kh_forgot_pw = Khachhang::find()
                ->where(['email' => $email])
                ->one();
                $model_kh_forgot_pw->forgot_password_code = $rand;
                if ($model_kh_forgot_pw->save(false)) {
                    $toEmail = $email;
                    $fromEmail = 'arcadia2252017@gmail.com';
                    $subject = 'Vietshipvn.com Đặt lại mật khẩu.';
                    $template = '@easyii/modules/feedback/mail/en/forgot_password';
                    $data = [
                        'forgot_password_code' => $rand,
                        'email' => $email
                    ];
                    $send_forgot_pw = Yii::$app->mailer->compose($template, $data)
                        ->setTo($toEmail)
                        ->setSubject($subject)
                        ->setFrom($fromEmail);
                    // Less secure app access on gmail setting -> can send email
                    if ($send_forgot_pw->send()) {
                        Yii::$app->session->setFlash('quenmatkhau-success', "Chúng tôi đã gửi liên kết để bạn đặt lại mật khẩu vào email. Vui lòng kiểm tra email.");
                    } else {
                        Yii::$app->session->setFlash('quenmatkhau-error', "Có lỗi trong quá trình gửi email. Xin vui lòng thử lại sau");
                    }
                }
            }
        }
        return $this->renderPartial('quenmatkhau', []);
    }
}
