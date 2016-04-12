<?php

namespace app\modules\wifibillingmanagement\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\MyLoginForm;
use app\models\Admin;

class LoginController extends Controller
{
	/**
	 * Displays the login page
	 */
	
	public function actionLogin()
	{
		$weburl=Yii::$app->params['weburl'];
            $this->layout = false;
          
            if (!empty(\Yii::$app->user->id))
            {
               return $this->redirect(Yii::app()->createUrl('site/index'));
            }

            $reffer_url = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'';
            $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '' ;
           
   
            $model=new MyLoginForm();
            if(isset($_POST['username']) && isset($_POST['password']))
            {
                    $model->username = $_POST['username'];
                  
                    $model->password = $_POST['password'];
                    $model->rememberMe = (empty($_POST['rememberMe']) ? false : true);
                   
                    // validate user input and redirect to the previous page if valid
                  $info=Admin::find()
                            ->where(['admin_name' => $_POST['username'],'admin_password'=>$_POST['password']])
                            ->one();
                 
                    if(!empty($info))
                    {
                       
                    	\Yii::$app->session['admin_id']=$info['admin_id'];
						\Yii::$app->session['admin_name']=$info['admin_name'];
                                   return $this->redirect($weburl);
                         
                    }
                    else
                    {
                      return  $this->render('login',array('model'=>$model,'login_state'=>true));
                        exit;
                    }
            }
            // display the login form
          return  $this->render('login',array('model'=>$model));
	}
	public function actionLoginout(){
		\Yii::$app->session['admin_id']='';
		\Yii::$app->session['admin_name']='';
		return $this->redirect('login');
	}
        
}
