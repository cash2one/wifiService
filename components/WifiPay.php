<?php
namespace app\components;
use Yii;

class WifiPay 
{
	//通过wifi的名字，价格
	public static function GetWifiInfo($wifi_id, $iso = 'zh_cn' )
	{
		$sql = " SELECT a.sale_price ,b.wifi_name
		FROM wifi_item a ,wifi_item_language b
		WHERE a.wifi_id = b.wifi_id AND a.wifi_id =$wifi_id AND iso='$iso' ";
		$wifi_item = Yii::$app->db->createCommand($sql)->queryOne();
		
		$item = '"sale_price" : "'.$wifi_item['sale_price'].'","wifi_name":"'.$wifi_item['wifi_name'].'"}';
		return $item;
	}
	
	
	//<DTSPostCharge><Header Action="PMS" Comment="Pay by the Minute: CREW Rate  Start:3/22/2016 2:15:31 AM End:3/22/2016 2:28:28 AM IP:172.28.25.129" CreationDateTime="2016-03-22 06:47:40" DocumentDefinition="" MessageIdentifier="7b6e2866-f5e9-48c1-8b27-9568946e3eed" SourceApplication="WIFI"/><Body><PostCharge CheckNumber="L490282" Department="WIFI" FolioID="0000902524" PassportNO="H123456" Gratuity="" OriginatingSystemID="WIFI" SalesAmount="2.47" TaxAmount="" TenderType="03" TotalSales="2.47" TransactionDate="20160322" TransactionTime="02:47:40"/></Body></DTSPostCharge>
	//请求DTSPostCharge
	public function DTSPostCharge()
	{
		
		
	}
	
	//<DTSFailResponse><Header Action="Nano" CreationDateTime="2016-03-21 23:49:27" DocumentDefinition="DTSFailResponse" MessageIdentifier="13408284.20160321" SourceApplication="DTS"/><Body Code="22" ErrorDescription="Unknown Error!"><OriginalMessage>FolioReviewRequest</OriginalMessage></Body></DTSFailResponse>
	//<DTSPostChargeResponse><Header Action="Lufthansa" CreationDateTime="2016-03-22 02:50:03" DocumentDefinition="DTSPostChargeResponse" MessageIdentifier="13408515.20160322" SourceApplication="DTS"/><Body><PostCharge CheckNum="L490282" FolioID="0000902524" PassportNO="H123456" PostingDate="2016-03-22" PostingTime="02:50:03"/></Body></DTSPostChargeResponse>
	//获取DTSPostCharge
	public function DTSPostChargeResponse()
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$CheckNum = $postObj->Body->PostCharge->CheckNum;
			
		}
	}
	
	
}