<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components;
use app\components\Wifi;
use app\components\WifiConnect;

class WifiController extends Controller
{
	public $layout = false;  	//don't use the default theme layout 
	public $enableCsrfValidation = false;
    public function actionIndex()
    {
    	$wifi = new Wifi();
    	$wifi_items = $wifi::GetWifiItem();
        return $this->render('index',['wifi_items'=>$wifi_items]);
    }
    
    public function actionPay()
    {
    	return $this->render('pay');
    }
    
    
    public function actionGetname($iso = 'zh_cn')
    {
    	$result = array();
    	$id = Yii::$app->request->post('id');
    	if(isset($id)){
    		$sql = " SELECT a.sale_price ,b.wifi_name 
    				FROM wifi_item a ,wifi_item_language b 
    				WHERE a.wifi_id = b.wifi_id AND a.wifi_id =$id AND iso='$iso' ";
    		$wifi_item = Yii::$app->db->createCommand($sql)->queryOne();
 
    		$item = '"sale_price" : "'.$wifi_item['sale_price'].'","wifi_name":"'.$wifi_item['wifi_name'].'"}';
    	}
    	
    	$result = '{"status":"OK","data":{'.$item.'}';
    	echo $result;
    }
}
