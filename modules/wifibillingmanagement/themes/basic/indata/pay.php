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
	<style>
		.btn { display:inline-block; vertical-align: top; margin-left: 50px; }
		.btn input[type='submit'] { background: #3f7fcf; }
	</style>
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
			<div class="topNav">Wifi Billging&nbsp;&gt;&gt;&nbsp;<a href="#">IBS pay set</a></div>
	<div>
		<form method="post">
				<label class="label_checkbox">
					<span>IBS Pay:</span>
					<label class="btn_checkbox">
						<input type="checkbox" name="type" value="1"></input>
						<span></span>
					</label>
				</label>
				<input type="hidden" name='t' value='1'>
				<span class="btn"><input type="submit" value="submit"></input></span>
			
		</form>	
			</div>

		</div>
	</main>
	<!-- main end -->
		<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
		<script type="text/javascript">
$(function(){
    <?php $massage=isset($massage)?$massage:'';?>
    <?php if ($massage==1){?>
    alert("操作成功");
    <?php }elseif ($massage==2){?>
    alert("操作失败");
    <?php }?>
  
    //操作后弹出框
})
		</script>
</body>

</html>