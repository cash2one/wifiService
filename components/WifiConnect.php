<?php
namespace app\components;
use app\components\Wifi; 
use Yii;

class WifiConnect
{
	//查询剩余流量
	public static function getWifiFlow($wifi_code)
	{
		$flow_url = Wifi::selectUrl('flow_url');
		$url = $flow_url."?user_name=".$wifi_code;
		$json = Wifi::httpsRequest($url);
		$response = json_decode($json);
		return $response;
	}
	

	
	//portal认证，登录
	public static function PortalLogin($username,$userpasswd)
	{
		//获取user的ip地址
		$wlanuserip = $_SERVER["REMOTE_ADDR"];
		//参数在数据库中查找
		$portal_url = Wifi::selectUrl('portal_url');
		$portal_params = Wifi::getParamsOfPortal();
		
		//拼接字符串
		$str = '';
		$portal_params = Wifi::getParamsOfPortal();
		foreach ($portal_params as $params ){
			$str .= "&".$params['params_key']."=".$params['params_value'];
		}
		$url = $portal_url."?username=".$username."&userpasswd=".$userpasswd."&version=2.0&action=login"."&wlanuserip=".$wlanuserip.$str;	//url + params
		if(isset(json_decode(trim(Wifi::httpsRequest($url),"()"))->errorCode)){
			$errorCode = json_decode(trim(Wifi::httpsRequest($url),"()"))->errorCode;
		}else {
			$errorCode = '404';
		}
		return $errorCode;
	}
	

	
	//portal认证，注销
	public static function PortaLogout()
	{
		//从数据库中查找出 $wlanuserip，$wlanacip
		$wlanuserip = $_SERVER["REMOTE_ADDR"];
		$wlanacip = self::GetWlanParams('wlanacip');
		
		//发送请求，带上参数
		$portal_url = Wifi::selectUrl('portal_url');
		$url = $portal_url."?version=2.0&action=logout&wlanuserip=".$wlanuserip."&wlanacip=".$wlanacip;
		return $url;
		if(isset(json_decode(trim(Wifi::httpsRequest($url),"()"))->errorCode)){
			$errorCode = json_decode(trim(Wifi::httpsRequest($url),"()"))->errorCode;
		}else {
			$errorCode = '404';
		}
		return $errorCode;
	}
	
	//$wlanacip 从数据库中拿到
	private static function GetWlanParams($params_key)
	{
		//根据wifi_code从数据库中查找对应的 $wlanuserip，$wlanacip 
		$sql = "SELECT params_value FROM wifi_wlan_params WHERE params_key='$params_key'";
		$wlan_params = Yii::$app->db->createCommand($sql)->queryOne()['params_value'];
		return $wlan_params;
	}
	
	
}