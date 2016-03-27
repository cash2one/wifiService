$(document).ready(function(){
	tab();
});

function tab() {
	$(".tab_content > div").css("height", ($(window).height() - $(".tab_title").height()) + "px");
	$("body").on("click",".tab_title li",function(){
		var index = $(".tab_title li").index($(this));
		var left = index * 100;
		$(".tab_content").css("left",(-left + "vw"));
		$("li.active").removeClass("active");
		$(this).addClass("active");
	});
}