<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\components\Wifi;
use app\components\WifiPay;
use app\components\WifiConnect;

class WifiController extends Controller
{
	public $layout = false;  	//don't use the default theme layout 
	public $enableCsrfValidation = false; // csrf validation can't work
	
	//App进入wifi连接的入口，用来判断用户是否购买了wifi套餐
    public function actionIndex()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
//     	$iso = Yii::$app->request->get('iso','zh_cn');
//     	$wifi_items = Wifi::getAllWifiItem($iso);

    	$wifi_count  = count(Wifi::getWifiItemStatus($PassportNO));
    	
    	if($wifi_count > 0){
    		//跳转到查询卡号是否在线状态界面
    		return Yii::$app->getResponse()->redirect(Url::to("checkloginstatus")."?Name=$Name&PassportNO=$PassportNO&TenderType=$TenderType");
    	}else{
    		//跳转到wifi购买界面
    		return Yii::$app->getResponse()->redirect(Url::to("showpaymentpage")."?Name=$Name&PassportNO=$PassportNO&TenderType=$TenderType");
    	}
    	
//     	return $this->render('index',['wifi_items'=>$wifi_items,'Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
//      return Yii::$app->getResponse()->redirect(Url::to("/wifiservice/wifi/checkloginstatus")."?Name=$Name&PassportNo=$PassportNo&TenderType=$TenderType");
    }
    
    //显示wifi购买页面
	public function actionShowpaymentpage()
	{
		$Name = Yii::$app->request->get('Name','');
		$PassportNO = Yii::$app->request->get('PassportNO','');
		$TenderType = Yii::$app->request->get('TenderType','');
		$iso = Yii::$app->request->get('iso','zh_cn');
		$wifi_items = Wifi::getAllWifiItem($iso);
		
		return $this->render('showpaymentpage',['wifi_items'=>$wifi_items,'Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType]);
	}
    
    //判断游客帐号是否在线
    public function actionCheckloginstatus()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	$iso = Yii::$app->request->get('iso');
    	if($iso === 'null'){
    		$iso = 'zh_cn';
    	}
    	
    	
//     	$tmp_Wifi_info_array = [];	//临时的数组，用于存放 wifiInfo
//     	$status = 0;		//在先状态，0为不在线
//     	$WifiInfoId =0;		//在线的wifiInfoId，当前在线的卡号的id
    	
    	
//     	$WifiStatus = Wifi::getWifiItemStatus($PassportNO);		//获取游客购买的上网卡
//     	foreach ($WifiStatus as $wifi){
//     		$tmp_wifi_info_id = $wifi['wifi_info_id'];
//     		$tmp_Wifi_info_array[$tmp_wifi_info_id] = Wifi::getWifiInfo($wifi['wifi_info_id']);
//     	}

    	
//     	foreach($tmp_Wifi_info_array as $info_key => $wifi_info){
//     		$online_status = WifiConnect::getWifiFlow($wifi_info['wifi_code'])->online_status;	//获取卡号的在线状态
//     		if($online_status != 0){
//     			$status = $online_status;
//     			$WifiInfoId = $wifi_info['wifi_info_id'];
//     			break;
//     		}
//     	}
    	
//     	var_dump($WifiInfoId);exit;
//     	return Yii::$app->getResponse()->redirect(Url::to("getwifipackage")."?Name=$Name&PassportNO=$PassportNO&TenderType=$TenderType");
    	
    	
    	//查找数据库，获取用户最近使用的一张卡号的帐号和密码
    	$card = Wifi::getConnectWifiCardFromDB($PassportNO);
    	if($card){
    		$card_number = $card['card_number'];
    		$card_password = $card['card_password'];
    		$WifiInfoId = $card['wifi_info_id'];
    		 
    		$sql = " SELECT time FROM wifi_info WHERE wifi_code = '$card_number'";
    		$turnOnTime = Yii::$app->db->createCommand($sql)->queryOne()['time'];
    		 
    		//通过curl发送卡号帐号查询在线状态
    		$response = WifiConnect::getWifiFlow($card_number);
    		$online_status = isset($response->online_status) ? $response->online_status : 0;
    		$flow_start = isset($response->flow_start) ? $response->flow_start : 0; 	//总流量
    		$left_flow  = isset($response->left_flow) ? $response->left_flow : 0 ;  	//剩余流量
    		
    		if($online_status){
    			//如果在线，显示wificonnect界面
    			return $this->render('connect',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'iso'=>$iso,'WifiInfoId'=>$WifiInfoId,'WifiCode'=>$card_number,'WifiPassword'=>$card_password,'TurnOnTime'=>$turnOnTime,'FlowStart'=>$flow_start,'LeftFlow'=>$left_flow,'errorCode'=>'0']);
    		}else{
    			//如果没在线，显示选择套餐界面。
    			return Yii::$app->getResponse()->redirect(Url::to("getwifipackage")."?Name=$Name&PassportNO=$PassportNO&TenderType=$TenderType");
    		}
    	}else {
    		//用户没有连接过，显示选择套餐界面
    		return Yii::$app->getResponse()->redirect(Url::to("getwifipackage")."?Name=$Name&PassportNO=$PassportNO&TenderType=$TenderType");
    	}
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
    	//先查询上网卡是否存在, 如果存在, 把卡号的状态设置为已售出,  通过wifi_id来查找
    	$wifi_info_id = Wifi::CheckCardAndSetSold($wifi_id);
    	if($wifi_info_id){
    		$ibsPayType = WifiPay::isIBSPay(); //是否通过ibs收费系统计费
    		if($ibsPayType){
    			//通过ibs系统支付
    			$checkBalance = WifiPay::isCheckBalance();  //是否需要查询余额
    			if($checkBalance){
    				//需要查询余额
    				//发送 xml,请求FolioBalance接口
    				$balanceResponse = WifiPay::folioBalance($passport,$identififer);
    				//解析FolioBalance xml报文格式
    				$balance = Wifi::xmlUnparsed($balanceResponse);
    				//新增的判断，未测试过   TODO
    				if(isset($balance->attributes()->Code)){
    					// 解析FolioBalance xml报文出错，返回 '{"status":"ERROR"}'，
    					$result = '{"status":"ERROR","message":"IBS send a error message"}';
    				}else {
    					//判断 FolioBalance xml 报文格式是否正确
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
    								//调用支付接口
    								$postResponse = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price'],$identififer);
    								if($postResponse != ""){
    									//判断 PostchargeResponse XML
    									$PostchargeResponse = Wifi::xmlUnparsed($postResponse);
    									if(isset($PostchargeResponse->attributes()->Code)){
    										// 解析DTSPostCharge xml报文出错，返回 '{"status":"ERROR"}'，
    										$result = '{"status":"ERROR","message":"IBS send a error message"}';
    										//把卡号的状态设置为代售状态
    										Wifi::SetUnsold($wifi_info_id);
    									}else {
    										//记录本地数据库的支付信息
    										$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
    										//.购买上网卡
    										Wifi::wifiCardBuy($wifi_info_id,$passport,$pay_log_id);
    										$result = '{"status":"OK"}';
    									}
    									$transaction->commit();
    								}else {
    									//支付时，收到的DTSPostCharge xml报文为空，出错
    									$result = '{"status":"ERROR","message":"Error,Can not recept IBS response"}';
    									//把卡号的状态设置为代售状态
    									Wifi::SetUnsold($wifi_info_id);
    								}
    							}catch(Exception $e){
    								$transaction->rollBack();
    								$result = '{"status":"ERROR","message":"System ERROR"}';
    								//把卡号的状态设置为代售状态
    								Wifi::SetUnsold($wifi_info_id);
    							}
    							//---sql事务---end---
    						}
    					}else{
    						//.接收到的FolioBalance xml 报文解析出错，或者接收不到报文
    						$result = '{"status":"ERROR","message":"Error,Can not recept IBS response"}';
    						//把卡号的状态设置为代售状态
    						Wifi::SetUnsold($wifi_info_id);
    					}
    				}
    			
    			}else {
    				//不查询余额，直接支付
    				//---sql事务---begin--
    				$transaction = Yii::$app->db->beginTransaction();
    				try {
    					//.调用支付接口
    					$postResponse = WifiPay::DTSPostCharge($passport,$TenderType,$checkNumber,$wifi_item['sale_price'],$identififer);
    					if($postResponse != ""){
    						//判断 PostchargeResponse XML
    						$PostchargeResponse = Wifi::xmlUnparsed($postResponse);
    						if(isset($PostchargeResponse->attributes()->Code)){
    							// 解析DTSPostCharge xml报文出错，返回 '{"status":"ERROR"}'，
    							$result = '{"status":"ERROR","message":"Error,Can not recept IBS response"}';
    							//把卡号的状态设置为代售状态
    							Wifi::SetUnsold($wifi_info_id);
    						}else {
    							//记录本地数据库的支付信息
    							$pay_log_id = Wifi::writePayLogToDB($checkNumber,$passport,$name,$wifi_item['sale_price']);
    							//.购买上网卡
    							Wifi::wifiCardBuy($wifi_info_id,$passport,$pay_log_id);
    							$result = '{"status":"OK"}';
    						}
    						$transaction->commit();
    					}else {
    						//支付时，收到的DTSPostCharge xml报文为空，出错
    						$result = '{"status":"ERROR","message":"Error,Can not recept IBS response"}';
    						//把卡号的状态设置为代售状态
    						Wifi::SetUnsold($wifi_info_id);
    					}
    				}catch(Exception $e){
    					$transaction->rollBack();
    					$result = '{"status":"ERROR","message":"System ERROR"}';
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
    				$result = '{"status":"ERROR","message":"System ERROR"}';
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
			//查流量 ，查在线状态
    		foreach($tmp_Wifi_array as $item_key => $wifi_item){  				// loop 2 times
    			$k=1;
    			foreach($tmp_Wifi_info_array as $info_key => $wifi_info){		// loop 3 times
    				if($wifi_item['wifi_id'] == $wifi_info['wifi_id']){
    					$Wifi_array[$item_key]['wifi_info'][$info_key] = $wifi_info;
//     					$Wifi_array[$item_key]['wifi_info'][$info_key]['index'] = $k++;
    					$flow = WifiConnect::getWifiFlow($wifi_info['wifi_code'])->left_flow;	//剩余流量
    					$Wifi_array[$item_key]['wifi_info'][$info_key]['flow'] = $flow;
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
     			//把用户使用的id,卡号和密码写入数据库中，用于下次进入连接界面时判断在线状态
    			Wifi::writeConnectWifiCardToDB($PassportNO,$wifi_info_id,$wifi_code,$wifi_password);
    			return $this->render('connect',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'iso'=>$iso,'WifiInfoId'=>$wifi_info_id,'WifiCode'=>$wifi_code,'WifiPassword'=>$wifi_password,'TurnOnTime'=>$turnOnTime,'FlowStart'=>$flow_start,'LeftFlow'=>$left_flow,'errorCode'=>'0']);
    		}else if($response == 9){
    			//多次连接，返回错误代码9
    			return $this->render('repeatlogin',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,'errorCode'=>'9']);
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
//     	$result = '{"status":"OK","errorCode":"0"}';
//     	echo $result;
    	
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
    
    //停用网络失败
    public function actionLogoutfail()
    {
    	$Name = Yii::$app->request->get('Name','');
    	$PassportNO = Yii::$app->request->get('PassportNO','');
    	$TenderType = Yii::$app->request->get('TenderType','');
    	return $this->render('logoutfail',['Name'=>$Name,'PassportNO'=>$PassportNO,'TenderType'=>$TenderType,]);
    }
   
}
    
  
