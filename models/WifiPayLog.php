<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_pay_log".
 *
 * @property integer $id
 * @property string $check_num
 * @property string $passport_num
 * @property string $name
 * @property integer $amount
 * @property string $pay_time
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
            [['amount'], 'integer'],
            [['pay_time'], 'safe'],
            [['check_num', 'passport_num'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'check_num' => 'Check Num',
            'passport_num' => 'Passport Num',
            'name' => 'Name',
            'amount' => 'Amount',
            'pay_time' => 'Pay Time',
        ];
    }
}
