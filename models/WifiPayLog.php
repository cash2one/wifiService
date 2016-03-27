<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_pay_log".
 *
 * @property integer $id
 * @property integer $wifi_info_id
 * @property string $passport_num
 * @property string $name
 * @property integer $amount
 * @property string $pay_time
 * @property string $wifi_code
 * @property string $wifi_password
 * @property integer $status_use
 */
class WifiPayLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_pay_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wifi_info_id', 'amount', 'status_use'], 'integer'],
            [['pay_time'], 'safe'],
            [['passport_num', 'wifi_code', 'wifi_password'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'wifi_info_id' => Yii::t('app', 'Wifi Info ID'),
            'passport_num' => Yii::t('app', '支付人的passport number'),
            'name' => Yii::t('app', '支付人的姓名'),
            'amount' => Yii::t('app', '支付金额'),
            'pay_time' => Yii::t('app', '支付时间'),
            'wifi_code' => Yii::t('app', 'wifi登录帐号'),
            'wifi_password' => Yii::t('app', 'wifi登录密码'),
            'status_use' => Yii::t('app', '使用状态：0 可使用，1流量耗尽'),
        ];
    }
}
