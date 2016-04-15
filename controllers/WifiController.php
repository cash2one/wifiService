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
    		$item = '{"sale_price":"'.$wifi_item['sale_price'].'","wifi_flow":"'.$wifi_item['wifi_flow'].'","wifi_name":"'.$wifi_item['wifi_name'].'"}';
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
    	$checkNumber = WifiPay::createChecknum();
    	$identififer = $passport.".".time();
    	
    	//.先查询上网卡是否存在, 如果存在, 把卡号的状态设置为已售出,  通过wifi_id来查找
    	$wifi_info_id = Wifi::CheckCardAndSetSold($wifi_id);
    	if($wifi_info_id){
    		
    		$ibsPayType = WifiPay::isIBSPay(); //是否通过ibs收费系统计费
    		if($ibsPayType){
    			//通过ibs系统支付
    			$checkBalance = WifiPay::isCheckBalance();  //是否需要查询余额
    		
    			if($checkBalance){
    				//需要查询余额
    		
    				//.发送 xml,请求FolioBalance接口
    				$balanceResponse = WifiPay::folioBalance($passport,$identififer);
    				 
    				//.解析FolioBalance xml报文格式
    				$balance = Wifi::xmlUnparsed($balanceResponse);
    				 
    				//.判断 FolioBalance xml 报文格式是否正确
    				if(isset($balance->Body)){
    					$balanceDue = $balance->Body->FolioBalance->attributes()->BalanceDue;		//解析xml，ibs返回的余额
    					if($balanceDue < $wifi_item['sale_price']){
    						//余额不足，返回 '{"status":"FAIL"}'，
    						$result = '{"status":"FAIL"}';
    						//把卡号的状态设置为代售状态
    						Wifi::SetUnsold($wifi_info_id);
    					}else{
    						
    						//---sql事务---begin--
    						$transaction = Yii::$app->db->beginTransaction();
    						try {
    							//.调用支付接口
    							$postResponse = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price'],$identififer);
    							
    							if($postResponse != ""){
    								//.判断 PostchargeResponse XML
    								$PostchargeResponse = Wifi::xmlUnparsed($postResponse);
    								
    								if(isset($PostchargeResponse->attributes()->Code)){
    									// 解析DTSPostCharge xml报文出错，返回 '{"status":"ERROR"}'，
    									$result = '{"status":"ERROR"}';
    									
    									//把卡号的状态设置为代售状态
    									Wifi::SetUnsold($wifi_info_id);
    								}else {
    									//.记录本地数据库的支付信息
    									$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
    									
    									//.购买上网卡
    									Wifi::wifiCardBuy($wifi_info_id,$passport,$pay_log_id);
    									$result = '{"status":"OK"}';
    								}
    								
    								$transaction->commit();
    							}else {
    								//支付时，收到的DTSPostCharge xml报文为空，出错
    								$result = '{"status":"ERROR"}';
    								//把卡号的状态设置为代售状态
    								Wifi::SetUnsold($wifi_info_id);
    							}
    						}catch(Exception $e){
    							$transaction->rollBack();
    							$result = '{"status":"ERROR"}';
    							//把卡号的状态设置为代售状态
    							Wifi::SetUnsold($wifi_info_id);
    							
    						}
    						//---sql事务---end---
    						
    					}
    				}else{
    					//.接收到的FolioBalance xml 报文解析出错，或者接收不到报文
    					$result = '{"status":"ERROR"}';
    					//把卡号的状态设置为代售状态
    					Wifi::SetUnsold($wifi_info_id);
    				}
    			}else {
    				//不查询余额，直接支付
    				
    				//---sql事务---begin--
    				$transaction = Yii::$app->db->beginTransaction();
    				try {
    					//.调用支付接口
    					$postResponse = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price'],$identififer);
    						
    					if($postResponse != ""){
    						//.判断 PostchargeResponse XML
    						$PostchargeResponse = Wifi::xmlUnparsed($postResponse);
    				
    						if(isset($PostchargeResponse->attributes()->Code)){
    							// 解析DTSPostCharge xml报文出错，返回 '{"status":"ERROR"}'，
    							$result = '{"status":"ERROR"}';
    								
    							//把卡号的状态设置为代售状态
    							Wifi::SetUnsold($wifi_info_id);
    						}else {
    							//.记录本地数据库的支付信息
    							$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
    								
    							//.购买上网卡
    							Wifi::wifiCardBuy($wifi_info_id,$passport,$pay_log_id);
    							$result = '{"status":"OK"}';
    						}
    				
    						$transaction->commit();
    					}else {
    						//支付时，收到的DTSPostCharge xml报文为空，出错
    						$result = '{"status":"ERROR"}';
    						//把卡号的状态设置为代售状态
    						Wifi::SetUnsold($wifi_info_id);
    					}
    				}catch(Exception $e){
    					$transaction->rollBack();
    					$result = '{"status":"ERROR"}';
    					//把卡号的状态设置为代售状态
    					Wifi::SetUnsold($wifi_info_id);
    						
    				}
    				//---sql事务---end---
    				
    			}
    		
    		}else{
    			//不通过ibs系统支付
    			
    			//---sql事务---begin--
    			$transaction = Yii::$app->db->beginTransaction();
    			try {
    				//.记录本地数据库的支付信息
	    			$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
	    			
	    			//.购买上网卡
	    			Wifi::wifiCardBuy($wifi_info_id,$passport,$pay_log_id);
	    			$result = '{"status":"OK"}';
	    			$transaction->commit();
    			}catch(Exception $e){
    				$transaction->rollBack();
    				$result = '{"status":"ERROR"}';
    				//把卡号的状态设置为代售状态
    				Wifi::SetUnsold($wifi_info_id);
    			}
    			//---sql事务---end--
    		
    		}
    	}else{
    		$result = '{"status":"NoCard"}';
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
    	 
    	$flow_start = isset(WifiConnect::getWifiFlow($wifi_code)->flow_start) ? WifiConnect::getWifiFlow($wifi_code)->flow_start : 0; 	//总流量
    	$left_flow  = isset(WifiConnect::getWifiFlow($wifi_code)->left_flow) ? WifiConnect::getWifiFlow($wifi_code)->left_flow : 0 ;  	//剩余流量
    	 
    	//如果查询的卡号剩余流量为0，设置他为流量耗尽
    	if($left_flow == 0){
    		$sql = "UPDATE wifi_item_status  SET status=1 WHERE wifi_info_id=(SELECT wifi_info_id FROM wifi_info WHERE wifi_code=$wifi_code)";
    		Yii::$app->db->createCommand($sql)->execute();
    	}
    	
    	$sql = " SELECT time FROM wifi_info WHERE wifi_code = '$wifi_code'";
    	$turnOnTime = Yii::$app->db->createCommand($sql)->queryOne()['time'];
    	//认证
    	$response = WifiConnect::PortalLogin($wifi_code,$wifi_password);

    	if($response == 0 || $response==2 || $response==9){
    		$result = '{"status":"OK","errorCode":"'.$response.'","data":{"wifi_code":"'.$wifi_code.'","wifi_password":"'.$wifi_password.'","turnOnTime":"'.$turnOnTime.'","flow_start":"'.$flow_start.'","left_flow":"'.$left_flow.'"}}';
    	}else {
    		$result = '{"status":"ERROR","errorCode":"'.$response.'"}';
    	}
    	
    	echo $result;
    }
    
  
    //停用网络  
    public function actionLogoutwificonnect()
    {
    	$wifi_code = Yii::$app->request->post('wifi_code');
    	$wifi_password = Yii::$app->request->post('wifi_password');
    	//访问下线接口
    	$response = WifiConnect::PortaLogout($wifi_code);
 		echo $response;exit;
    	if($response == 0){
    		//注销完成
    		$result = '{"status":"OK"}';
    	}else {
    		$result = '{"status":"ERROR","errorCode":"'.$response.'"}';
    	}
    	echo $result;
    }
    
}
    
  
