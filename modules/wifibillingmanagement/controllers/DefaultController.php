<?php

namespace app\modules\wifibillingmanagement\controllers;

use yii\web\Controller;
use app\components\Auth;

class DefaultController extends Controller
{
    public function actionIndex()
    {
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("/wifibilling/login/login");
    	}//判断有没登陆
       return $this->render('index');
    }
}
