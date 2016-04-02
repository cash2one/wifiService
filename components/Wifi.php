<?php
namespace app\components;
use Yii;
// use app\models\WifiItem;
// use app\models\WifiItemLanguage;

class Wifi
{
	
	//获取wifi的名字，价格
	public static function getWifiInfo($wifi_id, $iso = 'zh_cn' )
	{
		$sql = " SELECT a.sale_price ,b.wifi_name
		FROM wifi_item a ,wifi_item_language b
		WHERE a.wifi_id = b.wifi_id AND a.wifi_id =$wifi_id AND iso='$iso' ";
		$wifi_item = Yii::$app->db->createCommand($sql)->queryOne();
		return $wifi_item;
	}
	
	
	
	//通过curl发送http请求，发送内容可以为 xml 或者 json
	public static function httpsRequest($url,$data)
	{
		$curl = curl_init();								// create curl resource
		curl_setopt($curl, CURLOPT_URL, $url);				// set url
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);		//return the transfer as a string
		$output = curl_exec($curl);							// $output contains the output string
		curl_close($curl);									// close curl resource to free up system resources
		return $output;
	}
	
	
	//获取所有wifi套餐
	public static function getWifiItem($iso='zh_cn')
	{
		$sql = " SELECT a.wifi_id,a.sale_price,a.wifi_flow,b.wifi_name ,a.expiry_day 
				FROM wifi_item a ,wifi_item_language b 
				WHERE a.wifi_id = b.wifi_id 
				AND a.status=0 AND b.iso='$iso' AND a.status=0";
		
		$wifi_item = Yii::$app->db->createCommand($sql)->queryAll();
		return $wifi_item;
	}
	
	
	//购买上网卡
	public static function wifiCardBuy($wifi_id,$passport,$pay_log_id)
	{
		self::wifiCard($wifi_id);
	}
	
	//生成一张上网卡
	private static function wifiCard($wifi_id)
	{
		//通过wifi_id 更改wifi_info表中的数据状态,并获取被更改数据的wifi_info_id
		//todo
		return $wifi_info_id;
	}
	
	
	//把Xml内容写入数据库中
	public static function writeXMLToDB($data,$type)
	{
		//todo
	}
	
	//记录支付记录到数据库中
	public static function  writePayLogToDB($checknum,$passportNum,$name,$amount)
	{
		$pay_time = date('Y-m-d h:i:s',time());
		$sql = " INSERT INTO `wifi_pay_log` (`check_num`,`passport_num`,`name`,`amount`,`pay_time`)
		VALUES('$checknum','$passportNum','$name','$amount','$pay_time')" ;
		Yii::$app->db->createCommand($sql)->execute();
	}
	
	//解析xml内容
	public static function xmlUnparsed($data)
	{
		$xmlObj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
		$PostCharge = $xmlObj->Body->PostCharge;
		return $PostCharge;
	
// 		$checkNumber = $PostCharge->attributes()->CheckNumber;
	
	}
	
	

	
	
}