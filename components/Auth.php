<?php
namespace app\components;
use Yii;
// use app\models\WifiItem;
// use app\models\WifiItemLanguage;

class Auth
{
	public static  function getAuth(){
	
		$admin_id=isset(\Yii::$app->session['admin_id'])?\Yii::$app->session['admin_id']:'';
		
		if($admin_id==''){
			return "false";
		}
		else{
			return "true";
		}
	}	
}