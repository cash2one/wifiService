<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\Wifi;
use app\components\WifiPay;

class WifiController extends Controller
{
	public $layout = false;  	//don't use the default theme layout 
	public $enableCsrfValidation = false; // csrf validation equal false
    public function actionIndex()
    {
    	$wifi = new Wifi();
    	$wifi_items = $wifi::GetWifiItem();
        return $this->render('index',['wifi_items'=>$wifi_items]);
    }
    
    
    //获取wifi信息
    public function actionGetwifi($iso = 'zh_cn')
    {
    	$wifi_id = Yii::$app->request->post('wifi_id');
    	
    	
    	if(isset($wifi_id)){
    		$wifi_item = new WifiPay();
    		$item = $wifi_item::GetWifiInfo($wifi_id,$iso);
    	}else{
    		$item = '';
    	}
    	
    	$result = '{"status":"OK","data":{'.$item.'}';
    	echo $result;
    }
    
    
    //支付wifi套餐
    public function actionPayment()
    {
    	$wifi_id = Yii::$app->request->post('wifi_id');
    	$passport = Yii::$app->request->post('passport');
    	$TenderType = Yii::$app->request->post('TenderType');
    	
    	//构造XML数据，接口对接
    	
    	
    	
    	
    	$result = '{"status":"OK"}';
    	echo $result;
    }
}
