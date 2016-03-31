$(document).ready(function(){
	tab();
});

function tab() {
	changeWidthAndHeight();

	$(window).resize(function(){
		changeWidthAndHeight();
	});

	$("body").on("click",".tab_title li",function(){
		var index = $(".tab_title li").index($(this));
		var left = index * $(window).width();
		$(".tab_content").css("left",(-left + "px"));
		$("li.active").removeClass("active");
		$(this).addClass("active");
	});

	function changeWidthAndHeight() {
		$("#InternetAccess_box").css("height",$(window).height()+"px");
		$("#InternetAccess_box .tab_content").css("width",$(window).width()*2 + "px");
		$("#InternetAccess_box .tab_content > div").css("width",$(window).width() + "px");
		$(".tab_content > div").css("height", ($(window).height() - $(".tab_title").height()) + "px");
	}
}