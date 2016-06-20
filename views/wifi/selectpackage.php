<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
	<title>选择套餐</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
	<?=Html::cssFile('@web/css/public.css')?>
	<?=Html::cssFile('@web/css/pages.css')?>
</head>
<body id="selectPackage">
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
		<ul id="packageList">
		<?php foreach($Wifi as $key => $row):?>
			<li>
				<a href="#"><?php echo $row['wifi_name']?></a>
				<ul>
				<?php foreach($row['wifi_info'] as $r):?>
					<li>
						<div>
							<input value="<?php echo $r['wifi_info_id']?>" id="wifi_item_id" name="wifi_item_id" type="radio"></input>
						</div>
						<div>
							<div><span class="account">账号:<?php echo $r['wifi_code']?></span><span>密码:<?php echo $r['wifi_password']?></span></div>
							<div>
								<span>剩余流量:<?php echo $r['flow']?>M</span>
							</div>
						</div>
					</li>
				<?php endforeach;?>
				</ul>
			</li>
		<?php endforeach;?>
		</ul>
		<div class="btnBox">
			<input type="button" id="button" value="立即联网"></input>
		</div>
	</div>
	<!-- content end -->
	<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>
	<?=Html::jsFile('@web/js/selectPackage.js')?>
</body>
</html>

<script type="text/javascript">
window.onload = function(){
	$("#button").on("click",function(){
		var WifiInfoId = $("input[name='wifi_item_id']:checked").val();
		if(typeof(WifiInfoId) == 'undefined'){
			WifiInfoId = '';
		}
		location.href ="<?php echo Url::toRoute(['wificonnect']);?>?Name=<?php echo $Name;?>&PassportNO=<?php echo $PassportNO;?>&TenderType=<?php echo $TenderType?>&WifiInfoId="+WifiInfoId;
	});
}
</script>