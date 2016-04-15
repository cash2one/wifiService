<?php

namespace app\modules\wifibillingmanagement\controllers;
use Yii;
use yii\web\Controller;
use app\components\Auth;

class DefaultController extends Controller
{
    public function actionIndex()
    {
    	$weburl=Yii::$app->params['weburl'];
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("$weburl/login/login");
    	}//判断有没登陆
    	else {
    		$power=Auth::getPower();
    	}
    	   return $this->render('index');
    }
}
