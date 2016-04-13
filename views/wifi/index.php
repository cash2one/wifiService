<?php 
	use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
	<title>上网</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
	<?=Html::cssFile('@web/css/index.css')?>
</head>
<body>
	<div id="InternetAccess_box">
		<ul class="tab_title">
			<li class="active">上网购买</li>
			<li>上网连接</li>
		</ul>
		<div class="tab_content">
			<div>
				<div class="content payment">
					<p>尊敬的旅客( <?php echo Yii::$app->request->get('Name',''); ?> )，您好！</p>
					<p>欢迎选购辉煌号邮轮Wifi套餐。</p>
					<ul id="ul_wifi_item">
					<?php foreach($wifi_items as $key => $wifi_item){ ?>
						<li><label><input type="radio" name="wifi_item" value="<?php echo $wifi_item['wifi_id']?>"  <?php if($key == 0){?>  checked="checked" <?php }?>></input><?php echo $wifi_item['wifi_name']."&nbsp&nbsp&nbsp".$wifi_item['wifi_flow']."M&nbsp&nbsp&nbsp$".$wifi_item['sale_price']?></label></li>
					<?php }?>
					</ul>
				</div>
				<div class="btn">
					<input id="buy" type="button" value="购买选择的套餐"></input>
				</div>
			</div>
			<div>
				<div class="content connect">
					<h3>当前有效套餐：</h3>
					<ul id="ul_wifi_connect">
						<li><label>
							<input type="radio" name="wifi_connect" value="1"></input>
							<ul>
								<li></li>
								<li></li>
							</ul>
						</label></li>
					</ul>
				</div>
				<div class="btn">
					<input id="connect"  type="button" value="立即联网"></input>
				</div>
			</div>
		</div>
	</div>
	<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>
	<?=Html::jsFile('@web/js/index.js')?>
</body>
</html>
