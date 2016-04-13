<?php
$this->title = 'Wifi Billing Management';


use app\modules\wifibillingmanagement\themes\basic\myasset\ThemeAsset;

ThemeAsset::register($this);
$baseUrl = $this->assetBundles[ThemeAsset::className()]->baseUrl . '/';

//$assets = '@app/modules/membermanagement/themes/basic/static';
//$baseUrl = Yii::$app->assetManager->publish($assets);

?>
<?php 
	use yii\helpers\Html;
	use Yii as myyii;
	$weburl=Yii::$app->params['weburl'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>network</title>
	<meta charset="utf-8">
			<?=Html::cssFile('@web/assets/css/bootstrap.css')?>
			<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
			<script src="<?php echo $baseUrl?>js/jqPaginator.js"></script>	
<style type="text/css">
		#promptBox { width: 200px; }
		#promptBox p { text-align: center; }
			/*pop-ups*/
		.pop-ups { border: 1px solid #e0e9f4; box-shadow: 1px 1px 1px #cbcbcb; box-sizing: border-box; overflow: hidden; }
		.pop-ups h3 { padding: 16px; margin: 0; background: #3f7fcf; text-align: center; color: #fff; }
		.pop-ups h3 a { display: inline-block; width: 28px; height: 28px; margin-top: -4px; background: url(img/lg_close.png) no-repeat; }

		#promptBox { width: 200px; }
		#promptBox p { text-align: center; }

		/*添加样式*/
		.shadow { display: none; position: fixed; z-index: 999; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; opacity: 0.5; }
		.pop-ups { position: fixed; z-index: 1000; top: 50%; left: 50%; background-color: #fff; }
		#promptBox { margin: -100px 0 0 -125px; display: none; }
	</style>
</head>
<body>
		<!-- content start -->
		<div class="r content" id="user_content">
			<div class="topNav">Wifi Billing&nbsp;&gt;&gt;&nbsp;<a href="#">Wifi Package</a></div>
	
			<div class="searchResult">
			<form action="<?php echo $weburl?>/indata/deleteall" class="formindex" method="post">
		
				<table>
					<thead>
						<tr style=height:35px>
							<th><center><input type=checkbox name=All onclick="checkAll('ids[]')"></input></center></th>
							<th style="text-align:center">Name</th>
							<th style="text-align:center">WIFI(MB)</th>
							<th style="text-align:center">Price</th>
							<th style="text-align:center">Status</th>
							<th style="text-align:center">Operate</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($wifi_item as $k=>$v){?>
						<tr style=height:35px>
							<td ><input type=checkbox name="ids[]" value="<?php echo $v['wifi_id']?>" onclick="checkItem('All')"></input></td>
							<td ><?php echo $v['wifi_name']?></td>
							<td  ><?php echo $v['wifi_flow']?></td>
							
							<td>
							<?php 
							
					  		$str1='';
					  		
							for ($i=1;$i<=strlen($v['sale_price']);$i++){
								$str=substr($v['sale_price'],$i-1,1);
								 if ($i%3==0){
								 	if ($i!=strlen($v['sale_price'])){
								 	$str1.=$str.',';}
								 	else {
								 		$str1.=$str;
								 	}
									
								 }
								else{
									$str1.=$str;
								}
							}
							echo $str1;
							?>
							</td>
							<td ><?php echo $v['status']==0?'enable':'disable'?></td>
							<td  >
						
								<a href="<?php echo $weburl?>/indata/edit?wifi_id=<?php echo $v['wifi_id']?>"><img src="<?php echo $baseUrl?>images/write.png"></a>
								<img class="delete" onclick="infodata('<?php echo $v['wifi_id']?>')" src="<?php echo $baseUrl?>images/delete.png">
							</td>
						</tr>
					<?php }?>
					</tbody>
				</table>
				<p class="records">Records:<span><?php echo $maxcount?></span></p>
				<div class="btn">
					<a href="edit"><input type="button" value="Add"></input></a>
					<input type="button" id="deleteall" class='delete' value="Del Selected"></input>
				</div>
				</form>
						
				<div class="pageNum">
				<form id='member_list' method="post">
				 	<input type='hidden' name='page' value="<?php echo $page?>">
                 	<input type='hidden' name='isPage' value="1">
                 	<div class="center" id="page_div"></div> 
	           	</form>	
				</div>
			</div>
		</div>
		<!-- content end -->
<form action="<?php echo $weburl?>/indata/delete" method="post" id="formpost"><!-- 按删除按钮时提交的表单 -->
<input type="hidden" name="wifi_id" id="wifi_id">
</form>
	<!-- main end -->
	

<script type="text/javascript"> 
function infodata(t){
	/* document.getElementsById("wifi_id").value */
	document.getElementById("wifi_id").value=t; 
	
	 }
$(function(){
	$("body").on("click",".delete",function(){
		$("#promptBox").css("display","block");
		$(".shadow").css("display","block");
	});
	$("body").on("click",".undelete",function(){
		$("#promptBox").css("display","none");
		$(".shadow").css("display","none");
	});
	$(".thissubmit").click(function(){
	 if($("#wifi_id").val()==''){
		$(".formindex").submit();
	}else{
		$("#formpost").submit();
	} 
});
	<?php
    $massage=isset($massage)?$massage:'';
    if ($massage==''){
    	
    }elseif ($massage=='success'){?>//操作信息弹出框
		alert('Option success');
		window.location = "<?php echo $weburl?>/indata/index";
	<?php }else {
	?>
	alert('OPtion fail');
	window.location = "<?php echo $weburl?>/indata/index";
	<?php
}	
?>
	
});

function checkAll(str)    
{    
	
   	var a = document.getElementsByName(str);    
    var n = a.length;    
    for (var i=0; i<n; i++)    
    a[i].checked = window.event.srcElement.checked;    
}    
function checkItem(str)    
{    
    var e = window.event.srcElement;    
    var all = eval("document.all."+ str);    
    if (e.checked)    
    {    
        var a = document.getElementsByName(e.name);    
        all.checked = true;    
        for (var i=0; i<a.length; i++)    
        {    
            if (!a[i].checked)   
            {    
                all.checked = false; break;   
            }    
        }   
    }    
    else    
        all.checked = false;    
}   
jQuery(function($) {
	  
	/* 获取参数 */
	//分页
	var page = <?php echo $page;?>;
		$('#page_div').jqPaginator({
            totalPages: <?php echo $count;?>,
            visiblePages: 5,
            currentPage: page,
            wrapper:'<ul class="pagination"></ul>',
            first:  '<li class="first"><a href="javascript:void(0);">First</a></li>',
            prev:   '<li class="prev"><a href="javascript:void(0);">«</a></li>',
            next:   '<li class="next"><a href="javascript:void(0);">»</a></li>',
            last:   '<li class="last"><a href="javascript:void(0);">Last</a></li>',
            page:   '<li class="page"><a href="javascript:void(0);">{{page}}</a></li>',
            onPageChange: function (num) {
                var val = $("input[name='page']").val();
                if(num != val)
                {
                    $("input[name='page']").val(num);
                    $("input[name='isPage']").val(2);
                    $("form#member_list").submit();
                }
            }
        });				
});  
</script> 
	<div class="shadow"></div>
	<div id="promptBox" class="pop-ups write">
		<h3>Warmming<a href="#" class="close r"></a></h3>
		<p>Are you sure to delete? </p>
		<p class="btn">
		
			<input type="button" class="thissubmit"  value="Yes"></input>
			<input type="button" class="undelete" value="No"></input>
		</p>
	</div>
	
</body>
</html>