<?php
namespace app\models;
use yii\easyii\components\ActiveRecord;

class Auth extends ActiveRecord
{
    public static function tableName() {
        return 'auth';
    }
    
    public function rules()
    {
        return [
            [['user_id', 'source', 'source_id'], 'safe']
        ];
    }

    public function getHoadonchitiet() {
        return $this->hasMany(Hoadonchitiet::className(), ['ma_hoa_don' => 'ma_hoa_don']);
    }
}