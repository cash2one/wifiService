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
	
			<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
		

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
			<div class="topNav">Wifi Billing&nbsp;&gt;&gt;&nbsp;<a href="#">Import Card</a></div>

                                       <form style="position:relative;" enctype="multipart/form-data" id="import_form" action="infodata" method='post'>
                          
						<div style="height: 30px"></div>
						<div style="width: 350px;float:left;margin-left:1%">
										<span>Wifi Expiry_day(day):</span>
				<input type="text"  style="height:23px" name="expiry_day" placeholder="50">
				</div>
									<div>	
					
									<span class="btn uploadFile_btn">
									<span>Wifi Package:</span>
										<?php $wifi_items = (new \yii\db\Query())
									    	->from('wifi_item_language')
									    	->where(['iso'=>'zh_cn'])
									    	->all(); ?>
									<select name="wifi_id" style="height:31px;width:200px">
											<option selected='selected' value="">All</option>
											
											<?php foreach ($wifi_items as $k=>$v):?>				
																<option value="<?=$v['wifi_id']?>" <?php if (isset($wifi_id)){if ($wifi_id==$v['wifi_id']){echo "selected='selected'"; }}?>><?=$v['wifi_name']?></option>
																<?php endforeach;?>
															</select>
								</span>
				<label class="uploadFileBox">
					<span class="fileName">Select File...</span>
					<a href="#"  class="uploadFile">choose<input type="file" name="import_input" class="import_file"></input></a>
				</label>
				
				<span class="btn uploadFile_btn">
					<input type="submit" style="height: 31px"  value="submit"></input>
				</span>
				
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
				<p class="records">Wifi Expiry_day(day):<span><?php echo Yii::$app->session['expiry_day']?></span></p>
				<div class="btn">
					<input type="submit" value="save"></input>
					
				</div>
				</form>
				<?php }?>
				<div class="pageNum">
				  <div class="center" id="page_div"></div> 
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

    <?php $massage=isset($massage)?$massage:'';?>
    <?php if ($massage==1){?>
    alert("请选择套餐");
    <?php }elseif ($massage==2){?>
    alert("操作成功");
    <?php }elseif ($massage==3){?>
    alert("操作失败");
    <?php }?>
    //操作后弹出框
});

</script>
</body>
</html>