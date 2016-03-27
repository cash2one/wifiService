<?php 
	use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
	<title>上网</title>
	<meta charset="utf-8">
	<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1">
	<?=Html::cssFile('@web/css/index.css')?>
</head>
<body>
	<div id="InternetAccess_box">
		<ul class="tab_title">
			<li class="active">上网购买</li>
			<li>上网连接</li>
		</ul>
		<div class="tab_content">
			<div class="contetn_box">
				<div class="content content_buy">
					<p>尊敬的旅客(张三)，您好！</p>
					<p>欢迎选购辉煌号邮轮Wifi套餐。</p>
					<ul>
						<li><input type="radio" name="1" value="1" checked="checked"></input>Wifi套餐一：50元100M</li>
						<li><input type="radio" name="2" value="2" ></input>Wifi套餐一：50元100M</li>
						<li><input type="radio" name="3" value="3"></input>Wifi套餐一：50元100M</li>
						<li><input type="radio" name="4" value="4"></input>Wifi套餐一：50元100M</li>
						<li><input type="radio" name="5" value="5"></input>Wifi套餐一：50元100M</li>
					</ul>
				</div>
				<div class="btn">
					<input type="button" class="buy" value="购买选择的套餐"></input>
				</div>
			</div>
			<div>
				<div class="content">
					<h3>当前有效套餐：</h3>
					<ul>
						<li>
							<input type="radio" name="1" value="2"></input>Wifi套餐一：50元100M
							<ul>
								<li>账号：XXXXXXXX</li>
								<li>密码：XXXXXXXX</li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="btn">
					<input type="button" value="立即联网"></input>
				</div>
			</div>
		</div>
	</div>
	<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>
	<?=Html::jsFile('@web/js/index.js')?>

</body>
</html>


<!-- inline scripts related to this page -->
<script type="text/javascript">
jQuery(function($){
	
	$(".buy").on('click',function(e){
//         e.preventDefault();
        $(".contetn_box").replaceWith("<div class='content_box'><div class='content'>"+
        		"<h3>Wifi订单确认</h3>"+
        		"<ul>"+
        			"<li>商品名称：<?php echo '套餐2';?></li>"+
        			"<li>订单金额：<?php echo '500'?></li>"+
        		"</ul>"+
        		"<p>购买前请确保您的房卡中余额充足，支付成功后，系统将自动从您的房卡中扣除对应的余额。</p>"+
        	"</div>"+
        	"<div class='btn'>"+
        		"<input type='button' value='立即支付'></input>"+
        	"</div></div>");
	})
	

	$("body").on("click",".tab_title li",function(){alert("");})
});
</script>