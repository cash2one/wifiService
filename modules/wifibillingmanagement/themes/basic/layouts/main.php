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

// echo $permissionName;
// exit;

$Wifi_package_active = false;
$url_active = false;
$carr_active = false;
$import_active = false;
$pay_active=false;
$report_active=false;

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
<header id="header">
    <div class="l" id="title">
        <img src="<?=$baseUrl ?>images/logo.png">
        <h1><?= \Yii::t('app', 'Wifi Billing Management') ?></h1>
    </div>
    <div class="r" id="user">
        <div class="l" id="user_img">
            <img src="<?=$baseUrl ?>images/user_img.png">
        </div>
        <div class="r">
            <span><?php echo Yii::$app->session['admin_name']?></span>
            <a href="/wifibilling/login/loginout">Exit</a>
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
                    <li class="<?php echo ($Wifi_package_active ? 'active':'')?>"><a href="/wifibilling/indata/index"><?= \Yii::t('app', 'Wifi Package') ?></a></li>
                     <li class="<?php echo ($url_active ? 'active':'')?>"><a href="/wifibilling/indata/wifiurl"><?= \Yii::t('app', 'Wifi URL') ?></a></li>
						<li class="<?php echo ($carr_active ? 'active':'')?>"><a href="/wifibilling/indata/currdata">Curr Card</a></li>
						<li class="<?php echo ($import_active ? 'active':'')?>"><a href="/wifibilling/indata/updata"><?= \Yii::t('app', 'Import Card') ?></a></li>
                    <li class="<?php echo $pay_active?'active':''?>"><a href="/wifibilling/indata/pay"><?= \Yii::t('app', 'IBS pay set') ?></a></li>
                    <li class="<?php echo ($report_active ? 'active':'')?>"><a href="/wifibilling/indata/report"><?= \Yii::t('app', 'Report') ?></a></li>
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
