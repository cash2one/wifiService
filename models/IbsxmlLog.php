<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ibsxml_log".
 *
 * @property integer $id
 * @property integer $type
 * @property string $content
 * @property string $time
 * @property string $identififer
 */
class IbsxmlLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ibsxml_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['content'], 'string'],
            [['time'], 'safe'],
            [['identififer'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'content' => 'Content',
            'time' => 'Time',
            'identififer' => 'Identififer',
        ];
    }
}
