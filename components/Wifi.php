<?php
namespace app\components;
use Yii;
// use app\models\WifiItem;
// use app\models\WifiItemLanguage;

class Wifi
{
	
	//获取wifi的名字，价格
	public static function getWifiItem($wifi_id, $iso = 'zh_cn' )
	{
		$sql = " SELECT a.sale_price ,b.wifi_name
		FROM wifi_item a ,wifi_item_language b
		WHERE a.wifi_id = b.wifi_id AND a.wifi_id ='$wifi_id' AND iso='$iso' ";
		$wifi_item = Yii::$app->db->createCommand($sql)->queryOne();
		return $wifi_item;
	}
	
	
	//获取所有wifi套餐
	public static function getAllWifiItem($iso='zh_cn')
	{
		$sql = "SELECT a.wifi_id,a.sale_price,a.wifi_flow,b.wifi_name ,a.expiry_day
		FROM wifi_item a ,wifi_item_language b
		WHERE a.wifi_id = b.wifi_id
		AND a.status=0 AND b.iso='$iso' AND a.status=0";
	
		$wifi_item = Yii::$app->db->createCommand($sql)->queryAll();
		return $wifi_item;
	}
	
	//获取wifi的帐号，密码
	public static function getWifiInfo($wifi_info_id)
	{
		$sql = "SELECT wifi_info_id,wifi_code, wifi_password FROM wifi_info WHERE wifi_info_id='$wifi_info_id'";
		$wifi_info = Yii::$app->db->createCommand($sql)->queryOne();
		return $wifi_info;
	}
	

	
	//购买上网卡
	public static function wifiCardBuy($wifi_id,$passport,$pay_log_id)
	{
		//生成一张上网卡
		$wifi_info_id = self::wifiCard($wifi_id);
		
		//把相关信息保存到wifi_item_status
		self::wifiItemSave($passport,$wifi_info_id,$pay_log_id);
		
	}
	
	//生成一张上网卡
	private static function wifiCard($wifi_id)
	{
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
	
	//把相关信息保存到wifi_item_status
	private  static function wifiItemSave($passport,$wifi_info_id,$pay_log_id)
	{
		$sql = " INSERT INTO `wifi_item_status` (passport_num,wifi_info_id,pay_log_id) VALUES('$passport','$wifi_info_id','$pay_log_id')";
		Yii::$app->db->createCommand($sql)->execute();
	}
	
	
	//获取游客购买的上网卡  
	//todo 判断有效期
	public static function getWifiItemStatus($passport)
	{
		//1.查找 wifi_info 的开通时间
		//2.查找 wifi_item 的有效时间
		//3.对比当前时间和开通时间，如果小于有效时决，显示

// 		$sql = " SELECT * FROM wifi_item_status WHERE passport_num = '$passport'  AND status = 0 " ;
// 		$wifi_item_status = Yii::$app->db->createCommand($sql)->queryAll();
// 		$wifi_info_id = wifi_item_status['wifi_info_id'];
// 		$pay_log_id = wifi_item_status['pay_log_id'];
		
// 		$sql = " SELECT wifi_id,time FROM wifi_info WHERE wifi_info_id = '$wifi_info_id'";
// 		$wifi_info = Yii::$app->db->createCommand($sql)->queryOne();
// 		$wifi_id = $wifi_info['wifi_id'];
// 		$time = $wifi_info['time'];
		
// 		$sql = " SELECT expiry_day FROM wifi_item WHERE wifi_id = '$wifi_id'";
		$time = date('Y-m-d H:i:s',time());
		
		$sql = " SELECT a.*,b.wifi_id FROM wifi_item_status a,wifi_info b,wifi_item c 
					WHERE a.wifi_info_id=b.wifi_info_id AND b.wifi_id = c.wifi_id 
					AND a.passport_num='$passport' AND a.status=0 AND '$time' < DATE_ADD(b.time,INTERVAL c.expiry_day day)";
		
		$wifi_item_status = Yii::$app->db->createCommand($sql)->queryAll();
		
		return $wifi_item_status;
	}
	
	
	
	
	//把Xml内容写入数据库中
	public static function writeXMLToDB($data,$type)
	{
		$time = date('Y-m-d H:i:s',time());
		$sql = " INSERT INTO `ibsxml_log` (`type`,`content`,`time`) VALUES('$type','$data','$time')";
		Yii::$app->db->createCommand($sql)->execute();
	}
	
	//记录支付记录到数据库中
	public static function  writePayLogToDB($checknum,$passportNum,$name,$amount)
	{
		$pay_time = date('Y-m-d h:i:s',time());
		$sql = " INSERT INTO `wifi_pay_log` (`check_num`,`passport_num`,`name`,`amount`,`pay_time`)
		VALUES('$checknum','$passportNum','$name','$amount','$pay_time')" ;
		Yii::$app->db->createCommand($sql)->execute();
		
		$pay_log_id = Yii::$app->db->getLastInsertID();
		return $pay_log_id;
		
	}
	
	//解析xml内容
	public static function xmlUnparsed($data)
	{
		$xmlObj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
		$PostCharge = $xmlObj->Body->PostCharge;
		return $PostCharge;
	
// 		$checkNumber = $PostCharge->attributes()->CheckNumber;
	
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

	
	
}