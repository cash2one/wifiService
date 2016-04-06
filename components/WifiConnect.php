<?php
namespace app\components;
use app\components\Wifi; 
use Yii;

class WifiConnect
{
	//portal认证
	//http://10.10.5.100/cgi-bin/index.cgi?username=xx&userpasswd=xx&...
	//username		-	用户名
	//userpasswd	-	用户密码
	//...		   	-	重定向后URL的参数（首先发起http请求获得重定向后的URL的参数）
	
	//查询剩余流量
	public static function getWifiFlow($wifi_code)
	{
		$url = Yii::$app->params['flow_url'];
		$data = "{'user_name':'$wifi_code'}";
		$json = Wifi::httpsRequest($url, $data);
		$response = json_decode($json);
		return $response;
	}
	
	
	
	
}