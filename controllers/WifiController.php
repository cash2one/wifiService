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
    	$iso = Yii::$app->request->get('iso','zh_cn');
    	$wifi_items = Wifi::getAllWifiItem($iso);
        return $this->render('index',['wifi_items'=>$wifi_items]);
    }
    
    
    
    //获取wifi信息
    public function actionGetwifi()
    {
    	$wifi_id = Yii::$app->request->post('wifi_id');
    	$iso = Yii::$app->request->post('iso');
    	
    	if($iso === 'null'){
    		$iso = 'zh_cn';
    	}
    	
    	if(isset($wifi_id)){
    		$wifi_item = Wifi::getWifiItem($wifi_id,$iso);
    		$item = '{"sale_price" : "'.$wifi_item['sale_price'].'","wifi_name":"'.$wifi_item['wifi_name'].'"}';
    	}else{
    		$item = '';
    	}
    	
    	$result = '{"status":"OK","data":'.$item.'}';
    	echo $result;
    }
    
    
    
    
    //支付wifi套餐
    public function actionPayment()
    {
    	$wifi_id = Yii::$app->request->post('wifi_id');
    	$passport = Yii::$app->request->post('PassportNO');
    	$TenderType = Yii::$app->request->post('TenderType');
    	$name = Yii::$app->request->post('Name');
    	$iso = Yii::$app->request->post('iso');
    	
    	if($iso === 'null'){
    		$iso = 'zh_cn';
    	}
    	
    	$wifi_item = Wifi::getWifiItem($wifi_id,$iso);
    	
    	$checkNumber = '1234'; //todo


/*  	 
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

*/

    	$type = WifiPay::isIBSPay();
    	if($type){
    		
    		//通过ibs系统支付
	    	//构造XML数据，接口对接
	    	//IBS的url地址    http://172.16.2.218:9560
	    	//1.发送 xml,请求FolioBalance接口， 返回参数 ：BalanceDue , 判断余额
	    	$balance = WifiPay::folioBalance($passport);
	    	
	    	//2.判断余额是否充足
	    	if($balance < $wifi_item['sale_price']){
	    		// 如果余额不足，返回 '{"status":"FAIL"}'，
	    		$result = '{"status":"FAIL"}';
	    	}else {
	    		//---sql事务---
	    		$transaction = Yii::$app->db->beginTransaction();
	    		try {
	    			//3.调用支付接口
	    			$postResponse = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price']);
	    			
	    			//4.判断 PostchargeResponse XML
	    			$PostchargeResponse = Wifi::xmlUnparsed($postResponse);
	    			
	    			if(isset($PostchargeResponse->attributes()->Code)){  
	    				// 如果error，就返回 '{"status":"ERROR"}'，
	    				$result = '{"status":"ERROR"}';
	    			}else {
	    				
	    				//5.记录本地数据库的支付信息
	    				$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
	    				
	    				//6.购买上网卡
	    				Wifi::wifiCardBuy($wifi_id,$passport,$pay_log_id);
	    				
	    				$result = '{"status":"OK"}';
	    			}
	    			$transaction->commit();
	    		}catch(Exception $e){
	    			$transaction->rollBack();
	    			$result = '{"status":"ERROR"}';
	    		}
	    		//---sql事务---end-- 
	    	}
    	}else{
    		//不通过ibs系统支付
    		//---sql事务---
    		$transaction = Yii::$app->db->beginTransaction();
    		try {
	    		//1.记录本地数据库的支付信息
	    		$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
	    		//2.购买上网卡
	    		Wifi::wifiCardBuy($wifi_id,$passport,$pay_log_id);
	    		
	    		$transaction->commit();
	    		$result = '{"status":"OK"}';
	    		
    		}catch(Exception $e){
    			$transaction->rollBack();
    			$result = '{"status":"ERROR"}';
    		}
    		//---sql事务---end--
    	}
    	
    	echo $result;

    }
    
    
    //获取游客购买的套餐
    public function actionGetwifiitemstatus()
    {
    	$passport = Yii::$app->request->post('PassportNO');
    	$wifi_status = Wifi::getWifiItemStatus($passport);
    	$item = '';
    	
    	$wifi_count = count($wifi_status);	//游客购买上网卡的数量
    	$index = 1;	//计数标志，用于计算是否最后一条上网卡信息
    	
    	foreach ($wifi_status as $wifi){
    		
    		$wifi_item = Wifi::getWifiItem($wifi['wifi_id']);
    		$wifi_info = Wifi::getWifiInfo($wifi['wifi_info_id']);
    		if($index < $wifi_count ){
    			//不是最后一条，在末尾加 ','
    			$index++;
    			$item .= '{"wifi_name" : "'.$wifi_item['wifi_name'].'","wifi_code":"'.$wifi_info['wifi_code'].'","wifi_info_id":"'.$wifi_info['wifi_info_id'].'","wifi_password":"'.$wifi_info['wifi_password'].'"},';
    		}else{
    			// 最后一条，不用加在末尾加 ','
    			$item .= '{"wifi_name" : "'.$wifi_item['wifi_name'].'","wifi_code":"'.$wifi_info['wifi_code'].'","wifi_info_id":"'.$wifi_info['wifi_info_id'].'","wifi_password":"'.$wifi_info['wifi_password'].'"}';
    		}
    		
    	}
    
    	$result = '{"status":"OK","data":['.$item.']}';
    	echo $result;
    }
    
    
    
    //网络连接
    public function actionWificonnect()
    {
    	$wifi_code = Yii::$app->request->post('wifi_code');
    	$wifi_password = Yii::$app->request->post('wifi_password');
    	

    	$flow_start = WifiConnect::getWifiFlow($wifi_code)->flow_start; //总流量 
    	$left_flow  = WifiConnect::getWifiFlow($wifi_code)->left_flow;	//剩余流量
    	
    	
    	
    	$sql = " SELECT time FROM wifi_info WHERE wifi_code = '$wifi_code'";
    	$turnOnTime = Yii::$app->db->createCommand($sql)->queryOne()['time'];
    	
    	$result = '{"status":"OK","data":{"wifi_code":"'.$wifi_code.'","wifi_password":"'.$wifi_password.'","turnOnTime":"'.$turnOnTime.'","flow_start":"'.$flow_start.'","left_flow":"'.$left_flow.'"}}';
    	echo $result;
    }
    
    
    
    //停用网络
    public function actionLogoutwificonnect()
    {
    	$result = '{"status":"OK"}';
    	echo $result;
    }
    
    
    
    
    
    //---   XML request Demo-----  begin --
    
    public function actionTestupdate()
    {
    	$time = date('Y-m-d H:i:s',time());
    	$wifi_id = 1;
		//通过wifi_id 更改wifi_info表中的数据状态,并获取被更改数据的wifi_info_id
   	 	$time = date('Y-m-d H:i:s',time());
		$sql = "SELECT wifi_info_id FROM wifi_info WHERE wifi_id='$wifi_id' AND status_sale=0 LIMIT 1";
		$wifi_info_id = Yii::$app->db->createCommand($sql)->queryOne()['wifi_info_id'];
		if($wifi_info_id){
			$sql = "UPDATE wifi_info SET status_sale='1' ,time='$time' WHERE wifi_info_id='$wifi_info_id'";
			Yii::$app->db->createCommand($sql)->execute();
			return $wifi_info_id;
		}else {
			return 0;
		}
    }
    
    
    
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
    
    
    
    public function actionSentjson()
    {
    	$wifi_code = '123456';
    	$flow = WifiConnect::getWifiFlow($wifi_code)->flow_start;
    	
		echo $flow;
    }
    
    
    public function actionTestjson()
    {
    	$data = '{"name":"123","left_flow":"50","flow_start":"100"}';
    	return $data;
    }
    //---   XML request Demo-----  end --
    	
}
    
  
