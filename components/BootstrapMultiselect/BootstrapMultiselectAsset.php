<?php
namespace components\BootstrapMultiselect;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class BootstrapMultiselectAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $css = ['css/bootstrap-multiselect.css'];
    public $js = ['js/bootstrap-multiselect.js'];
//	public $jsOptions = ['position'=>yii\web\View::POS_HEAD]; 
    public $depends = [
		'yii\web\JqueryAsset', 'yii\bootstrap\BootstrapPluginAsset',
    ];

}

