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
		<div class="r content" id="user_content">
			<div class="topNav">Wifi Billing&nbsp;&gt;&gt;&nbsp;<a href="#">Curr Card</a></div>
			<form id='member_list' method="post">
				<div class="search" style="margin-left:3%">
				<label>
					<span>Status Sale:</span>
				<select style="width:150px" name="status_sale">
				<option selected='selected' value="2">All</option>
				<option value="1" <?php echo $status_sale==1?"selected='selected'":''?>>Sale</option>
				<option value="0"  <?php echo $status_sale==0?"selected='selected'":''?>>NotSale</option>
				</select>
				</label>
				<label>
					<span>Wifi Pakage:</span>
				<select  style="width:150px" name="wifi_id">
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
						<tr style="height:35px;">
						<th style="text-align:center">Num</th>
						<th style="text-align:center">wifi_code</th>
						<th style="text-align:center">expiry_day</th>
						<th style="text-align:center">Wifi Pakage</th>
						<th style="text-align:center">status_sale</th>
						<th style="text-align:center" >time</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($wifi_item as $k=>$v){?>
						<tr style="height:35px">
							<td><?php echo $k+1?></td>
							<td><?php echo $v['wifi_code']?></td>
							<td><?php echo $v['expiry_day']?></td>
							<td><?php echo $v['wifi_name']?></td>
							<td><?php echo $v['status_sale']==0?'notsale':'sale'?></td>
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