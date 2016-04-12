<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wifi_admin".
 *
 * @property integer $admin_id
 * @property string $admin_name
 * @property string $admin_real_name
 * @property string $admin_password
 * @property integer $role_id
 * @property string $last_login_ip
 * @property string $last_login_time
 * @property string $admin_email
 * @property integer $admin_state
 * @property integer $is_login
 */
class WifiAdmin extends \yii\db\ActiveRecord
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
            'admin_id' => 'Admin ID',
            'admin_name' => 'Admin Name',
            'admin_real_name' => 'Admin Real Name',
            'admin_password' => 'Admin Password',
            'role_id' => 'Role ID',
            'last_login_ip' => 'Last Login Ip',
            'last_login_time' => 'Last Login Time',
            'admin_email' => 'Admin Email',
            'admin_state' => 'Admin State',
            'is_login' => 'Is Login',
        ];
    }
}
