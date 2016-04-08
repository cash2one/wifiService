<?php
$this->title = 'Wifi Billing Management';
use app\modules\wifibillingmanagement\themes\basic\myasset\ThemeAsset;

ThemeAsset::register($this);
$baseUrl = $this->assetBundles[ThemeAsset::className()]->baseUrl . '/';

//$assets = '@app/modules/membermanagement/themes/basic/static';
//$baseUrl = Yii::$app->assetManager->publish($assets);
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html>
<head>
	<title>上网</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl?>css/public.css">
</head>
<body>
	<form action="" method="post">
	<div class="search" style="margin-left: 40%">	
	<label>
		<span>套餐包:</span>
		<select class="form-control" id="form-field-select-1" name="wifi_id">
			<option selected='selected' value="">全部</option>
			<?php foreach ($wifi_items as $k=>$v):?>				
			<option value="<?=$v['wifi_id']?>" ><?=$v['wifi_name']?></option>
			<?php endforeach;?>
		</select>
	</label>
	<span class="btn"><input type="submit" value="SEARCH"></input></span>
	</div>
	</form>
	
	<!-- main start -->
	<main id="main" style="margin-left:1%">
		<!-- asideNav start -->
		<aside id="asideNav" class="l"></aside>
		<!-- asideNav end -->
		<!-- content start -->
		<div class="r content" id="user_content">
			<div class="topNav">Wifi Billing&nbsp;&gt;&gt;&nbsp;<a href="#">Wifi</a></div>
			<div class="searchResult">
				<table>
					<thead>
						<tr>
							<th>序号</th>
							<th>wifi_id</th>
							<th>wifi_code</th>
							<th>wifi_password</th>
							<th>status_sale</th>
							<th>time</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($wifi_info as $k=>$v){?>
						<tr>
							<td><?php echo $k+1?></td>
							<td><?php echo $v['wifi_id']?></td>
							<td><?php echo $v['wifi_code']?></td>
							<td><?php echo $v['wifi_password']?></td>
							<td><?php echo $v['status_sale']==0?'可用':'停用'?></td>
							<td><?php echo $v['time']?></td>
						</tr>
					<?php }?>
					</tbody>
				</table>
			
				<div class="pageNum">
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
	<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $baseUrl?>js/public.js"></script>
</body>
</html>