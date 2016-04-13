<?php
namespace app\components;
use Yii;
use yii\bootstrap\Alert;
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
	public static  function getPower(){
		$db= \Yii::$app->db;
		$admin_id=isset(\Yii::$app->session['admin_id'])?\Yii::$app->session['admin_id']:'';
		$admin_info = (new \yii\db\Query())
		->from('admin_role')
		->where(['admin_id'=>"$admin_id"])
		->one();
		$menu_id=$admin_info['permission_menu'];
		$ids=explode(',',$menu_id);
		\Yii::$app->session['ids']=$ids;
		
		
	}
	public static  function GetRole($permissionName){
		$weburl=Yii::$app->params['weburl'];
		$ids=\Yii::$app->session['ids'];
		$str = "";
		foreach ($ids as $k=>$v){
			$str .=	$v.",";
		}
		
		$str = rtrim($str,',');
	
	
	   $role="select * from role_info where role_id in ($str) and role_name='$permissionName'";
	   $data = \Yii::$app->db->createCommand($role)->queryAll();
	   if (empty($data)){
	  return "false";
	   }
	 
	}
}