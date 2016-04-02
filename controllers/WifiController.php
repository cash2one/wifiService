<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\Wifi;
use app\components\WifiPay;
use app\components\WifiConnect;

class WifiController extends Controller
{
	public $layout = false;  	//don't use the default theme layout 
	public $enableCsrfValidation = false; // csrf validation can't work
	
	
    public function actionIndex()
    {
    	$wifi_items = Wifi::getWifiItem();
        return $this->render('index',['wifi_items'=>$wifi_items]);
    }
    
    
    
    //获取wifi信息
    public function actionGetwifi($iso = 'zh_cn')
    {
    	$wifi_id = Yii::$app->request->post('wifi_id');
    	
    	if(isset($wifi_id)){
    		$wifi_item = Wifi::getWifiInfo($wifi_id,$iso);
    		$item = '"sale_price" : "'.$wifi_item['sale_price'].'","wifi_name":"'.$wifi_item['wifi_name'].'"}';
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
    	$passport = Yii::$app->request->post('PassportNO');
    	$TenderType = Yii::$app->request->post('TenderType');
    	$name = Yii::$app->request->post('Name');
    	
    	$wifi_item = Wifi::getWifiInfo($wifi_id);
    	
    	
    	//---  test demo ------
    	if($wifi_item['sale_price'] < 110){
    		//成功，返回OK
    		$result = '{"status":"OK"}';
    	}else{
    		//失败，返回FAIL
    		$result = '{"status":"FAIL"}';
    	}
    	echo $result;
    	//---  test demo - - end ---
   	
    	
    	
    	//构造XML数据，接口对接
    	//IBS的url地址    http://172.16.2.218:9560 
    	
    	//1.发送 xml,请求FolioBalance接口， 返回参数 ：BalanceDue  , 判断余额
    	$balance = WifiPay::folioBalance();
    	
    	
    	//2.判断余额是否充足，
    	if($balance < $wifi_item['sale_price']){
    		// 如果余额不足，返回 '{"status":"FAIL"}'，
    		$result = '{"status":"FAIL"}';
    	}else {
    		
    		//---sql事务---
    		$transaction = Yii::$app->db->beginTransaction();
    		try {
    			//3.调用支付接口
    			$postResponse = WifiPay::DTSPostCharge();
    			
    			//4.判断 PostchargeResponse XML
    			$PostchargeResponse = Wifi::xmlUnparsed($postResponse);
    			$checkNumber = $PostchargeResponse->attributes()->CheckNumber;
    			if($PostchargeResponse ){  //todo
    				// 如果error，就  返回 '{"status":"ERROR"}'，
    				$result = '{"status":"ERROR"}';
    			}else {
    				
    				//5.记录本地数据库的支付信息
    				$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$amount);
    				 
    				//6.购买上网卡
    				Wifi::wifiCardBuy($wifi_id,$passport,$pay_log_id);
    				//todo
    				
    				$result = '{"status":"OK"}';
    			}
    			$transaction->commit();
    		}catch(Exception $e){
    			$transaction->rollBack();
    			$result = '{"status":"ERROR"}';
    		}
    		//---sql事务---end-- 
    	}
    	
    	echo $result;
    }
    
    
    public function actionGetconnectpage()
    {
    	
    }
    
    
    
    
    //---   XML request Demo-----  begin --
    public function actionTest()
    {
    	$url = "www.wifiservice.com/wifi/response";
    	$data = "<?xml version='1.0' encoding='utf-8' ?>
    			<DTSPostCharge>
    				<Header Action='PMS' Comment='Pay by the Minute: CREW Rate  Start:3/22/2016 2:15:31 AM End:3/22/2016 2:28:28 AM IP:172.28.25.129' CreationDateTime='2016-03-22 06:47:40' DocumentDefinition='' MessageIdentifier='7b6e2866-f5e9-48c1-8b27-9568946e3eed' SourceApplication='WIFI'/>
    				<Body>
    					<PostCharge CheckNumber='L490282' Department='WIFI' FolioID='0000902524' PassportNO='H123456' Gratuity='' OriginatingSystemID='WIFI' SalesAmount='2.47' TaxAmount='' TenderType='03' TotalSales='2.47' TransactionDate='20160322' TransactionTime='02:47:40'/>
    				</Body>
    			</DTSPostCharge>
    			";
    
    	$res = Wifi::httpsRequest($url, $data);
    	
    	$this->actionTestresponse($res);
  	
    }
 
    public  function actionResponse()
    {
		$data = "<?xml version='1.0' encoding='utf-8' ?>
    			<DTSPostCharge>
    				<Header Action='123' Comment='Pay by the Minute: CREW Rate  Start:3/22/2016 2:15:31 AM End:3/22/2016 2:28:28 AM IP:172.28.25.129' CreationDateTime='2016-03-22 06:47:40' DocumentDefinition='' MessageIdentifier='7b6e2866-f5e9-48c1-8b27-9568946e3eed' SourceApplication='WIFI'/>
    				<Body>
    					<PostCharge CheckNumber='1234' Department='WIFI' FolioID='0000902524' PassportNO='H123456' Gratuity='' OriginatingSystemID='WIFI' SalesAmount='2.47' TaxAmount='' TenderType='03' TotalSales='2.47' TransactionDate='20160322' TransactionTime='02:47:40'/>
    				</Body>
    			</DTSPostCharge>
    			";
		return $data;
    }
    
    
    public function actionTestresponse($res)
    {
    	if (!empty($res)){
    		$postObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
    		$body = $postObj->Body->PostCharge;
    		echo $body->attributes()->CheckNumber;
    	}
    }
    //---   XML request Demo-----  end --
    	
}
    
  
