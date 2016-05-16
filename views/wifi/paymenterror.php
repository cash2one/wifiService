<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
	<title>订单支付错误</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
	<?=Html::cssFile('@web/css/public.css')?>
	<?=Html::cssFile('@web/css/pages.css')?>
	
</head>
<body>
	<!-- header start -->
	<header id="mainHeader">
		<ul class="tabTitle">
			<li class="active"><a href="<?php echo Url::toRoute(['index']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>">上网购买</a></li>
			<li><a href="<?php echo Url::toRoute(['getwifipackage']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>">上网连接</a></li>
		</ul>
	</header>
	<!-- header end -->
	<!-- content start -->
	<div class="tabContent">
		<div class="iconBox">
			<h2 class="error">订单支付错误</h2>
			<p>很抱歉，您的订单支付出现错误，</p>
			<p>请联系相关人员</p>
		</div>
		<div class="btnBox">
			<input type="button" id="button" value="返回"></input>
		</div>
	</div>
	<!-- content end -->
</body>
</html>

<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>

<script type="text/javascript">
window.onload = function(){
	$("#button").on("click",function(){
		location.href ="<?php echo Url::toRoute(['index']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";
	});
}
</script>