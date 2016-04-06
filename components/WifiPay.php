<?php
namespace app\components;
use Yii;
use app\components\Wifi;
class WifiPay 
{
	
	//<DTSPostCharge>
	//	<Header Action="PMS" Comment="Pay by the Minute: CREW Rate  Start:3/22/2016 2:15:31 AM End:3/22/2016 2:28:28 AM IP:172.28.25.129" CreationDateTime="2016-03-22 06:47:40" DocumentDefinition="" MessageIdentifier="7b6e2866-f5e9-48c1-8b27-9568946e3eed" SourceApplication="WIFI"/>
	//	<Body>
	//		<PostCharge CheckNumber="L490282" Department="WIFI" FolioID="0000902524" PassportNO="H123456" Gratuity="" OriginatingSystemID="WIFI" SalesAmount="2.47" TaxAmount="" TenderType="03" TotalSales="2.47" TransactionDate="20160322" TransactionTime="02:47:40"/>
	//	</Body>
	//</DTSPostCharge>
	
	

	//  Response -- Fail
	//<DTSFailResponse>
	//	<Header Action="Nano" CreationDateTime="2016-03-21 23:49:27" DocumentDefinition="DTSFailResponse" MessageIdentifier="13408284.20160321" SourceApplication="DTS"/>
	//	<Body Code="22" ErrorDescription="Unknown Error!">
	//		<OriginalMessage>FolioReviewRequest</OriginalMessage>
	//	</Body>
	//</DTSFailResponse>
	
	
	
	//  Response -- Success
	//<DTSPostChargeResponse>
	//	<Header Action="Lufthansa" CreationDateTime="2016-03-22 02:50:03" DocumentDefinition="DTSPostChargeResponse" MessageIdentifier="13408515.20160322" SourceApplication="DTS"/>
	//	<Body>
	//		<PostCharge CheckNum="L490282" FolioID="0000902524" PassportNO="H123456" PostingDate="2016-03-22" PostingTime="02:50:03"/>
	//	</Body>
	//	</DTSPostChargeResponse>
	

	//请求IBS系统查询余额
	public static function folioBalance($passport)
	{
// 		$url = "http://172.16.2.218:9560";
		$url = Yii::$app->params['ibs_request_url'];
		$xml = "<?xml version='1.0' encoding='utf-8' ?>
				<Body>
				<PassportNO='$passport'>
				</Body>
				";
		$balance  = Wifi::httpsRequest($url, $xml);  //返回值字段为 : PassportNO, BalanceDue
		
		return $balance['BalanceDue'];
	}

	
	
	//请求DTSPostCharge
	public static function DTSPostCharge($passport,$TenderType,$checkNumber,$price)
	{
		//http://172.16.2.218:9560
// 		$url = " http://172.16.2.218:9560";

		$url = Yii::$app->params['ibs_request_url'];
		//1.生成xml
		$request = "<?xml version='1.0' encoding='utf-8' ?>
				<DTSPostCharge>
				<Header Action='PMS'  />
				<Body>
					<PostCharge  OriginatingSystemID='WIFI' Department='WIFI' CheckNumber='$checkNumber'  PassportNo='$passport'  TenderType='$TenderType'  Gratuity=''  SalesAmount='$price' TaxAmount=''  TotalSales='$price' />
				</Body>
				</DTSPostCharge>";
		
		//2.记录 Postcharge XML 内容 到 ibsxml_log	
		$type_request = 1;     //'类型，0:接收 1:发送',
		Wifi::writeXMLToDB($request,$type_request);
		
		
		//3.调用支付接口，发送 Postcharge XML内容
		$chargeResponse = Wifi::httpsRequest($url, $request);
		
		
		//4.记录 PostchargeResponse 返回的XML内容
		$type_response = 0;     //'类型，0:接收 1:发送',
		Wifi::writeXMLToDB($chargeResponse,$type_response);
		
		return $chargeResponse;
	}

	

	
	
	
}