<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_admin".
 *
 * @property string $admin_id
 * @property string $admin_name
 * @property string $admin_real_name
 * @property string $admin_password
 * @property string $role_id
 * @property string $last_login_ip
 * @property string $last_login_time
 * @property string $admin_email
 * @property integer $admin_state
 * @property integer $is_login
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wifi_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_name', 'admin_real_name', 'admin_password', 'role_id', 'last_login_ip', 'last_login_time', 'admin_email'], 'required'],
            [['role_id', 'admin_state', 'is_login'], 'integer'],
            [['last_login_time'], 'safe'],
            [['admin_name', 'admin_real_name', 'admin_password', 'last_login_ip', 'admin_email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => Yii::t('app', 'Admin ID'),
            'admin_name' => Yii::t('app', '用于登录'),
            'admin_real_name' => Yii::t('app', 'Admin Real Name'),
            'admin_password' => Yii::t('app', 'Admin Password'),
            'role_id' => Yii::t('app', 'Role ID'),
            'last_login_ip' => Yii::t('app', 'Last Login Ip'),
            'last_login_time' => Yii::t('app', 'Last Login Time'),
            'admin_email' => Yii::t('app', 'Admin Email'),
            'admin_state' => Yii::t('app', '0未激活 1激活'),
            'is_login' => Yii::t('app', 'Is Login'),
        ];
    }
}
