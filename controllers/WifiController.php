<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
class WifiController extends Controller
{
	public $layout = false;
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionPay()
    {
    	return $this->render('pay');
    }
}
