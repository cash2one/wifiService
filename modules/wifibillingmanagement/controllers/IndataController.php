<?php

namespace app\modules\wifibillingmanagement\controllers;

use yii\web\Controller;
use yii;
use app\models\WifiItemLanguage;
use app\models\WifiInfo;
use app\components\Helper;

use app\components\Wifi;
use app\components\Auth;
use app\models\seletedata;

require dirname(dirname(__FILE__)).'/extensions/PHPExcel.php';
class IndataController extends Controller
{
	
	public $enableCsrfValidation = false;
	
    public function actionIndex()
    {
    	/*  */
  	 	$auth=Auth::getAuth();
  	 	if ($auth=="false"){
  	 		return $this->redirect("/wifibilling/login/login");
  	 	}//判断有没登陆
  	 	$massage=isset($_GET['massage'])?$_GET['massage']:'';
    	$db= \Yii::$app->db;
    	$sql="select *from wifi_item_language wil join wifi_item wi on wi.wifi_id=wil.wifi_id";
    	$count_sql="select *from wifi_item_language wil join wifi_item wi on wi.wifi_id=wil.wifi_id";
    	/* $wifi_item=\Yii::$app->db->createCommand($sql)->queryAll(); */
    	$selectdata=new seletedata();
		$data=$selectdata->paging($sql, $count_sql);
		$data['massage']=$massage;
    	
   		 return $this->render('index',$data);
    }
    public function actionDeleteall(){
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("/wifibilling/login/login");
    	}//判断有没登陆
    	$ids=isset($_POST['ids'])?$_POST['ids']:'';
    	if ($ids==''){
    	
    		return $this->redirect("index?massage=fail");
    	}
    	else {
    		$ids=implode('\',\'',$ids);
    		$sql1="delete from wifi_item_language where wifi_id in('$ids')";
    		$sql2="delete from wifi_item where wifi_id in('$ids')";
    		$transaction =\Yii::$app->db->beginTransaction();
    		try {
    		$command1 = \Yii::$app->db->createCommand($sql1)->execute();
    		$command2= \Yii::$app->db->createCommand($sql2)->execute();
    		$transaction->commit();
    		
		  	return $this->redirect("index?massage=success");
		  	} catch(Exception $e) {
  		$transaction->rollBack();
  		
  		return $this->redirect("index?massage=fail");
  	}
    	}
    }
  public function actionDelete(){
  	$auth=Auth::getAuth();
  	if ($auth=="false"){
  		return $this->redirect("/wifibilling/login/login");
  	}//判断有没登陆

  	$wifi_id=isset($_POST['wifi_id'])?$_POST['wifi_id']:'';
  	$transaction =\Yii::$app->db->beginTransaction();
  	try {
  	$command =\Yii::$app->db->createCommand("delete from wifi_item_language where wifi_id=$wifi_id")->execute();
  	$transaction->commit();
  	
  	return $this->redirect("index?massage=success");
  	} catch(Exception $e) {
  		$transaction->rollBack();
  		return $this->redirect("index?massage=fail");
  	}
  }
    public function actionEdit(){
    	
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("/wifibilling/login/login");
    	}//判断有没登陆
    	$db=\Yii::$app->db;
        if ($_POST){
        	$wifi_id= $_POST['wifi_id'];
        
        	if ($wifi_id==''){//没有wifi_id就查询
        		$sql="select max(wifi_id) as wifi_id from wifi_item";
        		$wifi_id =$db->createCommand($sql)->queryAll();
        		$wifi_id=$wifi_id[0]['wifi_id']+1;
        		$wifi_name=$_POST['wifi_name'];
        		$sale_price=$_POST['sale_price'];
        		$wifi_flow=$_POST['wifi_flow'];
        		$status=$_POST['status'];
        		
        		
        		$transaction =\Yii::$app->db->beginTransaction();
        		try {
        			$command = $db->createCommand("insert into wifi_item(sale_price,wifi_flow,status) values('$sale_price','$wifi_flow','$status')")->execute();
        			$command = $db->createCommand("insert into wifi_item_language(wifi_id,wifi_name) values($wifi_id,'$wifi_name')")->execute();
        			$transaction->commit();
        			return $this->redirect('index?massage=success');
        		} catch(Exception $e) {
        			$transaction->rollBack();
        			return $this->redirect('index',array('massage'=>'fail'));
        		}
        
        	}
        	else {//有wifi——id就更新
        		$transaction =\Yii::$app->db->beginTransaction();
        		try {
        			$wifi_name=$_POST['wifi_name'];
        			$sale_price=$_POST['sale_price'];
        			$wifi_flow=$_POST['wifi_flow'];
        			$status=$_POST['status'];
        			$command = $db->createCommand("update wifi_item_language set wifi_name='$wifi_name' where wifi_id=$wifi_id")->execute();
        			$tiem = $db->createCommand("update wifi_item set sale_price='$sale_price' , wifi_flow='$wifi_flow' , status='$status'  where wifi_id=$wifi_id")->execute();
        			$transaction->commit();
        			return $this->redirect('index?massage=success');
        		} catch(Exception $e) {
        			$transaction->rollBack();
        			return $this->redirect('index?massage=fail');
        		}	
        	}
        
        }
        else {
        $wifi_id=isset($_GET['wifi_id'])?$_GET['wifi_id']:'';
        if (!empty($wifi_id)){
   		$sql="select * from wifi_item_language wil join wifi_item wi on wi.wifi_id=wil.wifi_id where wil.wifi_id=$wifi_id";
   		$data = $db->createCommand($sql)->queryAll();
   		return $this->render('edit',array('data'=>$data));
        }
        else{
    	return $this->render('edit');
        }
        }
    }
    public function actionIndataupdate(){
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("/wifibilling/login/login");
    	}//判断有没登陆
    	
    	if ($_POST){
    		$info=$_POST;
    	  $t= isset($info['t'])?$info['t']:'';
    		if ($t==1){
    		
			     $transaction =\Yii::$app->db->beginTransaction();
			     try {
			     	$data['iso']="zh_cn";
			     	$data['wifi_name']=$info['isozhong'];
			     	$model = new WifiItemLanguage();
			     	$_model = clone $model;
			     	$_model->setAttributes($data);
			     	$_model->save();
			     	$data['iso']="en";
			     	$data['wifi_name']=$info['isoying'];
			     	$model = new WifiItemLanguage();
			     	$_model = clone $model;
			     	$_model->setAttributes($info);
			     	$_model->save();
			     	// ... 执行其他 SQL 语句 ...
			     	$transaction->commit();
			     } catch(Exception $e) {
			     	$transaction->rollBack();
			     }
			     $this->redirect("index");
			}
    	else {
  \Yii::$app->session['id']=$_POST['wifi_id'];
  $this->redirect("index");
    	}
    	}
    }
    public function actionUpdata(){
    	/*  */
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("/wifibilling/login/login");
    	}//判断有没登陆
    	$massage=isset($_GET['massage'])?$_GET['massage']:'';
    	return $this->render('updata',array('massage'=>$massage));
    }
    public function actionInfodata(){
    	/*  */
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("/wifibilling/login/login");
    	}//判断有没登陆
    	$flag = 0;
    	if ($_POST){
    		\Yii::$app->session['wifi_id']=$_POST['wifi_id'];
    		\Yii::$app->session['expiry_day']=$_POST['expiry_day'];
    	}
    	if(!isset($_FILES['import_input']['tmp_name'])){
    		$this->redirect("/Wifi/index");
    		exit;
    	}
    	 
    	$filePath = $_FILES['import_input']['tmp_name'];
    	if($filePath){
    		$objectPHPExcel = new \PHPExcel();
    		$PHPExcel = \PHPExcel_IOFactory::load($filePath);
    		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表(编号从 0 开始)
    		$highestRow = $sheet->getHighestRow(); // 取得总行数
    		$highestColumn = $sheet->getHighestColumn(); // 取得总列数
    		 
    		$arr = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M', 14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
    		$iswhere=array(0=>'wifi_code',1=>'wifi_password');
    		// 一次读取一列
    		 
    		$data = array();
    		for ($row = 2; $row <= $highestRow; $row++) {
    			$data_child = array();
    			for ($column = 0;; $column++)  {
    				$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
    				if($val === null){
    					break;
    				}
    					
    				$data_child[$iswhere[$column]] = $val;
    			}
    			if($data_child){
    				$data[] = $data_child;
    					
    			}
    			
    		}
    		return $this->render('updata',array('data'=>$data,'wifi_id'=>$_POST['wifi_id']));
    		 
    		 
    	}
    }
    public function actionCurrdata(){
    	$auth=Auth::getAuth();
    	if ($auth=="false"){
    		return $this->redirect("/wifibilling/login/login");
    	}//判断有没登陆
    	$db= \Yii::$app->db;
    	$wifi_items_info = (new \yii\db\Query())
    	->from('wifi_item_language')
    	->where(['iso'=>'zh_cn'])
    	->all();
    		$wifi_id= isset($_POST['wifi_id'])?$_POST['wifi_id']:'';
    		$sql="select *from wifi_info join wifi_item_language on wifi_info.wifi_id=wifi_item_language.wifi_id";
    		$count_sql="select *from wifi_info join wifi_item_language on wifi_info.wifi_id=wifi_item_language.wifi_id";
    		if ($wifi_id!=''){
    			$sql.=" and wifi_info.wifi_id=$wifi_id";
    			$count_sql.=" and wifi_info.wifi_id=$wifi_id";
    		}
    		$selecedata=new seletedata();
    		$data=$selecedata->paging($sql, $count_sql);
    		$data['wifi_items_info']=$wifi_items_info;
    		$data['wifi_id']=$wifi_id;
    		return $this->render('currdata',$data);

    }
public function actionSavedata(){
	/*  */
	$auth=Auth::getAuth();
	if ($auth=="false"){
		return $this->redirect("/wifibilling/login/login");
	}//判断有没登陆
	$data=\Yii::$app->session['mydata'];
	$wifi_id=\Yii::$app->session['wifi_id'];
	$expiry_day=\Yii::$app->session['expiry_day'];
	
	if ($wifi_id==''){
		return $this->redirect('updata?massage=1');
	}
	else {
    	foreach ($data as $k=>$v){
    		$wifi_code=$v['wifi_code'];
    		$wifi_password=$v['wifi_password'];
    		$wifi_id=$wifi_id;
    		$expiry_day=$expiry_day;
    		$transaction =\Yii::$app->db->beginTransaction();
    		try {
	    $command = \Yii::$app->db->createCommand("insert into wifi_info(wifi_code,wifi_password,wifi_id,expiry_day) values('$wifi_code','$wifi_password','$wifi_id','$expiry_day')")->execute();
    	$transaction->commit();
    	} catch(Exception $e) {
    		$transaction->rollBack();
    		return $this->redirect('updata?massage=3');
    		exit;
    	}
    	}
    	\Yii::$app->session['mydata']='';
    	\Yii::$app->session['wifi_id']='';
    	\Yii::$app->session['expiry_day']='';
    	return $this->redirect('updata?massage=2');
	}
    	
}
public function actionPay(){
	
	$auth=Auth::getAuth();
	if ($auth=="false"){
		return $this->redirect("/wifibilling/login/login");
	}//判断有没登陆
	$ibs_pay = (new \yii\db\Query())
	->from('ibs_pay')
	->one();
	
	if ($_POST){
		$type=isset($_POST['type'])?$_POST['type']:'0';
		$transaction =\Yii::$app->db->beginTransaction();
		try {
		$command = \Yii::$app->db->createCommand("UPDATE ibs_pay SET type='$type'")->execute();
		$transaction->commit();
		$ibs_pay = (new \yii\db\Query())
		->from('ibs_pay')
		->one();
		return $this->render("pay",array('massage'=>1,'ibs_pay'=>$ibs_pay));
		} catch(Exception $e) {
			$transaction->rollBack();
			return $this->render("pay",array('massage'=>2,'type'=>$ibs_pay['type']));
		}
	}
	return $this->render("pay",array('ibs_pay'=>$ibs_pay));
}
public function actionReport(){
	$auth=Auth::getAuth();
	if ($auth=="false"){
		return $this->redirect("/wifibilling/login/login");
	}//判断有没登陆
		$db= \Yii::$app->db;
    	$sql="select * from ibsxml_log ";
    	$count_sql="select * from ibsxml_log ";
    	/* $wifi_item=\Yii::$app->db->createCommand($sql)->queryAll(); */
    	$selectdata=new seletedata();
		$data=$selectdata->paging($sql, $count_sql);
   		 return $this->render('report',$data);
	
}

public function actionWifiurl(){//wifi地址
	$auth=Auth::getAuth();
	if ($auth=="false"){
		return $this->redirect("/wifibilling/login/login");
	}//判断有没登陆
	$db= \Yii::$app->db;
	$sql="select * from wifi_url_params";
	$count_sql="select * from wifi_url_params";
	/* $wifi_params =$db->createCommand($sql)->queryAll(); */
	$massage=isset($_GET['massage'])?$_GET['massage']:'';
	$selectdata=new seletedata();
	$data=$selectdata->paging($sql, $count_sql);
	$data['massage']=$massage;
	return $this->render("wifiurl",$data);
}
public function actionEditurl(){//url编辑
	$auth=Auth::getAuth();
	if ($auth=="false"){
		return $this->redirect("/wifibilling/login/login");
	}//判断有没登陆
	$db=\Yii::$app->db;
	if ($_POST){
		$id= $_POST['id'];
		
		if ($id==''){//没有wifi_id就查询
			$info=$_POST;
			$transaction =\Yii::$app->db->beginTransaction();
			try {
			$model = new \app\models\WifiUrlParams();
		  	$_model = clone $model;
		     $_model->setAttributes($info);
		     $_model->save();

				$transaction->commit();
				return $this->redirect('wifiurl?massage=success');
			} catch(Exception $e) {
				$transaction->rollBack();
				return $this->redirect('wifiurl',array('massage'=>'fail'));
			}
	
		}
		else {//有wifi——id就更新
			$transaction =\Yii::$app->db->beginTransaction();
			try {
				$name=$_POST['name'];
				$url=$_POST['url'];
				$remark=$_POST['remark'];
				$command = $db->createCommand("update wifi_url_params set name='$name',remark='$remark',url='$url' where id=$id")->execute();
				$transaction->commit();
				return $this->redirect('wifiurl?massage=success');
			} catch(Exception $e) {
				$transaction->rollBack();
				return $this->redirect('wifiurl?massage=fail');
			}
		}
	
	}
	else {
		$id=isset($_GET['id'])?$_GET['id']:'';//点编辑或者增加时的操作
		if (!empty($id)){
			$sql="select * from wifi_url_params where id=$id";
			$data = $db->createCommand($sql)->queryAll();
			return $this->render('editurl',array('data'=>$data));
		}
		else{
			return $this->render('editurl');
		}
	}
}
public function actionDeleteurl(){
	$auth=Auth::getAuth();
	if ($auth=="false"){
		return $this->redirect("/wifibilling/login/login");
	}//判断有没登陆
	$id=isset($_POST['id'])?$_POST['id']:'';
	$transaction =\Yii::$app->db->beginTransaction();
	try {
		$command =\Yii::$app->db->createCommand("delete from wifi_url_params where id=$id")->execute();
		$transaction->commit();
		 
		return $this->redirect("wifiurl?massage=success");
	} catch(Exception $e) {
		$transaction->rollBack();
		return $this->redirect("wifiurl?massage=fail");
	}
}
public function actionDeleteallurl(){//点击删除全部
	$auth=Auth::getAuth();
	if ($auth=="false"){
		return $this->redirect("/wifibilling/login/login");
	}//判断有没登陆
	$ids=isset($_POST['ids'])?$_POST['ids']:'';
	if ($ids==''){
		return $this->redirect("wifiurl?massage=fail");
	}
	else {
		$ids=implode('\',\'',$ids);
		$sql="delete from wifi_url_params where id in('$ids')";
		$transaction =\Yii::$app->db->beginTransaction();
		try {
			$command2= \Yii::$app->db->createCommand($sql)->execute();
			$transaction->commit();

			return $this->redirect("wifiurl?massage=success");
		} catch(Exception $e) {
			$transaction->rollBack();

			return $this->redirect("wifiurl?massage=fail");
		}
	}
}

}
