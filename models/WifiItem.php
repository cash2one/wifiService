<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_item".
 *
 * @property integer $wifi_id
 * @property integer $sale_price
 * @property integer $wifi_flow
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
            [['sale_price', 'wifi_flow'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wifi_id' => Yii::t('app', 'Wifi ID'),
            'sale_price' => Yii::t('app', 'wifi价格'),
            'wifi_flow' => Yii::t('app', 'wifi流量 单位:M'),
        ];
    }
}
