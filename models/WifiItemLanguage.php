<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_item_language".
 *
 * @property integer $id
 * @property integer $wifi_id
 * @property string $wifi_name
 * @property string $iso
 */
class WifiItemLanguage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_item_language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wifi_id'], 'integer'],
            [['wifi_name'], 'string', 'max' => 100],
            [['iso'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wifi_id' => 'Wifi ID',
            'wifi_name' => 'Wifi Name',
            'iso' => 'Iso',
        ];
    }
}
