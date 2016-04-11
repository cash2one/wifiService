<?php
namespace app\modules\wifibillingmanagement\themes\basic\myasset;

use yii\web\AssetBundle;
/**
 * Created by PhpStorm.
 * User: leijiao
 * Date: 16/3/11
 * Time: 上午11:34
 */
class ThemeAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/wifibillingmanagement/themes/basic/static';
    public $css = [
        'css/public.css',
    	'assets/css/ace-fonts.css',
    	'assets/css/ace-rtl.min.css',
    	'assets/css/ace.min.css',
    	'assets/css/bootstrap.css',
    	'assets/css/bootstrap.min.css',
    	'assets/css/font-awesome.css',
    	'assets/css/font-awesome.min.css',
    ];

    public $js = [
        'js/jquery-2.2.2.min.js',
        'js/public.js',
    	'js/jqPaginator.js',
    	'assets/js/jquery-2.0.3.min.js',	
    	'assets/js/bootstrap.js',
    	'assets/js/jquery.validate.min.js',
    ];
}