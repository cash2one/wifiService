<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use app\modules\wifibillingmanagement\themes\basic\myasset\ThemeAsset;
$baseUrl = $this->assetBundles[ThemeAsset::className()]->baseUrl . '/';


$module = Yii::$app->controller->module->id;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$permissionName = $module.'/'.$controller.'/'.$action;


use Yii as myyii;
use app\components\Auth;
$weburl=Yii::$app->params['weburl'];
// echo $permissionName;
// exit;
/* 权限控制 */
$Wifi_package = false;
$url = false;
$carr = false;
$import = false;
$pay=false;
$pinformation=false;
$report=false;
$authcontroller=false;
$wlan_params=false;
$changepassword=false;
$ids=isset(Yii::$app->session['ids'])?Yii::$app->session['ids']:array();
foreach ($ids as $k=>$v){
	if ($v==1){
		$Wifi_package=true;
	}
	if ($v==2){
		$url=true;
	}
	if ($v==3){
		$carr=true;
	}
	if ($v==4){
		$import=true;
	}
	if ($v==5){
		$pay=true;
	}
	if ($v==6){
		$pinformation=true;
	}
	if ($v==7){
		$report=true;
	}
	if ($v==8){
		$authcontroller=true;
	}
	if ($v==9){
		$wlan_params=true;
	}
	if ($v==10){
		$changepassword=true;
	}
	
}
$info=Auth::GetRole($permissionName);
/* Auth::GetRole($permissionName); */

/*  */
$Wifi_package_active = false;
$url_active = false;
$carr_active = false;
$import_active = false;
$pay_active=false;
$report_active=false;
$pinformation_active=false;
$auth_action=false;
$wlan_params_action=false;
$changepassword_action=false;
if($permissionName == 'wifibilling/indata/index'||$permissionName=='wifibilling/indata/edit' ){
	
	$Wifi_package_active = true;
}
elseif ($permissionName=='wifibilling/indata/wifiurl'||$permissionName=='wifibilling/indata/editurl'){
	$url_active=true;
}
elseif ($permissionName=='wifibilling/indata/currdata'){
	$carr_active=true;
}
elseif ($permissionName=='wifibilling/indata/updata'||$permissionName=='wifibilling/indata/infodata'){
	$import_active=true;
}
elseif ($permissionName=='wifibilling/indata/pay'){

	$pay_active=true;
}
elseif ($permissionName=='wifibilling/indata/report'){
	$report_active=true;
}
elseif ($permissionName=='wifibilling/indata/payinformation'){
	$pinformation_active=true;
}
elseif ($permissionName=='wifibilling/indata/authcontroller'){
	$auth_action=true;
}
elseif ($permissionName=='wifibilling/indata/wlanparams'){
	$wlan_params_action=true;
}
elseif ($permissionName=='wifibilling/indata/changepassword'){
	$changepassword_action=true;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<!-- header start -->
<header id="header" style="width:1010px">
    <div class="l" id="title">
        <img style="margin-top:-17px" src="<?=$baseUrl ?>images/logo.png">
        <h1><?= \Yii::t('app', "Wifi Billing  Management") ?></h1>
    </div>
    <div class="r" id="user">
        <div class="l" id="user_img">
            <img src="<?=$baseUrl ?>images/user_img.png">
        </div>
        <div class="r">
            <span><?php echo Yii::$app->session['admin_name']?></span>
            <a href="<?php echo $weburl?>/login/loginout">Exit</a>
        </div>
    </div>
</header>
<!-- header end -->
<!-- main start -->
<main id="main">
    <!-- asideNav start -->
    <aside id="asideNav" class="l">
        <nav id="asideNav_open">
            <!-- 一级 -->
            <ul>
                <li class="open">
                    <a href="#"><img src="<?=$baseUrl ?>images/routeManage_icon.png"><?= \Yii::t('app', 'Wifi Billging') ?><i></i></a>
                </li>
                <!-- 二级 -->

               		 <ul>
               		 <?php if ($Wifi_package){?>
                     <li class="<?php echo ($Wifi_package_active ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/index"><?= \Yii::t('app', 'Wifi Package') ?></a></li>
                     <?php } if ($url){?>
                     <li class="<?php echo ($url_active ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/wifiurl"><?= \Yii::t('app', 'Wifi URL') ?></a></li>
					 <?php } if ($carr){?>
					 <li class="<?php echo ($carr_active ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/currdata">Curr Card</a></li>
					 <?php }if ($import){?>
					 <li class="<?php echo ($import_active ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/updata"><?= \Yii::t('app', 'Import Card') ?></a></li>
                     <?php }if ($pay){?>
                     <li class="<?php echo $pay_active?'active':''?>"><a href="<?php echo $weburl?>/indata/pay"><?= \Yii::t('app', 'IBS pay set') ?></a></li>
                      <?php }if ($pinformation){?>
                     <li class="<?php echo $pinformation_active?'active':''?>"><a href="<?php echo $weburl?>/indata/payinformation"><?= \Yii::t('app', 'Pay Information') ?></a></li>
                     <?php }if ($report){?>
                   	 <li class="<?php echo ($report_active ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/report"><?= \Yii::t('app', 'Report') ?></a></li>
               		  <?php }if ($authcontroller){?>
                   	 <li class="<?php echo ($auth_action ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/authcontroller"><?= \Yii::t('app', 'Auth Controller') ?></a></li>
               		 <?php }if ($wlan_params){?>
                   	 <li class="<?php echo ($wlan_params_action ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/wlanparams"><?= \Yii::t('app', 'Wlan Params') ?></a></li>
               		 <?php }if ($changepassword){?>
                   	 <li class="<?php echo ($changepassword_action ? 'active':'')?>"><a href="<?php echo $weburl?>/indata/changepassword"><?= \Yii::t('app', 'Change Password') ?></a></li>
               		 <?php }?>
		              </ul>
		            </ul>
            <a href="#" id="closeAsideNav"><img src="<?=$baseUrl ?>images/asideNav_close.png"></a>
        </nav>
        <nav id="asideNav_close">
            <ul>
                <li><img src="<?=$baseUrl ?>images/routeManage_icon.png"></li>
                <a href="#" id="openAsideNav"><img src="<?=$baseUrl ?>images/asideNav_open.png"></a>
            </ul>
        </nav>
    </aside>
    <!-- asideNav end -->
    <!-- content start -->
    <?= $content ?>
    <!-- content end -->
</main>
<!-- main end -->
<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
<script type="text/javascript">
$(function(){
var a=<?php echo $info?>;
if(a==false){
	window.location= "<?php echo $weburl?>/default/index";
	
}
})
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
