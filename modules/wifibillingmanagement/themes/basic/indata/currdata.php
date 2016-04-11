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
			<div class="topNav">Wifi Billing&nbsp;&gt;&gt;&nbsp;<a href="#">Curr Card</a></div>
			<form id='member_list' method="post">
				<div class="search" style="margin-left: 26%">
				<label>
					<span>Wifi Pakage:</span>
				<select  style="width: 250px;height:30px" name="wifi_id">
					<option selected='selected' value="">All</option>
					<?php foreach ($wifi_items_info as $k=>$v):?>				
					<option value="<?=$v['wifi_id']?>" <?php echo $v['wifi_id']==$wifi_id?"selected='selected'":''?> ><?=$v['wifi_name']?></option>
					<?php endforeach;?>
				</select>
				</label>
				
				<span class="btn"><input type="submit" value="SEARCH"></input></span>
				</div>
				
			<div class="searchResult">
				<table>
					<thead>
						<tr style="height:35px">
						<th>Num</th>
						<th>wifi_code</th>
						<th>wifi_password</th>
						<th>expiry_day</th>
						<th>Wifi Pakage</th>
						<th>status_sale</th>
						<th>time</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($wifi_item as $k=>$v){?>
						<tr style="height:35px">
							<td><?php echo $k+1?></td>
							<td><?php echo $v['wifi_code']?></td>
							<td><?php echo $v['wifi_password']?></td>
							<td><?php echo $v['expiry_day']?></td>
							<td><?php echo $v['wifi_name']?></td>
							<td><?php echo $v['status_sale']==0?'usable':'unusable'?></td>
							<td><?php echo $v['time']?></td>
						</tr>
					<?php }?>
						
					</tbody>
				</table>
			<p class="records">Records:<span><?php echo $maxcount?></span></p>
			<div class="pageNum">
				<input type='hidden' name='page' value="<?php echo $page?>">
				<input type='hidden' name='isPage' value="1">
				<div class="center" id="page_div"></div> 
	                                
			</div>
			</div>
			 </form>
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