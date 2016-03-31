<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_item".
 *
 * @property integer $wifi_id
 * @property integer $sale_price
 * @property integer $wifi_flow
 * @property integer $expiry_day
 * @property integer $status
 */
class WifiItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_price', 'wifi_flow', 'expiry_day', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wifi_id' => Yii::t('app', 'Wifi ID'),
            'sale_price' => Yii::t('app', 'wifi价格 单位：$'),
            'wifi_flow' => Yii::t('app', 'wifi流量 单位:M'),
            'expiry_day' => Yii::t('app', '有效期限(单位：天)'),
            'status' => Yii::t('app', '是否停用 ：0可用 1停用'),
        ];
    }
}
