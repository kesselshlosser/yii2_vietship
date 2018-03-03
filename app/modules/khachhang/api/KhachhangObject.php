<?php
namespace app\modules\khachhang\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use app\modules\khachhang\models\Khachhang as KhachhangModel;
use yii\helpers\Url;

class KhachhangObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $image;
    public $views;
    public $time;

    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getDate(){
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getPhotos()
    {
        if(!$this->_photos){
            $this->_photos = [];

            foreach(Photo::find()->where(['class' => KhachhangModel::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function  getEditLink(){
        return Url::to(['/admin/khachhang/a/edit/', 'id' => $this->id]);
    }
}