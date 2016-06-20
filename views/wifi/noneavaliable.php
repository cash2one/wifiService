<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
	<title>无可用套餐</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
	<?=Html::cssFile('@web/css/public.css')?>
	<?=Html::cssFile('@web/css/pages.css')?>
</head>
<body id="noneAvailable">
	<!-- header start -->
	<header id="mainHeader">
		<ul class="tabTitle">
			<li><a href="<?php echo Url::toRoute(['showpaymentpage']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>">上网购买</a></li>
			<li class="active"><a href="<?php echo Url::toRoute(['checkloginstatus']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>">上网连接</a></li>
		</ul>
	</header>
	<!-- header end -->
	<!-- content start -->
	<div class="tabContent">
		<div class="iconBox">
			<span class="icon">无</span>
			<p>暂无可用套餐，请购买上网套餐。</p>
		</div>
		<div class="btnBox">
			<input type="button" id="button" value="再次购买"></input>
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