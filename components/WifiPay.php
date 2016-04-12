<?php
namespace app\components;
use Yii;
use app\components\Wifi;
class WifiPay 
{
	//请求DTSPostCharge
	public static function DTSPostCharge($passport,$TenderType,$checkNumber,$price,$identififer)
	{
		$url = Wifi::selectUrl('ibs_request_url');
// 		$url = "http://localhost/wifiservice/web/wifi/test";
		//1.生成xml
		$time = date('Y-m-d H:i:s',time());
		$request = "<?xml version='1.0' encoding='utf-8' ?><DTSPostCharge><Header Action='PMS' CreationDateTime='$time' SourceApplication='WIFI' MessageIdentifier='$identififer' /><Body><PostCharge  OriginatingSystemID='WIFI' Department='WIFI' CheckNumber='$checkNumber'  PassportNo='$passport'  TenderType='$TenderType'  Gratuity=''  SalesAmount='$price' TaxAmount=''  TotalSales='$price' /></Body></DTSPostCharge>";
		
		//2.记录 Postcharge XML 内容 到 ibsxml_log	
		$type_request = 1;     //'类型，0:接收 1:发送',
		Wifi::writeXMLToDB($request,$type_request,$time,$identififer);
		
		//3.调用支付接口，发送 Postcharge XML内容
		$chargeResponse =  Wifi::httpsRequest($url, $request);
		
		//4.记录 PostchargeResponse 返回的XML内容
		$type_response = 0;     //'类型，0:接收 1:发送',
		Wifi::writeXMLToDB($chargeResponse,$type_response,$time,$identififer);
		
		return $chargeResponse;
	}

	
	//生成订单号checknum
	public static function createChecknum()
	{
		$my_code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		 
		$order_sn = $my_code[intval(date('m'))].(intval(date('d')) < 10 ? intval(date('d')) : $my_code[(intval(date('d'))-10)]).date('Y')
		.substr(time(),-5).substr(microtime(),2,5)
		.sprintf('%02d', rand(0, 99));
		 
		return $order_sn;
	}
	
	
	//查找数据库，是否使用ibs系统
	public static function isIBSPay()
	{
		$sql = "SELECT type FROM ibs_pay WHERE id = 1";
		$type = Yii::$app->db->createCommand($sql)->queryOne()['type'];
		return $type;
	}
	
	
	
	

	
	
	
}