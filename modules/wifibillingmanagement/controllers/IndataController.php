<?php

namespace app\modules\wifibillingmanagement\controllers;

use yii\web\Controller;
use yii;
use app\models\WifiItemLanguage;
use app\models\WifiInfo;
require dirname(dirname(__FILE__)).'/extensions/PHPExcel.php';
class IndataController extends Controller
{
	public $enableCsrfValidation = false;
    public function actionIndex()
    {
    	$db= \Yii::$app->db;
    	$sql="select *from wifi_item_language wil join wifi_item wi on wi.wifi_id=wil.wifi_id";
    	$wifi_item =$db->createCommand($sql)->queryAll();
   		 return $this->render('index',['wifi_item'=>$wifi_item]);
    
    }

    public function actionEdit(){
    	$db=\Yii::$app->db;
        if ($_POST){
        	$wifi_id=isset($_GET['wifi_id'])?$_GET['wifi_id']:'';
        	if ($wifi_id==''){
        		$sql="select max(wifi_id) as wifi_id from wifi_item_language";
        		$wifi_id =$db->createCommand($sql)->queryAll();
        		$wifi_id=$wifi_id[0]['wifi_id']+1;
        		$data['wifi_name']=$_POST['wifi_name'];
        		$data['wifi_id']=$wifi_id;
        		
        		$transaction =\Yii::$app->db->beginTransaction();
        		try {
        		$model = new WifiItemLanguage();
        		$_model = clone $model;
        		$_model->setAttributes($data);
        		$_model->save();
        		} catch(Exception $e) {
        			$transaction->rollBack();
        		}
        
        	}
        	return $this->render('edit');
        }
        else {
    	return $this->render('edit');
        }
    }
    public function actionIndataupdate(){
    	
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
    	return $this->render('updata');
    }
    public function actionInfodata(){
    	$flag = 0;
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
    	
    		
    		 
    		return $this->render('updata',array('data'=>$data));
    		 
    		 
    	}
    }
    public function actionCurrdata(){
    	$db= \Yii::$app->db;
    	$wifi_items = (new \yii\db\Query())
    	->from('wifi_item_language')
    	->where(['iso'=>'zh_cn'])
    	->all();
    	if ($_POST){
    		
    		
    	}
    	else 
    	{
    	$sql="select *from wifi_info ";
    	$wifi_info =$db->createCommand($sql)->queryAll();
    	return $this->render('currdata',['wifi_info'=>$wifi_info,'wifi_items'=>$wifi_items]);
    	}
    }
public function actionSavedata(){
	$data=\Yii::$app->session['mydata'];
    $wifi_id=\Yii::$app->session['id'];
    	foreach ($data as $k=>$v){
    	$v['wifi_id']=$wifi_id;
    	$model = new WifiInfo();
    	$_model = clone $model;
    	$_model->setAttributes($v);
    	$_model->save();
    	}
	return $this->render('updata');
}
public function actionPay(){
	if ($_POST){
		$type=$_POST['type'];
		$command = \Yii::$app->db->createCommand("UPDATE ibs_pay SET type='$type'")->execute();
	}
	return $this->render("pay");
}
public function actionReport(){
	return $this->render("pay");
}
}
