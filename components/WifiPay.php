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
	
	
	
}