<?php
namespace app\components;
use app\components\Wifi; 
use Yii;

class WifiConnect
{
	//查询剩余流量
	public static function getWifiFlow($wifi_code)
	{
// 		$url = Yii::$app->params['flow_url'].$wifi_code;
		$flow_url = Wifi::selectUrl('flow_url');
		$url = $flow_url."?user_name=".$wifi_code;
		$json = Wifi::httpsRequest($url);
		$response = json_decode($json);
		return $response;
	}
	
	
	
	//portal认证，登录
	public static function PortalLogin($username,$userpasswd)
	{
		//请求一次百度，如果有返回字段参数，获取字段参数，再把参数带到url 请求认证，返回字段参数，以便注销时使用
// 		$request_url = Yii::$app->params['request_url'];	//第一次请求的地址
// 		$portal_url = Yii::$app->params['portal_url'];		//portal认证的地址
		
		$request_url = Wifi::selectUrl('request_url');
		$portal_url = Wifi::selectUrl('portal_url');
		
		$response = array();
		$content = self::RequireBeforeLogin($request_url);
		if($content !== false){
			$response_url = trim(str_replace('\r\n',null,str_replace("Location:",null, $content)));
			$url = $portal_url."?username=".$username."&userpasswd=".$userpasswd."&".$response_url;
			
			//解析 url 里面的参数
			$response = self::ParseURL($response_url);
// 			$response['errorCode'] = '1234';

			$errorCode = json_decode(Wifi::httpsRequest($url))->errorCode;
			$response['errorCode'] = $errorCode;
			
			return $response;
		}
	}
	
	//认证之前请求一次百度，获取相关信息
	private static function RequireBeforeLogin($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		$response = curl_exec($ch);
		
		// Then, after your curl_exec call:
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$matches = array();
		preg_match('/Location:(.*?)\n/',$header,$matches);
		if($matches){
			return $matches[0];
		}else 
			return false;
	}
	
	
	//解析url的参数
	private static function ParseURL($url)
	{
		
		$arr = parse_url($url);
		$arr_query = self::convertUrlQuery($arr['query']);
		return $arr_query;
	}
		
	//把url的参数放进数组中
	private static function	convertUrlQuery($query)
	{
		$queryParts = explode('&', $query);
		 
		$params = array();
		foreach ($queryParts as $param){
			$item = explode('=', $param);
			$params[$item[0]] = $item[1];
		}
		 
		return $params;
	}
	

	//portal认证，注销
	public static function PortaLogout($wifi_code)
	{
		//从数据库中查找出 $wlanuserip，$wlanacip
		$response = self::GetWlanParams($wifi_code);
		$wlanuserip = $response['wlanuserip'];
		$wlanacip = $response['wlanacip'];
		//发送请求，带上参数
// 		$portal_url = Yii::$app->params['portal_url'];
		$portal_url = Wifi::selectUrl('portal_url');
		$url = $portal_url."?versions=2.0&action=logout&wlanuserip=".$wlanuserip."&wlanacip=".$wlanacip;
		$errorCode = json_decode(Wifi::httpsRequest($url))->errorCode;
		return $errorCode;
	}
	
	
	//$wlanuserip，$wlanacip 存储到数据库中  
	public static function SaveWlanParams($wifi_code,$wlanuserip,$wlanacip)
	{
		//1.先查找数据库
		$sql = "SELECT * FROM wifi_wlan_params WHERE wifi_code='$wifi_code'";
		$wlan_params = Yii::$app->db->createCommand($sql)->queryOne();
		if(!$wlan_params){
			//insert
			$sql_insert = "INSERT INTO wifi_wlan_params (`wifi_code`,`wlanuserip`,`wlanacip`) VALUES ('$wifi_code','$wlanuserip','$wlanacip')";
			Yii::$app->db->createCommand($sql_insert)->execute();
		}else{
			//update
			$sql_update = "UPDATE wifi_wlan_params SET wlanuserip='$wlanuserip' , wlanacip='$wlanacip' WHERE wifi_code='$wifi_code'";
			Yii::$app->db->createCommand($sql_update)->execute();
		}
	}
	
	//$wlanuserip，$wlanacip 从数据库中拿到
	private static function GetWlanParams($wifi_code)
	{
		//根据wifi_code从数据库中查找对应的 $wlanuserip，$wlanacip 
		$sql = "SELECT wlanuserip ,wlanacip FROM wifi_wlan_params WHERE wifi_code='$wifi_code'";
		$wlan_params = Yii::$app->db->createCommand($sql)->queryOne();
		return $wlan_params;
	}
	
	
}