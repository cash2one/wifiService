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
    		$item = '{"sale_price":"'.$wifi_item['sale_price'].'","wifi_name":"'.$wifi_item['wifi_name'].'"}';
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
    	

    	$type = WifiPay::isIBSPay(); //是否通过ibs收费系统计费
    	
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
	    		$result = '{"status":"OK","name":"'.$name.'"}';
	    		
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
    
    
    
    public function actionResponsefail()
    {
    	$xml =  "<?xml version='1.0' encoding='utf-8' ?>
				<DTSFailResponse><Header Action='Nano' CreationDateTime='2016-03-21 23:49:27' DocumentDefinition='DTSFailResponse' MessageIdentifier='13408284.20160321' SourceApplication='DTS'/>
    			<Body Code='22' ErrorDescription='Unknown Error!'>
    			<OriginalMessage>FolioReviewRequest</OriginalMessage>
    			</Body>
    			</DTSFailResponse>";
    	
    	$postObj = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
    	$PostCharge = $postObj->Body->attributes()->Code;
    	if(isset($PostCharge)){
    		echo '11';
    	}
    }
    
    public function actionBaidu()
    {
//     	$url = "http://www.qq.com/";
//     	$res = Wifi::httpsRequest($url);
// 		$wifi_code = '12345';
//     	$response = WifiConnect::SaveWlanParams($wifi_code,22,22);
		$wifi_code = '1234';
//     	$url = Wifi::selectUrl('flow_url');
//     	$url = Wifi::selectUrl('ibs_request_url');
//     	$url = Wifi::selectUrl('portal_url');
    	$url = Wifi::selectUrl('request_url');
    	echo $url."?name=".$wifi_code;
//     	var_dump($res);
    }
    
    
    public function actionTest()
    {
//     	$url = "www.wifiservice.com/wifi/responsefoliobalance";
//     	$url = "www.wifiservice.com/wifi/response";
    
    	$data = "<?xml version='1.0' encoding='utf-8' ?>
    			<DTSPostCharge>
    				<Header Action='PMS' Comment='Pay by the Minute: CREW Rate  Start:3/22/2016 2:15:31 AM End:3/22/2016 2:28:28 AM IP:172.28.25.129' CreationDateTime='2016-03-22 06:47:40' DocumentDefinition='' MessageIdentifier='7b6e2866-f5e9-48c1-8b27-9568946e3eed' SourceApplication='WIFI'/>
    				<Body>
    					<PostCharge CheckNumber='L490282' Department='WIFI' FolioID='0000902524' PassportNO='H123456' Gratuity='' OriginatingSystemID='WIFI' SalesAmount='2.47' TaxAmount='' TenderType='03' TotalSales='2.47' TransactionDate='20160322' TransactionTime='02:47:40'/>
    				</Body>
    			</DTSPostCharge>
    			";
    	
    	
    	$time = $time = date('Y-m-d H:i:s',time());
    	$passport = '123456';
    	$url = 'www.wifiservice.com/wifi/responsefoliobalancedata';
    	$xml = "<?xml version='1.0' encoding='utf-8' ?>
	    	<DTSFolioBalance>
	    	<Header Action='PMS' CreationDateTime='$time' SourceApplication='WIFI'/>
	    	<Body>
	    	<FolioBalance PassportNO='$passport' />
	    	</Body>
	    	</DTSFolioBalance>
    	";
    
    	$res = Wifi::httpsRequest($url, $xml);
    	$postObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
    	var_dump($res);
    	
//     	$this->actionTestresponse($res);
  	
    }
    
    
    public function actionResponsefoliobalancedata()
    {
    	$passport = '123456';
    	$time = $time = date('Y-m-d H:i:s',time());
    	$xml = "<?xml version='1.0' encoding='utf-8' ?>
    	<DTSFolioBalance>
    	<Header Action='PMS' CreationDateTime='$time' SourceApplication='WIFI'/>
    	<Body>
    	<FolioBalance PassportNO='$passport' />
    	</Body>
    	</DTSFolioBalance>
    	";
    	return $xml;
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
    
    
    public function actionResponsefoliobalance()
    {
    	$data = "<?xml version='1.0' encoding='utf-8' ?>
				<DTSFolioBalanceResponse>
    				<Header Action='PMS' Comment='' CreationDateTime='2016-03-22 06:47:40' DocumentDefinition='' MessageIdentifier='7b6e2866-f5e9-48c1-8b27-9568946e3eed' SourceApplication='WIFI'/>
    				<Body>
    					<FolioBalance PassportNO='H123456'  BalanceDue='99.99' />
    				</Body>
    			</DTSFolioBalanceResponse> 
    			";
    	return $data;
    }
    
    
    
    
    
    public function actionTestresponse($res)
    {
//     	if (!empty($res)){
//     		$postObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
//     		$body = $postObj->Body->PostCharge;
//     		echo $body->attributes()->CheckNumber;
    		
//     	}
    	
    	
    	if (!empty($res)){
    		$postObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
    		$body = $postObj->Body->FolioBalance;
    		echo $body->attributes()->BalanceDue;
    	
    	}
    }
    
    public function actionTestflow()
    {
    	$wifi_code = "1000101122";
    	$url = "http://106.39.37.48:8080/fms/ws/queryFlowInfo?user_name=".$wifi_code;
    	$url = Yii::$app->params['flow_url'].$wifi_code;
    	$json = Wifi::httpsRequest($url);
    	$response = json_decode(Wifi::httpsRequest($url));
    	if(isset(json_decode(Wifi::httpsRequest($url))->flow_start)){
    		echo "true";
    	}else{
    		echo "false";
    	}
    	echo "<br/>";
    	var_dump($response);
    	echo "<br/>";

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
    
    public function actionBaiducurl()
    {
//     	$url = 'http://www.wifiservice.com/wifi/testpage';
    	$username = '123456';
    	$userpasswd = '123456';
    	$content =  WifiConnect::PortalLogin($username,$userpasswd);
    	
    	if($content != ''){
    		var_dump($content);
    	}else{
    		echo "22";
    	}
//     	$url = 'http://www.baidu.com';
//     	$ch = curl_init();
//     	curl_setopt($ch, CURLOPT_URL, $url);
//     	curl_setopt($ch, CURLOPT_HEADER, TRUE);	//表示需要response header
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// 		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		
// 		$response = curl_exec($ch);

// 		// Then, after your curl_exec call:
// 		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
// 		$header = substr($response, 0, $header_size);
// 		$matches = array();
// 		$content = preg_match('/Location:(.*?)\n/',$header,$matches);
// 		$url =  str_replace("Location:",'', $matches[0]);
// 		echo $url;
		
// 		$url = @parse_url(trim(array_pop($matches)));
// 		var_dump($url);
		
		
    }
    
    
    public function actionParseurl()
    {
    	$url = "123.57.182.84:8080/?ssid=test800&wlanacip=123.57.182.84&apid=EA113E6B-652C-4B04-83D6-B22948BE832E&wlanuserip=192.168.68.218&wlanusermac=70-1A-04-FF-12-3C&wlanusersn=235394546&wlanuserfirsturl=www%2Ebaidu%2Ecom%2F";
    	$response = WifiConnect::ParseURL($url);
    	echo $response['ssid']."<br/>";
    	echo $response['wlanacip']."<br/>";
    	echo $response['apid']."<br/>";
    	echo $response['wlanuserip']."<br/>";
    	echo $response['wlanusermac']."<br/>";
    	echo $response['wlanusersn']."<br/>";
    	
    	//array(7) { ["ssid"]=> string(7) "test800" ["wlanacip"]=> string(13) "123.57.182.84" ["apid"]=> string(36) "EA113E6B-652C-4B04-83D6-B22948BE832E" ["wlanuserip"]=> string(14) "192.168.68.218" ["wlanusermac"]=> string(17) "70-1A-04-FF-12-3C" ["wlanusersn"]=> string(9) "235394546" ["wlanuserfirsturl"]=> string(20) "www%2Ebaidu%2Ecom%2F" } 
    }
    
  
    
    public function actionTestpage()
    {

    	return $this->render('testpage');
    }
    
    
    
    //获取wifi的名字，价格
    public  function actionGettess($wifi_id=1, $iso = 'zh_cn' )
    {
    	$sql = " SELECT a.sale_price ,b.wifi_name FROM wifi_item a ,wifi_item_language b WHERE a.wifi_id = b.wifi_id AND a.wifi_id ='$wifi_id' AND b.iso='$iso' ";
		$wifi_item = Yii::$app->db->createCommand($sql)->queryOne();
		echo $wifi_item['sale_price'];
		echo $wifi_item['wifi_name'];
    	var_dump($wifi_item);
    }
    
    
    
    // Server side notification
    public function actionNotify() {
    	$alipay = Yii::app()->alipay;
    	if ($alipay->verifyNotify()) {
    		$order_num = $_POST['out_trade_no'];
    		if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
    			$this->updateOrderStatus($order_num);
    			echo "success";
    		}
    		else {
    			echo "success";
    		}
    	} else {
    		//echo "fail";
    		throw new CHttpException(404,'交易失败！');
    		exit();
    	}
    }
    
    
    
	//Redirect notification
	public function actionReturn() {
 		$alipay = Yii::app()->alipay;
		if ($alipay->verifyReturn()) {
			$order_num = $_GET['out_trade_no'];
			$order_info = $this->getOrderType($order_num);
			if(empty($order_info['member_id']) && '2' == $order_info['order_type']){
    			$this->redirect(Yii::app()->createUrl('agent/OrderCenter'));
    		}else{
    			$this->redirect(array('memberCenter/memberCenterOrder','order_state'=>1));
    		}
    	} else {
    		throw new CHttpException(404,'交易失败！');
    		exit();
    	}
 	}
 
    //---   XML request Demo-----  end --
    	
}
    
  
