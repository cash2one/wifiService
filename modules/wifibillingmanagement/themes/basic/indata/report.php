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
			<div class="topNav">Wifi Report&nbsp;&gt;&gt;&nbsp;<a href="#">Report</a></div>
			<div class="searchResult">
				<table>
					<thead>
						<tr style="height:35px">
							<th>Num</th>
							<th>type</th>
							<th>XMLcontent</th>
							<th>time</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($wifi_item as $k=>$v){?>
						<tr  style="height:35px">
						<td><?php echo $k+1;?></td>
						<td><?php echo $v['type']==0?'accept':'send';?></td>
							<td  align="left" ><?php echo "<pre style='background-color:#ffffff;'><xmp style='width:150px;'>".$v['content']."</xmp></pre>"; ?></td>
							<td><?php echo $v['time'];?></td>
						</tr>
					<?php }?>
					</tbody>
				</table>
				<p class="records">Records:<span><?php echo $maxcount?></span></p>
				
				<div class="pageNum">
					<form id='member_list' method="post">
						<input type='hidden' name='page' value="<?php echo $page?>">
                        <input type='hidden' name='isPage' value="1">
                        <div class="center" id="page_div"></div> 
	              	</form>	
				<!-- 	<span>
						<a href="#" class="active">1</a>
						<a href="#">2</a>
						<a href="#">》</a>
						<a href="#">Last</a>
					</span> -->
				</div>
			</div>
		</div>
		<!-- content end -->
	</main>
	<!-- main end -->
<script type="text/javascript">
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
</body>
</html>