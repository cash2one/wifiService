$(document).ready(function(){
	// 加载header和asideNav内容
	$("#header").load("header.html");
	$("#asideNav").load("asideNav.html");

	// 动态改变右边部分宽度
	changeMainRWith();
	$(window).resize(function(){
		changeMainRWith();
	});

	// asideNav点击事件
	$("body").on("click","#asideNav li",function(){
		if ($(this).next().prop("tagName") === "UL") {
			if ($(this).hasClass("open")) {
				$(this).parent().find("ul").css("display","none");
				$(this).parent().find("ul").prev("li").removeClass("open");
			} else {
				$(this).next().css("display","block");
				$(this).addClass("open");
			}
		} else {
			$(".active").removeClass("active");
			$(this).addClass("active");
		}
	});

	// 左边导航关闭
	$("body").on("click","#closeAsideNav",function(){
		$("#asideNav_open").css("display","none");
		$("#asideNav_close").css("display","block");
		$("#asideNav").css("width",$("#asideNav_close").width() + "px");
		changeMainRWith();
	});

	// 左边导航打开
	$("body").on("click","#openAsideNav",function(){
		$("#asideNav_close").css("display","none");
		$("#asideNav_open").css("display","block");
		$("#asideNav").css("width",$("#asideNav_open").width() + "px");
		changeMainRWith();
	});

	// tab功能
	$("body").on("click",".tab_title li",function(){
		var index = $(".tab_title li").index($(this));
		$(".tab .active").removeClass("active");
		$(this).addClass("active");
		$($(".tab_content > div")[index]).addClass("active");
	});
});

// 动态改变右边部分宽度
function changeMainRWith() {
	$("#main > .r").css("width",($("#main").width() - 44 - $("#asideNav").width())+"px");
}