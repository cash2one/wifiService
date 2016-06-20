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
<body id="currentPackage">
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
		<div class="packageInfo">
			<h2>当前连接的套餐</h2>
			<p>账号:  <?php echo $WifiCode;?></p>
			<p>密码:  <?php echo $WifiPassword;?></p>
		</div>
		<div class="packageInfo">
			<p>开通时间: <?php echo $TurnOnTime;?></p>
			<p>流量状态: 剩余<?php echo $LeftFlow;?>M/总流量<?php echo $FlowStart;?>M</p>
		</div>
		<div class="btnBox">
			<input type="button" id="button" value="断开连接"></input>
		</div>
	</div>
	<!-- content end -->
</body>
</html>

<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>
<script type="text/javascript">
window.onload = function(){

	
	$("#button").on("click",function(){
		$.ajax({
			url: "<?php echo Url::toRoute(['logoutwificonnect']);?>",
	        data: '',
	        type: 'post',
	        dataType: 'json',
	        timeout:20000,
	        success : function(response) {
	            if(response.status == "OK"){
	            	//显示购买页面
	            	location.href ="<?php echo Url::toRoute(['getwifipackage']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";
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