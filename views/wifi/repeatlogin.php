<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
	<title>当前套餐</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
	<?=Html::cssFile('@web/css/public.css')?>
	<?=Html::cssFile('@web/css/pages.css')?>
</head>
<body id="repeatLogin">
	<!-- header start -->
	<header id="mainHeader">
		<ul>
			<li><a href="<?php echo Url::toRoute(['showpaymentpage']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>">上网购买</a></li>
			<li class="active"><a href="<?php echo Url::toRoute(['checkloginstatus']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>">上网连接</a></li>
		</ul>
	</header>
	<!-- header end -->
	<!-- content start -->
	<div class="tabContent">
		<div class="iconBox">
			<h2>重复登录</h2>
			<p>已有其它账号连接，</p>
			<p>请先断开后再登录新账号连接。</p>
		</div>
		<div class="btnBox">
			<input type="button" id="logout_button" value="立即断开"></input>
			<input type="button" id="back_button" value="返回"></input>
		</div>
	</div>
	<!-- content end -->
</body>
</html>

<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>

<script type="text/javascript">
window.onload = function(){
	$("#back_button").on("click",function(){
		location.href ="<?php echo Url::toRoute(['index']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";
	});

	$("#logout_button").on("click",function(){
		
		$.ajax({
			url: "<?php echo Url::toRoute(['logoutwificonnect']);?>",
	        data: '',
	        type: 'post',
	        dataType: 'json',
	        timeout:20000,
	        success : function(response) {
	            if(response.status == "OK"){
	            	//显示购买页面
	            	location.href ="<?php echo Url::toRoute(['index']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";
	            }else{
	            	//显示出错页面
	            	location.href ="<?php echo Url::toRoute(['logoutfail']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";
	            }
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log("error");
	        }
		});
	});
}
</script>