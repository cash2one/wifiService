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
 * @property string $time
 * @property integer $expiry_day
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
            [['wifi_id', 'status_sale', 'expiry_day'], 'integer'],
            [['time'], 'safe'],
            [['wifi_code', 'wifi_password'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wifi_info_id' => 'Wifi Info ID',
            'wifi_id' => 'Wifi ID',
            'wifi_code' => 'Wifi Code',
            'wifi_password' => 'Wifi Password',
            'status_sale' => 'Status Sale',
            'time' => 'Time',
            'expiry_day' => 'Expiry Day',
        ];
    }
}
