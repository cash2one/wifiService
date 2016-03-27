<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_info".
 *
 * @property integer $wifi_info_id
 * @property integer $wifi_id
 * @property string $wifi_code
 * @property string $wifi_password
 * @property integer $status_sale
 */
class WifiInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wifi_id', 'status_sale'], 'integer'],
            [['wifi_code', 'wifi_password'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wifi_info_id' => Yii::t('app', 'Wifi Info ID'),
            'wifi_id' => Yii::t('app', 'Wifi ID'),
            'wifi_code' => Yii::t('app', 'wifi登录帐号'),
            'wifi_password' => Yii::t('app', 'wifi登录密码'),
            'status_sale' => Yii::t('app', '出售状态：0 未出售，1已出售'),
        ];
    }
}
