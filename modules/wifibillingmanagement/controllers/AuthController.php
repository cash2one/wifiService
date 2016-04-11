<?php

namespace app\modules\wifibillingmanagement\controllers;

use yii\web\Controller;

class AuthController extends Controller
{
public function __construct(){

$admin_id=\Yii::$app->session['admin_id'];
if ($admin_id==''){
	echo 'aa';
	
}
else {
	echo 'a';

}
	/* $admin_id=isset(\Yii::$app->session['admin_id'])?\Yii::$app->session['admin_id']:'';
	if($admin_id==''){
		return $this->redirect("login/login");
	} */
}
}
