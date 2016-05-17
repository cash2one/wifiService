<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
	<title>确认订单</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
	<?=Html::cssFile('@web/css/public.css')?>
	<?=Html::cssFile('@web/css/pages.css')?>
</head>
<body id="confirmOrder">
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
		<h2 class="title pBox">Wifi订单确认</h2>
		<div id="orderInfo" class="pBox">
			<p>商品名称：<?php echo $WifiItem['wifi_name'];?></p>
			<p>订单金额：<em class="em">$<?php echo $WifiItem['sale_price'];?></em></p>
		</div>
		<div class="point pBox">
			<p>1.购买前请确认您的房卡中余额充足。</p>
			<p>2.支付成功后，系统将自动从您的房卡中扣除对应的余额。</p>
		</div>
		<div class="btnBox">
			<input type="button" id="button" value="立即支付"></input>
		</div>
	</div>
	<!-- content end -->
</body>
</html>

<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>

<script type="text/javascript">
window.onload = function(){
	
	$("#button").on("click",function(){
		
		var str = "<div class='iconBox'><h2>请稍等</h2>";
		str+= '<p>正在生成订单中，请耐心等待！</p>';
		str+='</div>';
		$(".tabContent").html(str);
		
		
		$.ajax({
			url:"<?php echo Url::toRoute(['payment']);?>",
			data:"wifi_id=<?php echo $WifiItemId?>&PassportNO=<?php echo $PassportNO?>&Name=<?php echo $Name?>&TenderType=<?php echo $TenderType?>&iso=<?php echo $iso?>",
			type:'post',
			dataType:'json', 
			success:function(response){
				if(response.status == "OK"){
					//显示支付成功页面
					location.href ="<?php echo Url::toRoute(['paymentsuccessful']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";					
				}else if(response.status == "FAIL"){
					//显示支付失败界面
					location.href ="<?php echo Url::toRoute(['paymentfail']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";	
				}else if(response.status == "ERROR"){
					//显示支付出错界面
					location.href ="<?php echo Url::toRoute(['paymenterror']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";		
				}else if(response.status == "NoCard"){
					location.href ="<?php echo Url::toRoute(['soldout']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>";
				}
			},
			error:function(XMLHttpRequest,textStatus,errorThrown){
				console.log("error");
			}
		});
	});
	
}
</script>