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
				<div class="r content" id="user_content">
			<div class="topNav">IBS Pay&nbsp;&gt;&gt;&nbsp;<a href="#">pay</a></div>
	
	<div class="search" style="margin-left:5%">
		<form method="post">
	<span>IBS Pay:</span>通过IBS支付:<input type="radio" name="type" value="1">
						不通过IBS支付:<input type="radio" name="type" value="0">

			<br>	<br>	<br>
				<span class="btn"><input type="submit" value="提交"></input></span>
			
		</form>	
		</div>	
		</div>
	</main>
	<!-- main end -->
	<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $baseUrl?>js/public.js"></script>
</body>
</html>