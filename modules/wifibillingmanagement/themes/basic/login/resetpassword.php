<?php 
	use yii\helpers\Html;
	use app\modules\wifibillingmanagement\themes\basic\myasset\LoginAsset;
	LoginAsset::register($this);
	$baseUrl = $this->assetBundles[LoginAsset::className()]->baseUrl.'/';
	//$assets = '@app/modules/membermanagement/themes/basic/static';
	//$baseUrl = Yii::$app->assetManager->publish($assets);
?>
<?php 
	use Yii as myyii;
	$weburl=Yii::$app->params['weburl'];
?>
<div style="width:250px;margin-left:auto;margin-right:auto"><h3>Welcone to Reset Password</h3></div>
<form method="post" >
<center>
Admin Name:<input type="text" style="height:28px;width:250px" name="admin_name"><br/><br/>
Admin Password:<input type="password" style="height:28px;width:250px" name="admin_password"><br/><br/>
<input type="submit" value="submit" style="width:80px;height:28px;"><a href="<?php echo $weburl?>/login/login"><input type="button" value="back" style="width:80px;height:28px;margin-left:30px"></a>
</center>
</form>
 <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo $baseUrl?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
        </script>
       <script type="text/javascript"> 
$(function(){
	<?php
		    $massage=isset($massage)?$massage:'';
		    if ($massage==''){
		    	
		    }elseif ($massage=='super'){?>//操作信息弹出框
				alert('Input  Admin Name is Super Admin,can not reset password');
				window.location = "<?php echo $weburl?>/login/resetpassword";
			<?php }else if($massage=='passwordfail'){
			?>
			alert('Input  Admin Password is Error');
			window.location = "<?php echo $weburl?>/login/resetpassword";
		
		<?php }else if($massage=='adminname'){
			?>
			alert('Input  Admin Name is not Exist');
			window.location = "<?php echo $weburl?>/login/resetpassword";
		
		<?php }else if($massage=='fail'){
			?>
			alert('Option Fail');
			window.location = "<?php echo $weburl?>/login/resetpassword";
			<?php
		}	
		?>
})
       </script>