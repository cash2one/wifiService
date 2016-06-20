<?php
namespace app\components;
use Yii;
use app\components\Wifi;
class WifiPay 
{
	//请求IBS系统查询余额
	public static function folioBalance($passport,$identififer)
	{
		$url = Wifi::selectUrl('ibs_request_url');
		//1.生成xml
		$time = date('Y-m-d H:i:s',time());
		$request = "<?xml version='1.0' encoding='utf-8' ?><DTSFolioBalance><Header Action='folioBalance' CreationDateTime='$time' SourceApplication='WIFI'  MessageIdentifier='$identififer' /><Body><FolioBalance PassportNO='$passport' /></Body></DTSFolioBalance>";
		//2.记录 DTSFolioBalance XML 内容 到 ibsxml_log 数据表	
		$type_request = 1;     //'类型，0:接收 1:发送',
		Wifi::writeXMLToDB($request,$type_request,$time,$identififer);
		//3.调用查询接口，发送 DTSFolioBalance XML内容
		$folioBalance  = Wifi::httpsRequest($url, $request);  //返回值字段为 : PassportNO, BalanceDue
		//4.记录 DTSFolioBalance 返回的XML内容
		$type_response = 0;     //'类型，0:接收 1:发送',
		Wifi::writeXMLToDB($folioBalance,$type_response,$time,$identififer);
		return $folioBalance;
	}

	
	//请求DTSPostCharge
	public static function DTSPostCharge($passport,$TenderType,$checkNumber,$price,$identififer)
	{
		$url = Wifi::selectUrl('ibs_request_url');
		//1.生成xml
		$time = date('Y-m-d H:i:s',time());
		$request = "<?xml version='1.0' encoding='utf-8' ?><DTSPostCharge><Header Action='DTSPostCharge' CreationDateTime='$time' SourceApplication='WIFI' MessageIdentifier='$identififer' /><Body><PostCharge  OriginatingSystemID='WIFI' Department='WIFI' CheckNumber='$checkNumber'  PassportNo='$passport'  TenderType='$TenderType'  Gratuity=''  SalesAmount='$price' TaxAmount=''  TotalSales='$price' /></Body></DTSPostCharge>";
		//2.记录 DTSPostCharge XML 内容 到 ibsxml_log 数据表	
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
	
	
	//查找数据库，是否需要查询余额
	public static function isCheckBalance()
	{
		$sql = "SELECT type FROM ibs_pay WHERE id = 2";
		$type = Yii::$app->db->createCommand($sql)->queryOne()['type'];
		return $type;
	}
	
	
}