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
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
	<?=Html::cssFile('@web/css/index.css')?>

<?=Html::cssFile('@web/assets/css/chosen.css')?>
<?=Html::cssFile('@web/assets/css/bootstrap.min.css')?>
<?=Html::cssFile('@web/assets/css/font-awesome.min.css')?>
<?=Html::cssFile('@web/assets/css/datepicker.css')?>
<?=Html::cssFile('@web/assets/css/bootstrap-timepicker.css')?>
<?=Html::cssFile('@web/assets/css/colorpicker.css')?>
<?=Html::cssFile('@web/assets/css/daterangepicker.css')?>
<?=Html::cssFile('@web/assets/css/colorpicker.css')?>
		<!-- fonts -->
<?=Html::cssFile('@web/assets/css/ace-fonts.css')?>
	
</head>
<body>
	
				
	  <form style="position:relative;" enctype="multipart/form-data" id="import_form" action="infodata" method='post'>
                                                        <input style="margin-left: 50%" type='file' name="import_input" class="import_file"/>
                                                        <br>   <br>   <br>
                                                       <input type="submit" value="提交" id="submit" class="btn btn-primary" style="margin-left: 45%"/>
                                                        </form>
                                                        <form action="savedata" method="post">
                                                        <?php if (isset($data)){?>
														<table>
														<tr width=20%>
														<td>wifi_code</td>
														<td>wifi_password</td>
														</tr>
														<?php foreach ($data as $k=>$v){?>
														<tr>
														<td><?php echo $v['wifi_code']?></td>
														<td><?php echo $v['wifi_password']?></td>
														</tr>
														<?php Yii::$app->session['mydata']=$data;?>
														<?php }}?>
														</table>	
													<input type="submit" value="保存" id="submit" class="btn btn-primary" style="margin-left: 5%"/>
														</form>
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

