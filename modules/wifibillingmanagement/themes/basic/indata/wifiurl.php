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

?>

<!DOCTYPE html>
<html>
<head>
	<title>上网</title>
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
	<!-- header start -->
	
	<!-- header end -->
	<!-- main start -->
	<main id="main" style="margin-left:1%">
		<!-- asideNav start -->
		<aside id="asideNav" class="l"></aside>
		<!-- asideNav end -->
		<!-- content start -->
		<div class="r content" id="user_content">
			<div class="topNav">Wifi Billing&nbsp;&gt;&gt;&nbsp;<a href="#">Wifi URL</a></div>
	
			<div class="searchResult">
			<form action="deleteallurl" class="formindex" method="post">
				<table  >
					<thead>
						<tr style="height:35px">
							<th style="width:50px"><center><input type=checkbox name=All onclick="checkAll('ids[]')"></input></center></th>
							<th>name</th>
							<th>url</th>
						
							<th>remark</th>
							
							
							<th>Operate</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($wifi_item as $k=>$v){?>
						<tr style="height:35px">
							<td><input type=checkbox name="ids[]" value="<?php echo $v['id']?>" onclick="checkItem('All')"></input></td>
							<td  align="left"><?php echo $v['name']?></td>
							<td  align="left"><?php echo $v['url']?></td>
							
						
							<td  align="left"><?php echo $v['remark']?></td>
							
							<td  align="left">
						
								<a href="editurl?id=<?php echo $v['id']?>"><img src="<?php echo $baseUrl?>images/write.png"></a>
								<img class="delete" onclick="infodata('<?php echo $v['id']?>')" src="<?php echo $baseUrl?>images/delete.png">
							</td>
						</tr>
					<?php }?>
						
					</tbody>
					
				</table>
				
				<p class="records">Records:<span><?php echo $maxcount?></span></p>
				<div class="btn">
					<a href="editurl"><input type="button" value="Add"></input></a>
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
	</main>
<form action="deleteurl" method="post" id="formpost"><!-- 表单按删除按钮时提交的 -->
<input type="hidden" name="id" id="id">
</form>
	<!-- main end -->
	
<script type="text/javascript"> 
function infodata(t){
	/* document.getElementsById("wifi_id").value */
	document.getElementById("id").value=t; 
	
	 }
$(function(){

		/* 获取参数 */
			//分页
		     var page = <?php echo $page;?>;
		        $('#page_div').jqPaginator({
		            totalPages: <?php echo $count;?>,
		            visiblePages: 5,
		            currentPage: page,
		            wrapper:'<ul class="pagination"></ul>',
		            first:  '<li class="first"><a href="javascript:void(0);">首页</a></li>',
		            prev:   '<li class="prev"><a href="javascript:void(0);">«</a></li>',
		            next:   '<li class="next"><a href="javascript:void(0);">»</a></li>',
		            last:   '<li class="last"><a href="javascript:void(0);">尾页</a></li>',
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
				
					
	$("body").on("click",".delete",function(){
		$("#promptBox").css("display","block");
		$(".shadow").css("display","block");
	});
	$("body").on("click",".undelete",function(){
		$("#promptBox").css("display","none");
		$(".shadow").css("display","none");
	});
	$(".thissubmit").click(function(){
		
		 if($("#id").val()==''){
			$(".formindex").submit();
			}
		else{
$("#formpost").submit();
		} 
		});
	<?php
    $massage=isset($massage)?$massage:'';
    if ($massage==''){}
	elseif ($massage=='success'){?>//操作信息弹出框
	alert('Option success');
	<?php }
else {
	?>
	alert('Option fail');
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

</script> 
   
   
	<div class="shadow"></div>
	<div id="promptBox" class="pop-ups write">
		<h3>Prompt<a href="#" class="close r"></a></h3>
		<p>Whether to delete??</p>
		<p class="btn">
			<input type="button" class="thissubmit"  value="Yes"></input>
			<input type="button" class="undelete" value="No"></input>
		</p>
	</div>
	
</body>
</html>