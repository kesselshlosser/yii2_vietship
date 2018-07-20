<?php
namespace app\components;

use app\models\Auth;
use app\modules\khachhang\models\Khachhang;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $nickname = ArrayHelper::getValue($attributes, 'login');

        /* @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->asArray()->one();
        
        if (count($auth) <= 0) {
            // Thêm mới khách hàng với email là email của facebook, setting = 0
            $model_kh = new Khachhang();
            $model_kh->email = $email;
            if ($model_kh->save(false)) {
                $kh_id = $model_kh->kh_id;
                $model_auth = new Auth();
                $model_auth->user_id = $kh_id;
                $model_auth->source = $this->client->getId();
                $model_auth->source_id = $id;
                if ($model_auth->save(false)) {
                    Yii::$app->session->destroy();
                    $user = Khachhang::find()->where(['kh_id' => $kh_id])->asArray()->one();
                    \Yii::$app->session->set('user', $user);
                    $model_khach_hang = new Khachhang();
                    $model_dclh = new \yii\easyii\models\Diachilayhang();
                    $model_httt = new \yii\easyii\models\Hinhthucthanhtoan();
                    return Yii::$app->response->redirect(Url::base(true).'/site/profile');
                }
            }
        } else {
            // Đăng nhập
            echo 'auth > 0';
            exit();
            return Yii::$app->response->redirect(Url::base(true).'/donhang');
        }

        // if (Yii::$app->user->isGuest) {
        //     if ($auth) { // login
        //         /* @var User $user */
        //         $user = $auth->user;
        //         $this->updateUserInfo($user);
        //         Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
        //     } else { // signup
        //         if ($email !== null && Khachhang::find()->where(['email' => $email])->exists()) {
        //             Yii::$app->getSession()->setFlash('error', [
        //                 Yii::t('app', "Khachhang with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
        //             ]);
        //         } else {
        //             $password = Yii::$app->security->generateRandomString(6);
        //             $user = new Khachhang([
        //                 'username' => $nickname,
        //                 'github' => $nickname,
        //                 'email' => $email,
        //                 'password' => $password,
        //             ]);
        //             $user->generateAuthKey();
        //             $user->generatePasswordResetToken();

        //             $transaction = Khachhang::getDb()->beginTransaction();

        //             if ($user->save()) {
        //                 $auth = new Auth([
        //                     'user_id' => $user->id,
        //                     'source' => $this->client->getId(),
        //                     'source_id' => (string)$id,
        //                 ]);
        //                 if ($auth->save()) {
        //                     $transaction->commit();
        //                     Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
        //                 } else {
        //                     Yii::$app->getSession()->setFlash('error', [
        //                         Yii::t('app', 'Unable to save {client} account: {errors}', [
        //                             'client' => $this->client->getTitle(),
        //                             'errors' => json_encode($auth->getErrors()),
        //                         ]),
        //                     ]);
        //                 }
        //             } else {
        //                 Yii::$app->getSession()->setFlash('error', [
        //                     Yii::t('app', 'Unable to save user: {errors}', [
        //                         'client' => $this->client->getTitle(),
        //                         'errors' => json_encode($user->getErrors()),
        //                     ]),
        //                 ]);
        //             }
        //         }
        //     }
        // } else { // user already logged in
        //     if (!$auth) { // add auth provider
        //         $auth = new Auth([
        //             'user_id' => Yii::$app->user->id,
        //             'source' => $this->client->getId(),
        //             'source_id' => (string)$attributes['id'],
        //         ]);
        //         if ($auth->save()) {
        //             /** @var Khachhang $user */
        //             $user = $auth->user;
        //             $this->updateUserInfo($user);
        //             Yii::$app->getSession()->setFlash('success', [
        //                 Yii::t('app', 'Linked {client} account.', [
        //                     'client' => $this->client->getTitle()
        //                 ]),
        //             ]);
        //         } else {
        //             Yii::$app->getSession()->setFlash('error', [
        //                 Yii::t('app', 'Unable to link {client} account: {errors}', [
        //                     'client' => $this->client->getTitle(),
        //                     'errors' => json_encode($auth->getErrors()),
        //                 ]),
        //             ]);
        //         }
        //     } else { // there's existing auth
        //         Yii::$app->getSession()->setFlash('error', [
        //             Yii::t('app',
        //                 'Unable to link {client} account. There is another user using it.',
        //                 ['client' => $this->client->getTitle()]),
        //         ]);
        //     }
        // }
    }
}