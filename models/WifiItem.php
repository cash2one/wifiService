<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_item".
 *
 * @property integer $wifi_id
 * @property integer $sale_price
 * @property integer $wifi_flow
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
            [['sale_price', 'wifi_flow', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wifi_id' => 'Wifi ID',
            'sale_price' => 'Sale Price',
            'wifi_flow' => 'Wifi Flow',
            'status' => 'Status',
        ];
    }
}
