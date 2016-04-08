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

                                                          <form style="position:relative;" enctype="multipart/form-data" id="import_form" action="infodata" method='post'>
                                                        <div class="search" style="margin-left:40%">
				<label>
					<span>文件:</span>
					<input  type='file' name="import_input" class="import_file"/>
				</label>
				
				<span class="btn"><input type="submit" value="提交"></input></span>
			</div>
			  </form>
			<div class="searchResult">
			  <?php if (isset($data)){?>
			  <form action="savedata" method="post">
                  
				<table>
					<thead>
						<tr>
						
							<th>序号</th>
							<th>wifi_code</th>
						
							<th>wifi_password</th>
							
						
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $k=>$v){?>
						<tr>
							<td><?php echo $k+1?></td>
							<td><?php echo $v['wifi_code']?></td>
							<td><?php echo $v['wifi_password']?></td>
						
						</tr>
					<?php }?>
						
					</tbody>
				</table>
				<?php Yii::$app->session['mydata']=$data;?>
				
				
				
				
				<p class="records">有效时间(月):<span>26</span></p>
				<div class="btn">
					<a href="edit"><input type="submit" value="save"></input></a>
					
				</div>
				</form>
				<?php }?>
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
	<?=Html::jsFile('@web/js/jquery-2.2.2.min.js')?>
	<?=Html::jsFile('@web/js/index.js')?>
	<script type="text/javascript">
$(function(){
    //监听文件域选中文件
    $("input[name='import_input']").change(function(){
        
        var fileName = $(this).val();
        var extStart  = fileName.lastIndexOf(".")+1;
        var fileext1 = fileName.substring(extStart,fileName.length).toLowerCase(); 
        var allowtype =  ["xls","xlsx"];
        if ($.inArray(fileext1,allowtype) == -1)
        {
            alert("请输入正确格式的excel文件!");
            return false;
        }
       
    });
});

</script>
</body>
</html>