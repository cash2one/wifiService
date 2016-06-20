<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
	<title>请选择套餐</title>
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
			<p>您没有选择上网的套餐，请选择需要联网的套餐。</p>
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

	var errorCode = <?php echo $errorCode;?>;
	if(errorCode == -1){
		//连接出错,返回错误代码-1
		var str = '<p>连接出错</p>';
		$(".iconBox").html(str);
	}


	$("#button").on("click",function(){
		location.href ="<?php echo Url::toRoute(['getwifipackage']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";
	});
}
</script>