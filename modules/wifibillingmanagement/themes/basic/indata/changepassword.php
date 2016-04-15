<?php
$this->title = 'Wifi Billing Management';


use app\modules\wifibillingmanagement\themes\basic\myasset\ThemeAsset;

ThemeAsset::register($this);
$baseUrl = $this->assetBundles[ThemeAsset::className()]->baseUrl . '/';

//$assets = '@app/modules/membermanagement/themes/basic/static';
//$baseUrl = Yii::$app->assetManager->publish($assets);

?>
<?php 
	use yii\helpers\Html;

?>

<!DOCTYPE html>
<html>
<head>
	<title>上网</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl?>css/public.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl?>css/edit.css">
</head>
<body>
	<!-- header start -->
	
	<!-- header end -->
	<!-- main start -->
	<main id="main" style="margin-left:1%">
		<!-- asideNav start -->
		<aside id="asideNav" class="l"></aside>
		<!-- asideNav end -->
		<!-- content start -->
		<div class="search" style="width: 400px;margin-left:auto;margin-right:auto;">
				<form method="post">
				<label>
					<span >Old Password :</span>
					<input type="password" style="width: 200px;height:20px" name="oldpassword" id="oldpassword" ></input>
				</label>
				<br/><br/><br/>
				<label>
					<span>New Password:</span>
				
					<input type="password" style="width: 200px;height:20px" name="newpassword" id="newpassword"  ></input>
					
				</label>
				<br/><br/><br/>
					<label>
					<span> Re-Enter Password:</span>
					
					<input type="password" style="width: 200px;height:20px" name="repassword" id="repassword"></input>
					
				</label>
					
				<br/><br/><br/>
				<span class="btn"><input type="submit" id="mysubmit" value="submit"></input></span>
		<!-- content end -->
		</form>
		</div>
	</main>
	<!-- main end -->
	<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $baseUrl?>js/public.js"></script>
	<script type="text/javascript">
$(function(){ 
	$("#mysubmit").click(function(){
		 var oldpassword = $("#oldpassword").val();
		 var newpassword = $("#newpassword").val();
		 var repassword = $("#repassword").val();
    	 if(oldpassword==''){
            alert("Old password can't empty");
            return false;
         }
    	 else if(newpassword==''){
    		 alert("New password can't empty");
    		 return false;
        	 }
    	
    	
         if(newpassword!=repassword){
        alert(" New Password is not equal Re-Enter Password:");
        return false;
             }
    });

    <?php $massage=isset($massage)?$massage:'';?>
    var massage=<?php echo $massage?>;
    if(massage==1){
        alert("Option success");
        }
    else if(massage==2){
        alert("Option fail");
        }
    else if(massage==3){
       alert('Old Password is not right')
        }
});
	</script>
</body>
</html>