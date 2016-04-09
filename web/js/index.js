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

//------ 获取get请求的参数------  使用这个中文不乱码-------
function request(paras) {
    var url = location.href;
    var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
    var paraObj = {}
    for (i = 0; j = paraString[i]; i++) {
        paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=") + 1, j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if (typeof (returnValue) == "undefined") {
        return "";
    } else {
		return returnValue;
    }
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
        url: "wifi/getwifi",
        data: 'wifi_id='+wifi_id+"&PassportNO="+getQueryString("PassportNO")+"&iso="+getQueryString("iso"),
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

		$("#buy").replaceWith(
			"<input id='payment' type='button' value='立即支付'></input>"
		);
	}
}


$("body").off("click","#payment");
//点击payment按钮----立即支付-----
$("body").on("click","#payment",function(){
	//切换到支付中界面
	ShowPayingPage();
	$.ajax({
		url:"wifi/payment",
		data:"wifi_id="+wifi_id+"&PassportNO="+getQueryString("PassportNO")+"&Name="+decodeURI(request("Name"))+"&TenderType="+getQueryString("TenderType")+"&iso="+getQueryString("iso"),
//		data:"wifi_id="+wifi_id+"&PassportNO="+getQueryString("PassportNO")+"&Name="+getQueryString("Name")+"&TenderType="+getQueryString("TenderType")+"&iso="+getQueryString("iso"),
		type:'post',
		dataType:'json', 
		success:function(response){
			if(response.status == "OK"){
				if(response.type == "1"){
					//通过ibs连接上网
					//延迟3秒请求数据库
					//setInterval(function(){
					setTimeout(function(){
						var identififer = response.identififer;
						$.ajax({
							url:"wifi/getxmlfromdb",
							data:"wifi_id="+wifi_id+"&PassportNO="+getQueryString("PassportNO")+"&identififer="+identififer+"&Name="+decodeURI(request("Name"))+"&TenderType="+getQueryString("TenderType")+"&iso="+getQueryString("iso"),
							type:'post',
							dataType:'json', 
							success:function(response){
								if(response.status == "OK"){
									//支付成功，跳到连接界面
									$(".tab_content").css("left",(-$(window).width() + "px"));
									$(".tab_title li.active").removeClass("active");
									$(".tab_title li:nth-of-type(2)").addClass("active");
									//显示上网连接界面
									ShowConnectPage();
								}else if( response.status == "Fail" ){
									//支付失败，跳到支付失败界面
									ShowPayFailPage();
								}else {
									//显示支付出错界面
									ShowPayErrorPage();
								}
							},
							error:function(XMLHttpRequest,textStatus,errorThrown){
								console.log("error");
							}
						});
					},3000); //3秒后调用
				}else if(response.type == "0"){
					//不使用ibs连接上网
					//跳转到上网连接
					$(".tab_content").css("left",(-$(window).width() + "px"));
					$(".tab_title li.active").removeClass("active");
					$(".tab_title li:nth-of-type(2)").addClass("active");
					//显示上网连接界面
					ShowConnectPage();
				}
			}else if(response.status == "FAIL"){
				//显示支付失败界面
				ShowPayFailPage();
			}else if(response.status == "ERROR"){
				//显示支付出错界面
				ShowPayErrorPage();
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
        url: "wifi/getwifiitemstatus",
        data: 'PassportNO='+getQueryString("PassportNO"),
        type: 'post',
        dataType: 'json',
        success : function(response) {
            if(response.status == "OK"){
            	if(response.data != ''){
            		//游客购买了上网卡
            		//显示上网连接--立即上网界面
            		ShowConnectSelect(response.data);
            	}else {
            		//游客没有购买上网卡
            		//显示没有购买上网卡界面
            		ShowNoItem();
            	}
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("error");
        }
    });
	
}



//显示等待支付中界面
function ShowPayingPage()
{
	$(".payment").replaceWith(
		"<div class='content payment'>"+
			"<h3>Wifi订单支付中！</h3>"+
			"<p>正在生成订单中，请稍后...</p>"+
		"</div>"
	);
	
	$("#payment").replaceWith(
		//"<input id='return' type='button' value='取消'></input>"
	);
	
	$("body").on("click","#return",function(){
		location.reload();	//重载页面
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
	
	$("#payment").replaceWith(
		"<input id='return' type='button' value='返回'></input>"
	);
	
	$("body").on("click","#return",function(){
		location.reload();	//重载页面
	});
}

//显示支付出错界面
function ShowPayErrorPage()
{
	$(".payment").replaceWith(
		"<div class='content payment'>"+
			"<h3>Wifi订单支付出现错误！</h3>"+
			"<p>很抱歉，支付出现错误，请联系前台咨询！</p>"+
		"</div>"
	);
		
	$("#payment").replaceWith(
		"<input id='return' type='button' value='返回'></input>"
	);
	
	$("body").on("click","#return",function(){
		location.reload();	//重载页面
	});
}



//显示选择上网连接 ---- 立即上网 界面
function ShowConnectSelect(data)
{
	//动态生成当前有效套餐
	var wifi_status = "<div class='content connect'><h3>当前有效套餐：</h3><ul id='ul_wifi_connect'>";
	$.each(data,function(index,item){
		wifi_status += "<li><label>";
		
		if(index == 0){
			wifi_status += "<input type='radio' checked='checked' name='wifi_connect' value="+item.wifi_info_id+"></input>"+item.wifi_name;
		}else{
			wifi_status += "<input type='radio' name='wifi_connect' value="+item.wifi_info_id+"></input>"+item.wifi_name;
		}
			
		wifi_status +=
			"<ul>"+
				"<li>账号："+item.wifi_code+" <input type='hidden' id='wifi_code' value="+item.wifi_code+" /></li>"+
				"<li>密码："+item.wifi_password+"<input type='hidden' id='wifi_password' value="+item.wifi_password+" /></li>"+
			"</ul>"+
			"</label></li>";
	});
	
	wifi_status += "</ul></div>";
	$(".connect").replaceWith(wifi_status);
	
	//动态生成立即联网按钮
	$("#connect").replaceWith(
		"<input id='connect' type='button' value='立即联网'></input>"
	);
	
	//动态生成立即联网按钮
	$("#connect_logout").replaceWith(
		"<input id='connect' type='button' value='立即联网'></input>"
	);
	
	//点击 立即联网 按钮
	ClickWifiConnectBtn(data);
	
}

//点击  --立即联网 ---
function ClickWifiConnectBtn(data)
{
	//点击connect按钮  --立即联网 ---
	$("body").off("click","#connect");
	$("body").on("click","#connect",function(){
		//获取点击的套餐
    	index = SelectWifiItem();
		$.ajax({
		 url: "wifi/wificonnect",
	        data: 'wifi_code='+index[0]+'&wifi_password='+index[1],
	        type: 'post',
	        dataType: 'json',
	        success : function(response) {
	            if(response.status == "OK"){
	            	//显示 停用wifi页面
	            	ShowLogOutWifiConnect(response.data);
	            }
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log("error");
	        }
		});
	});
}

//停用wifi页面
function ShowLogOutWifiConnect(item)
{
	var wifi_status = "<div class='content connect'><h3>当前连接的套餐：</h3>";
	wifi_status +=
			"<p>账号："+item.wifi_code+"</p>"+
			"<p>密码："+item.wifi_password+"</p>"+
			"<p>开通的时间 : "+item.turnOnTime+"</p>"+
			"<p>流量状态 : 已用 "+item.left_flow+"M / 剩余 "+item.flow_start+"M </p>";
	$(".connect").replaceWith(wifi_status);
	
	//动态生成立即联网按钮
	$("#connect").replaceWith(
		"<input id='connect_logout' type='button' value='停用网络'></input>"
	);
	
	ClickLogoutWifiBtn(item);
	
}


// 停用网络按钮  
function ClickLogoutWifiBtn(item)
{
	//点击connect按钮  --立即联网 ---
	$("body").off("click","#connect_logout");
	$("body").on("click","#connect_logout",function(){
		$.ajax({
			url: "wifi/logoutwificonnect",
	        data: 'wifi_code='+item.wifi_code+'&wifi_password='+item.wifi_password,
	        type: 'post',
	        dataType: 'json',
	        success : function(response) {
	            if(response.status == "OK"){
	            	//显示购买页面
	            	ShowConnectPage();
	            }
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log("error");
	        }
		});
	});
}


//获取 上网选择套餐的index
function SelectWifiItem()
{
	var index = [];	//定义一个数组存放 wifi套餐
	$("#ul_wifi_connect>li").each(function(){
		if($(this).find("input").prop("checked")) {
			index[0] = $(this).find("ul li input#wifi_code").val();
			index[1] = $(this).find("ul li input#wifi_password").val();
		}
	});
	return index ;
}



//显示没有购买套餐界面
function ShowNoItem()
{
	$(".connect").replaceWith(
		"<div class='content connect'><h3>当前有效套餐：</h3>"+
		"<p>暂无可用的套餐，请购买上网套餐。</p>"+
		"</div>"
	);
	
	//动态生成立即联网按钮
	$("#connect").replaceWith(
		"<input id='connect_return' type='button' value='返回购买'></input>"
	);
	
	$("body").on("click","#connect_return",function(){
		location.reload();	//重载页面
	});
}

