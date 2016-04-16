<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_wlan_params".
 *
 * @property integer $id
 * @property string $wifi_code
 * @property string $wlanuserip
 * @property string $wlanacip
 */
class WifiWlanParams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_wlan_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wifi_code', 'wlanuserip', 'wlanacip'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wifi_code' => 'Wifi Code',
            'wlanuserip' => 'Wlanuserip',
            'wlanacip' => 'Wlanacip',
        ];
    }
}
