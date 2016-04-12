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
    	$wifi_id = Yii::$app->request->post('wifi_id');
    	$iso = Yii::$app->request->post('iso');

    	if($iso === 'null'){
    		$iso = 'zh_cn';
    	}
    	
    	if(isset($wifi_id)){
    		$wifi_item = Wifi::getWifiItem($wifi_id,$iso);
    		$item = '{"sale_price":"'.$wifi_item['sale_price'].'","wifi_name":"'.$wifi_item['wifi_name'].'"}';
    	}else{
    		$item = '';
    	}
    	
    	$result = '{"status":"OK","data":'.$item.'}';
    	echo $result;
    }
    
    
/*
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
    	$identififer = $passport.".".time();
    	$wifi_item = Wifi::getWifiItem($wifi_id,$iso);
    	$checkNumber = WifiPay::createChecknum(); 
    	$type = WifiPay::isIBSPay(); //是否通过ibs收费系统计费
    	
    	if($type){
    		//---sql事务---
    		$transaction = Yii::$app->db->beginTransaction();
    		try {
    			//.调用支付接口
    			$postResponse = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price'],$identififer);
    			//.判断 PostchargeResponse XML
    			$PostchargeResponse = Wifi::xmlUnparsed($postResponse);
    			if(isset($PostchargeResponse->attributes()->Code)){
    				// 如果error，就返回 '{"status":"ERROR"}'，
    				$result = '{"status":"ERROR"}';
    			}else {
    				//.记录本地数据库的支付信息
    				$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
    				//.购买上网卡
    				Wifi::wifiCardBuy($wifi_id,$passport,$pay_log_id);
    				$result = '{"status":"OK"}';
    			}
    			$transaction->commit();
    		}catch(Exception $e){
    			$transaction->rollBack();
    			$result = '{"status":"ERROR"}';
    		}
    		//---sql事务---end-- 
	    	
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
	    		$result = '{"status":"OK","name":"'.$name.'"}';
	    		
    		}catch(Exception $e){
    			$transaction->rollBack();
    			$result = '{"status":"ERROR"}';
    		}
    		//---sql事务---end--
    	}
    	echo $result;
    }
    
*/
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
    	 
    	$checkNumber = WifiPay::createChecknum();
    	$identififer = $passport.".".time();
    
    	$type = WifiPay::isIBSPay(); //是否通过ibs收费系统计费
    	 
    	if($type){
    
    		//通过ibs系统支付
    		//---sql事务---
			$transaction = Yii::$app->db->beginTransaction();
    		try {
    			//3.调用支付接口
    			$postResponse = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price'],$identififer);
    			if($postResponse != ""){
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
    			}else {
    				$result = '{"status":"ERROR"}';
    			}
    		}catch(Exception $e){
    			$transaction->rollBack();
    			$result = '{"status":"ERROR"}';
    		}
    		//---sql事务---end--
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
    			$result = '{"status":"OK","name":"'.$name.'"}';
    	   
    		}catch(Exception $e){
    			$transaction->rollBack();
    			$result = '{"status":"ERROR"}';
    		}
    		//---sql事务---end--
    	}
    	 
    	echo $result;
    
    }

     
  /*  
    //支付wifi套餐
    public function actionPayment()
    {
    	$url = Wifi::selectUrl('ibs_request_url');
    	$content = file_get_contents('php://input');
    	if($content){
    		if(isset( Wifi::xmlUnparsed($content)->Header)){
    			$type_response = 0; 	//'类型，0:接收 1:发送',
    			$PostchargeResponse = Wifi::xmlUnparsed($content);
    			$header = $PostchargeResponse->Header;
    			$time = date('Y-m-d H:i:s',time());
    			$identififer = $header->attributes()->MessageIdentifier;
    			//记录 PostchargeResponse 返回的XML内容
    			Wifi::writeXMLToDB($content,$type_response,$time,$identififer);
    			exit;
    		}else{
    			//发送xml内容
    			$wifi_id = Yii::$app->request->post('wifi_id');
    			$passport = Yii::$app->request->post('PassportNO');
    			$TenderType = Yii::$app->request->post('TenderType');
    			$name = Yii::$app->request->post('Name');
    			$iso = Yii::$app->request->post('iso');
    			if($iso === 'null'){
    				$iso = 'zh_cn';
    			}
    			 
    			$identififer = $passport.".".time();
    			$wifi_item = Wifi::getWifiItem($wifi_id,$iso);
    			$checkNumber = WifiPay::createChecknum();
    			$type = WifiPay::isIBSPay();
    			if($type){
    				//通过ibs收费系统计费
    				WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price'],$identififer);
    				//把$identififer传到前端
    				$result = '{"status":"OK","type":"1","server_name":"'.$_SERVER["SERVER_NAME"].'","url":"'.$url.'","identififer":"'.$identififer.'"}';
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
    					$result = '{"status":"OK","type":"0","identififer":"'.$identififer.'"}';
    			
    				}catch(Exception $e){
    					$transaction->rollBack();
    					$result = '{"status":"ERROR","type":"0"}';
    				}
    				//---sql事务---end--
    			}
    			echo $result;
    		}
    	}
    }
	
    
   	
 */ 
    
  /*  
    //IBSwifi的回传内容,保存到数据库中  todo
    public function actionIbsresponse()
    {
    	if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
    		// 接收
    		$content = file_get_contents('php://input');
    		$type_response = 0; 	//'类型，0:接收 1:发送',
    		if($content){
    			$PostchargeResponse = Wifi::xmlUnparsed($content);
    			$header = $PostchargeResponse->Header;
    			$time = date('Y-m-d H:i:s',time());
    			$identififer = $header->attributes()->MessageIdentifier;
    			//记录 PostchargeResponse 返回的XML内容
    			Wifi::writeXMLToDB($content,$type_response,$time,$identififer);
    		}
    		exit;
    	}
    }
   
    
    //ajax查找数据库，并且下单
    public function actionGetxmlfromdb()
    {
    	$identififer = Yii::$app->request->post('identififer');
    	$wifi_id = Yii::$app->request->post('wifi_id');
    	$passport = Yii::$app->request->post('PassportNO');
    	$iso = Yii::$app->request->post('iso');
    	if($iso === 'null'){
    		$iso = 'zh_cn';
    	}
    	$name = Yii::$app->request->post('Name');
    	$wifi_item = Wifi::getWifiItem($wifi_id,$iso);
    	$checkNumber = WifiPay::createChecknum();
    	
    	//查找数据表
    	$sql = " SELECT content FROM ibsxml_log WHERE identififer='$identififer' AND type=0 ";
    	$data = Yii::$app->db->createCommand($sql)->queryOne()['content'];
    	if($data){
    		//解析数据
    		$PostchargeResponse = Wifi::xmlUnparsed($data);
    		$body = $PostchargeResponse->Body;
    		if($body->attributes()->Code){
    			//支付失败
    			$result = '{"status":"Fail"}';
    		}else{
    			$transaction = Yii::$app->db->beginTransaction();
    			try {
    				//1.记录本地数据库的支付信息
    				$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
    				//2.购买上网卡
    				Wifi::wifiCardBuy($wifi_id,$passport,$pay_log_id);
    				$transaction->commit();
    				$result = '{"status":"OK","$pay_log_id":"'.$pay_log_id.'"}';
    			}catch(Exception $e){
    				$transaction->rollBack();
    				$result = '{"status":"Fail"}';
    			}
    		}
    	}else{
    		//没找到数据
    		$result = '{"status":"Noitem","$identififer":"'.$identififer.'"}';
    	}
    	echo $result;
    }
    
   */
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
    	
    	$flow_start = isset(WifiConnect::getWifiFlow($wifi_code)->flow_start) ? WifiConnect::getWifiFlow($wifi_code)->flow_start : 0; 	//总流量
    	$left_flow  = isset(WifiConnect::getWifiFlow($wifi_code)->left_flow) ? WifiConnect::getWifiFlow($wifi_code)->left_flow : 0 ;  	//剩余流量
    	
    	$sql = " SELECT time FROM wifi_info WHERE wifi_code = '$wifi_code'";
    	$turnOnTime = Yii::$app->db->createCommand($sql)->queryOne()['time'];
    	
    	//认证
    	$response = WifiConnect::PortalLogin($wifi_code,$wifi_password);
    	if($response != ''){
    		$errorCode = $response['errorCode'];
    		$wlanuserip = $response['wlanuserip'];
    		$wlanacip = $response['wlanacip'];
    		//$wlanuserip，$wlanacip 存储到数据库中
    		WifiConnect::SaveWlanParams($wifi_code,$wlanuserip,$wlanacip);
    	}
    	
    	$result = '{"status":"OK","data":{"wifi_code":"'.$wifi_code.'","wifi_password":"'.$wifi_password.'","turnOnTime":"'.$turnOnTime.'","flow_start":"'.$flow_start.'","left_flow":"'.$left_flow.'"}}';
    	echo $result;
    }
    

    //停用网络  
    public function actionLogoutwificonnect()
    {
    	$wifi_code = Yii::$app->request->post('wifi_code');
    	$wifi_password = Yii::$app->request->post('wifi_password');
    	//访问下线接口
    	$response = WifiConnect::PortaLogout($wifi_code);
    	
    	if($response == 0){
    		//注销完成
    		
    	}
    	$result = '{"status":"OK"}';
    	echo $result;
    }
    
    
    
    
    
    //test code
    public function actionTest()
    {
//     	$url = "http://localhost/wifiservice/web/wifi/payment";
    	$xml = "<?xml version='1.0' encoding='utf-8' ?><DTSPostChargeResponse><Header Action='Lufthansa' CreationDateTime='2016-03-22 02:50:03' DocumentDefinition='DTSPostChargeResponse' MessageIdentifier='13408515.20160322' SourceApplication='DTS'/><Body><PostCharge CheckNum='L490282' FolioID='0000902524' PassportNO='H123456' PostingDate='2016-03-22' PostingTime='02:50:03'/></Body></DTSPostChargeResponse>";
//     	$res = Wifi::httpsRequest($url,$xml);
    	echo $xml;
    }
    
    
    public function actionSend()
    {
    	$passport = '123456';
    	$TenderType='02';
    	$checkNumber = WifiPay::createChecknum(); 
    	$identififer = $passport.".".time();
    	$res = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,'100',$identififer);
    	echo $res;
    }
    
    //test code end
    
    
    
}
    
  
