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

	<!-- main start -->
	<main id="main" style="margin-left:1%">
		<!-- asideNav start -->
		<aside id="asideNav" class="l"></aside>
		<!-- asideNav end -->
		<!-- content start -->
		<div class="search" style="width: 400px;margin-left:auto;margin-right:auto;">
		<form method="post">
		<label>
			<span >N&nbsp;a&nbsp;m&nbsp;e&nbsp;&nbsp;:</span>
			<input type="text" name="wifi_name" placeholder="Wifi套餐一：50M"></input>
		</label>
		<br/><br/><br/>
		<label>
			<span>流量(MB):</span>
		
			<input type="text" name="wifi_flow" placeholder="50"></input>
			
		</label>
		<br/><br/><br/>
			<label>
			<span>价&nbsp;&nbsp;&nbsp;格&nbsp;&nbsp;&nbsp;:</span>
			
			<input type="text" name="wifi_flow" placeholder="50"></input>
			
		</label>
			<br/><br/><br/>
		<label>
			<span>状&nbsp;&nbsp;&nbsp;态&nbsp;&nbsp;&nbsp;:</span>
			
			启用:<input type="radio" name="status" value="0">
			不启用:<input type="radio" name="status" value="1">
			
		</label>
			
		<br/><br/><br/>
		<span class="btn"><input type="submit" value="submit"></input></span>
		<!-- content end -->
		</form>
		</div>
	</main>
	<!-- main end -->
	<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $baseUrl?>js/public.js"></script>
</body>
</html>