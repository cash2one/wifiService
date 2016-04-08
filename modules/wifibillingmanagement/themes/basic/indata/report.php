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
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl?>css/public.css">
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
			<div class="topNav">Wifi Billing&nbsp;&gt;&gt;&nbsp;<a href="#">Wifi</a></div>
	
			<div class="searchResult">
				<table>
					<thead>
						<tr>
							<th>序号</th>
							<th>价格</th>
							<th>流量(MB)</th>
							<th>有效qi</th>
							<th>状态</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($wifi_item as $k=>$v){?>
						<tr>
						<td><?php echo $k+1?></td>
							<td><?php echo $v['sale_price']?></td>
							<td><?php echo $v['wifi_flow']?></td>
							
						<td><?php echo $v['expiry_day']?></td>
							
							<td><?php echo $v['status']==0?'可用':'停用'?></td>
							
						</tr>
					<?php }?>
						
					</tbody>
				</table>
				<p class="records">Records:<span>26</span></p>
				<div class="btn">
					<a href="edit"><input type="button" value="Add"></input></a>
					<input type="button" value="Del Selected"></input>
				</div>
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