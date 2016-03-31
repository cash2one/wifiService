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
					<p>尊敬的旅客( <?php echo Yii::$app->request->get('name',''); ?> )，您好！</p>
					<p>欢迎选购辉煌号邮轮Wifi套餐。</p>
					<ul id="ul_wifi_item">
					<?php foreach($wifi_items as $wifi_item){ ?>
						<li><label><input type="radio" name="wifi_item" value="<?php echo $wifi_item['wifi_id']?>"  <?php if($wifi_item['wifi_id'] == '1'){?>  checked="checked" <?php }?>></input><?php echo $wifi_item['wifi_name']?></label></li>
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
					<ul>
						<li><label>
							<input type="radio" name="wifi_connect" value="2"></input>Wifi套餐一：50元100M
							<ul>
								<li>账号：XXXXXXXX</li>
								<li>密码：XXXXXXXX</li>
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

<script type="text/javascript">
jQuery(function($){
	var wifi_id ;     	//套餐的id
	var wifi_name ;		//套餐的名字
	var wifi_price ;	//套餐的价格

	
	
	//--点击buy按钮--购买选择菜单------
	$("body").on("click","#buy",function(){
		$("#ul_wifi_item input").each(function(){
			if($(this).prop("checked")) {
				wifi_id = $(this).val();
			}
		});
		GetNameAndShowConfirm(wifi_id);
	});
	

	//------ 得到wifi套餐信息，并显示确认支付页面---
	function GetNameAndShowConfirm(wifi_id)
	{
		$.ajax({
            url: "getwifi",
            data: 'wifi_id='+wifi_id,
            type: 'POST',
            dataType: 'json',
            success : function(response) {
                if(response.status == "OK"){
                    wifi_name = response.data['wifi_name'];
                    wifi_price = response.data['sale_price'];
                    PayConfirm(wifi_name,wifi_price);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log("error");
            }
        });
	}
	

	//------ 显示确认支付页面-------
	function PayConfirm(wifi_name,wifi_price)
	{
		$(".payment").replaceWith(
  				"<div class='content payment'>"+
  				"<h3>Wifi订单确认</h3>"+
  				"<ul>"+
  					"<li>商品名称："+wifi_name+"</li>"+
  					"<li>订单金额：$"+wifi_price+"</li>"+
  				"</ul>"+
  				"<p>购买前请确保您的房卡中余额充足，支付成功后，系统将自动从您的房卡中扣除对应的余额。</p>"+
  			"</div>");

  		$(".btn").replaceWith(
  				"<div class='btn'>"+
  				"<input id='payment'  type='button' value='立即支付'></input>"+
  			"</div>"
  		);
	}

	

	//------ 获取get请求的参数------
	function getQueryString(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
		var r = window.location.search.substr(1).match(reg);
		if (r != null) return unescape(r[2]); return null;
	}

	
	
	//点击payment按钮----立即支付-----
	$("body").on("click","#payment",function(){
		$.ajax({
			url:"payment",
			data:"wifi_id="+wifi_id+"&passport="+getQueryString("passport")+"&TenderType="+getQueryString("TenderType"),
			type:'POST',
			dataType:'json',
			success:function(response){
				if(response.status == "OK"){
					//跳转到上网连接
					$(".tab_content").css("left",(-$(window).width() + "px"));
					$(".tab_title li.active").removeClass("active");
					$(".tab_title li:nth-of-type(2)").addClass("active");


				}else if(response.status == "FAIL"){
					
				}
			},
			error:function(XMLHttpRequest,textStatus,errorThrown){
				console.log("error");
			}

		});
	});


});
</script>