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
	
	
	//选择套餐界面
    public function actionIndex()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	$iso = Yii::$app->request->get('iso','zh_cn');
    	
    	$wifi_items = Wifi::getAllWifiItem($iso);
    	
        return $this->render('index',['wifi_items'=>$wifi_items,'Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
    }
    
    //确认支付界面
    public function actionConfirmorder()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	$iso = Yii::$app->request->get('iso','zh_cn');
    	$WifiItemId = Yii::$app->request->get('WifiItemId');
    	
    	$WifiItem = Wifi::getWifiItem($WifiItemId,$iso);
    	
    	return $this->render('confirm_order',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'WifiItemId'=>$WifiItemId,'WifiItem'=>$WifiItem,'iso'=>$iso]);
    	
    }
    
    //支付成功界面
    public function actionPaymentsuccessful()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	 
    	return $this->render('paymentsuccessful',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
    	 
    }
    
    
    //支付失败界面
    public function actionPaymentfail()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	 
    	return $this->render('paymentfail',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
    }
    
    
    
    //支付出错界面
    public function actionPaymenterror()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	 
    	return $this->render('paymenterror',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
    }
    
    //套餐售罄
    public function actionSoldout()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	 
    	return $this->render('soldout',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
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
    	
    	
    	
// //     	$result = '{"status":"NoCard"}';
// //     	$result = '{"status":"ERROR"}';
//     	$result = '{"status":"OK"}';
// //     	$result = '{"status":"FAIL"}';
//     	echo $result;
    }
    
    
    
    //显示游客购买的wifi套餐
    public function actionGetwifipackage()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	
    	$WifiStatus = Wifi::getWifiItemStatus($PassportNO);
    	
    	$tmp_Wifi_array = [];
    	$tmp_Wifi_info_array = [];
    	$Wifi_array = [];

    	if($WifiStatus){
    		
    		foreach ($WifiStatus as $wifi){
    			
    			$tmp_wifi_id = $wifi['wifi_id'];
    			$tmp_wifi_info_id = $wifi['wifi_info_id'];
    			
    			$Wifi_array[$tmp_wifi_id] = Wifi::getWifiItem($wifi['wifi_id']);
    			
    			$tmp_Wifi_array[$tmp_wifi_id] = Wifi::getWifiItem($wifi['wifi_id']);
    			$tmp_Wifi_info_array[$tmp_wifi_info_id] = Wifi::getWifiInfo($wifi['wifi_info_id']);
    			
    		}

    		foreach($tmp_Wifi_array as $item_key => $wifi_item){  				// loop 2 times
    			$k=1;
    			foreach($tmp_Wifi_info_array as $info_key => $wifi_info){		// loop 3 times
    				if($wifi_item['wifi_id'] == $wifi_info['wifi_id']){
    					$Wifi_array[$item_key]['wifi_info'][$info_key] = $wifi_info;
    					$Wifi_array[$item_key]['wifi_info'][$info_key]['index'] = $k++;
    				}
    			}
    		}

    		return $this->render('selectpackage',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'Wifi'=>$Wifi_array]);
    	}else{
    		//查找不到套餐时，显示没有套餐界面
    		return $this->render('noneavaliable',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
    	}
    }
    
    
    //网络连接
    public function actionWificonnect()
    {
    	$WifiInfoId = Yii::$app->request->get('WifiInfoId');
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	$iso = Yii::$app->request->post('iso');
    	
    	if($iso === 'null'){
    		$iso = 'zh_cn';
    	}
    	
    	if($WifiInfoId != ''){
    		
    		$wifi_info = Wifi::getWifiInfo($WifiInfoId);
    		$wifi_code = $wifi_info['wifi_code'];
    		$wifi_password = $wifi_info['wifi_password'];
    		$wifi_info_id = $wifi_info['wifi_info_id'];
    		$flow_start = isset(WifiConnect::getWifiFlow($wifi_code)->flow_start) ? WifiConnect::getWifiFlow($wifi_code)->flow_start : 0; 	//总流量
    		$left_flow  = isset(WifiConnect::getWifiFlow($wifi_code)->left_flow) ? WifiConnect::getWifiFlow($wifi_code)->left_flow : 0 ;  	//剩余流量
    		
    		//认证
    		$response = WifiConnect::PortalLogin($wifi_code,$wifi_password);
    		//如果查询的卡号剩余流量为0，设置他为流量耗尽
    		if($left_flow == 0){
    			$sql = "SELECT wifi_info_id FROM wifi_info WHERE wifi_code='$wifi_code'";
    			$wifi_info_id = Yii::$app->db->createCommand($sql)->queryOne()['wifi_info_id'];
    			$update_sql = "UPDATE wifi_item_status  SET status=1 WHERE wifi_info_id='$wifi_info_id'";
    			Yii::$app->db->createCommand($update_sql)->execute();
    		}

    		$sql = " SELECT time FROM wifi_info WHERE wifi_code = '$wifi_code'";
    		$turnOnTime = Yii::$app->db->createCommand($sql)->queryOne()['time'];
    		
    		
    		if($response == 0 || $response==2 ){
    			//连接成功，返回错误代码0
    			return $this->render('connect',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'iso'=>$iso,'WifiInfoId'=>$wifi_info_id,'WifiCode'=>$wifi_code,'WifiPassword'=>$wifi_password,'TurnOnTime'=>$turnOnTime,'FlowStart'=>$flow_start,'LeftFlow'=>$left_flow,'errorCode'=>'0']);
    		}else if($response == 9){
    			//多次连接，返回错误代码9
    			return $this->render('connect_error',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'errorCode'=>'9']);
    		}else {
    			//连接出错,返回错误代码-1
    			return $this->render('connect_error',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'errorCode'=>'-1']);
    		}
    	}else{
    		//没选择上网套餐，返回错误代码-2
    		return $this->render('connect_error',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'errorCode'=>'-2']);
    	}
    }
    
    //停用网络
    public function actionLogoutwificonnect()
    {
    	//访问下线接口
    	$response = WifiConnect::PortaLogout();
  
    	if($response == 0){
    		//注销完成
    		$result = '{"status":"OK","errorCode":"'.$response.'"}';
    	}else {
    		$result = '{"status":"ERROR","errorCode":"'.$response.'"}';
    	}
    	echo $result;
    }
    
    public function actionLogoutfail()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	return $this->render('logoutfail',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,]);
    }
    
    
    
   
}
    
  
