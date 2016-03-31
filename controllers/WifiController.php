<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\Wifi;
use app\components\WifiPay;

class WifiController extends Controller
{
	public $layout = false;  	//don't use the default theme layout 
	public $enableCsrfValidation = false; // csrf validation can't work
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
    	//todo
    	
    	//成功，返回OK
//     	$result = '{"status":"OK"}';

    	
    	//失败，返回FAIL
    	$result = '{"status":"FAIL"}';
    	echo $result;
    }
    
    
    public function actionGetconnectpage()
    {
    	
    }
    
    
    
    
    
    
    public function actionTest()
    {
    	$postStr = "
    			<DTSPostCharge>
    				<Header Action='PMS' Comment='Pay by the Minute: CREW Rate  Start:3/22/2016 2:15:31 AM End:3/22/2016 2:28:28 AM IP:172.28.25.129' CreationDateTime='2016-03-22 06:47:40' DocumentDefinition='' MessageIdentifier='7b6e2866-f5e9-48c1-8b27-9568946e3eed' SourceApplication='WIFI'/>
    				<Body>
    					<PostCharge CheckNumber='L490282' Department='WIFI' FolioID='0000902524' PassportNO='H123456' Gratuity='' OriginatingSystemID='WIFI' SalesAmount='2.47' TaxAmount='' TenderType='03' TotalSales='2.47' TransactionDate='20160322' TransactionTime='02:47:40'/>
    				</Body>
    			</DTSPostCharge>
    			";
    	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    	
    	var_dump($postObj->Body->PostCharge);
    }
}
