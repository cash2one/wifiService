<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_item_status".
 *
 * @property integer $id
 * @property string $passport_num
 * @property integer $wifi_info_id
 * @property integer $pay_log_id
 * @property integer $status
 */
class WifiItemStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_item_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wifi_info_id', 'pay_log_id', 'status'], 'integer'],
            [['passport_num'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'passport_num' => Yii::t('app', '支付人的passport number'),
            'wifi_info_id' => Yii::t('app', 'wifi_info_id'),
            'pay_log_id' => Yii::t('app', 'pay_log_id'),
            'status' => Yii::t('app', '状态，0可用 1耗尽'),
        ];
    }
}
