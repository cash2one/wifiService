<?php
namespace app\components;
use Yii;
// use app\models\WifiItem;
// use app\models\WifiItemLanguage;

class Wifi
{
	//通过curl发送http请求，发送内容可以为 xml 或者 json
	public static function https_request($url,$data)
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
	public static function GetWifiItem($iso='zh_cn')
	{
		$sql = " SELECT a.wifi_id,a.sale_price,a.wifi_flow,b.wifi_name ,a.expiry_day 
				FROM wifi_item a ,wifi_item_language b 
				WHERE a.wifi_id = b.wifi_id 
				AND a.status=0 AND b.iso='$iso' AND a.status=0";
		
		$wifi_item = Yii::$app->db->createCommand($sql)->queryAll();
		return $wifi_item;
	}
	

	
	
}