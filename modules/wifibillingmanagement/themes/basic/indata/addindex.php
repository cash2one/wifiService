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
</head>
<body>
<form action="indataupdate" method="post" >	
     <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-3">中文名（M）</label>
        <div class="col-sm-9" >
          <input type="text" style="width:60%" name="isozhong"   id="form-field-3" placeholder="Wifi套餐一：50M" class="col-xs-10 col-sm-5" />
       	</div>
        </div>
           <br/><br/>
     <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-3">英文名（M）</label>
        <div class="col-sm-9" >
          <input type="text" style="width:60%" name="isoying"   id="form-field-3" placeholder="Wifipackage：50M" class="col-xs-10 col-sm-5" />
          </div>
        </div>
         <br/><br/>
        <input type="hidden" name='t' value='1'>
        	<input type="submit" value="提交" id="submit" class="btn btn-primary" style="margin-left: 5%"/>
        </form>
</body>

