<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_url_params".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $remark
 */
class WifiUrlParams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_url_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'remark' => 'Remark',
        ];
    }
}
