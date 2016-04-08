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
//        'css/style_test.css'
    ];

    public $js = [
        'js/jquery-2.2.2.min.js',
        'js/public.js'
    ];
}