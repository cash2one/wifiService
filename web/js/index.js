$(document).ready(function(){
	tab();
});


//切换tab
function tab() {
	
	changeWidthAndHeight();

	$(window).resize(function(){
		changeWidthAndHeight();
	});

	//切换tab
	$("body").on("click",".tab_title li",function(){
		var index = $(".tab_title li").index($(this));
		var left = index * $(window).width();
		$(".tab_content").css("left",(-left + "px"));
		$("li.active").removeClass("active");
		$(this).addClass("active");
		if(index == 0) {
			//点击 上网购买 tab，刷新页面
			location.reload();
		}else if(index == 1){
			//点击  上网连接 tab 显示上网连接页面
			ShowConnectPage();
		}
	});
	
	
	function changeWidthAndHeight() {
		$("#InternetAccess_box").css("height",$(window).height()+"px");
		$("#InternetAccess_box .tab_content").css("width",$(window).width()*2 + "px");
		$("#InternetAccess_box .tab_content > div").css("width",$(window).width() + "px");
		$(".tab_content > div").css("height", ($(window).height() - $(".tab_title").height()) + "px");
	}
	
}


//------ 获取get请求的参数------
function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]); return null;
}



var wifi_id ;     	//套餐的id
//--点击buy按钮--购买选择菜单------
$("body").on("click","#buy",function(){
	$("#ul_wifi_item input").each(function(){
		if($(this).prop("checked")) {
			wifi_id = $(this).val();
		}
	});
	GetNameAndShowConfirm(wifi_id);
});



var wifi_name ;		//套餐的名字
var wifi_price ;	//套餐的价格
//------ 得到wifi套餐信息，并显示确认支付页面---
function GetNameAndShowConfirm(wifi_id)
{
	$.ajax({
        url: "getwifi",
        data: 'wifi_id='+wifi_id+"&iso="+getQueryString("iso"),
        type: 'post',
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
			"</div>"
		);

		$(".btn").replaceWith(
				"<div class='btn'>"+
				"<input id='payment'  type='button' value='立即支付'></input>"+
			"</div>"
		);
	}
}



//点击payment按钮----立即支付-----
$("body").on("click","#payment",function(){
	$.ajax({
		url:"payment",
		data:"wifi_id="+wifi_id+"&PassportNO="+getQueryString("PassportNO")+"&Name="+getQueryString("Name")+"&TenderType="+getQueryString("TenderType")+"&iso="+getQueryString("iso"),
		type:'POST',
		dataType:'json',
		success:function(response){
			if(response.status == "OK"){
				//跳转到上网连接
				$(".tab_content").css("left",(-$(window).width() + "px"));
				$(".tab_title li.active").removeClass("active");
				$(".tab_title li:nth-of-type(2)").addClass("active");
				
				//显示上网连接界面
				ShowConnectPage();
			}else if(response.status == "FAIL"){
				//显示支付失败界面
				ShowPayFailPage();
			}
		},
		error:function(XMLHttpRequest,textStatus,errorThrown){
			console.log("error");
		}
	});
});


//显示上网连接界面
function ShowConnectPage()
{
	$.ajax({
        url: "getwifiitemstatus",
        data: 'passport='+getQueryString("PassportNO"),
        type: 'POST',
        dataType: 'json',
        success : function(response) {
            if(response.status == "OK"){
            	//动态生成当前有效套餐
            	var wifi_status = "<div class='content connect'><h3>当前有效套餐：</h3><ul>";
            	$.each(response.data,function(index,item){
            		wifi_status +=
	    				"<li><label>"+
	            		"<input type='radio' name='wifi_connect' value="+item.wifi_info_id+"></input>"+item.wifi_name+
						"<ul>"+
							"<li>账号："+item.wifi_code+"</li>"+
							"<li>密码："+item.wifi_password+"</li>"+
						"</ul>"+
						"</label></li>"
            	});
            	wifi_status += "</ul></div>";
            	$(".connect").replaceWith(wifi_status);
            	
            	//动态生成立即联网按钮
            	$("#connect").replaceWith(
	    			"<div class='btn'>"+
					"<input id='connect' type='button' value='立即联网'></input>"+
					"</div>"
    			);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("error");
        }
    });
}



//显示支付失败界面
function ShowPayFailPage()
{
	$(".payment").replaceWith(
		"<div class='content payment'>"+
			"<h3>Wifi订单支付失败！</h3>"+
			"<p>很抱歉，您的房卡账户余额不足，请先到前台充值后，再购买！</p>"+
		"</div>"
	);
	
	$(".btn").replaceWith(
		"<div class='btn'>"+
			"<input id='return' type='button' value='返回'></input>"+
		"</div>"
	);
	
	$("body").on("click","#return",function(){
		location.reload();	//重载页面
	});
}

