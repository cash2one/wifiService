<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "IBSconnect_log".
 *
 * @property integer $id
 * @property integer $type
 * @property string $content
 * @property string $time
 */
class IBSconnectLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IBSconnect_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['content'], 'string'],
            [['time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', '类型，0:接收 1:发送'),
            'content' => Yii::t('app', 'XML内容'),
            'time' => Yii::t('app', '时间'),
        ];
    }
}
