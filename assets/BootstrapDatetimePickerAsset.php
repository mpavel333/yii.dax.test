<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapDatetimePickerAsset
 * @package app\assets
 */
class BootstrapDatetimePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/eonasdan-bootstrap-datetimepicker';
    public $css = [
        'build/css/bootstrap-datetimepicker.min.css',
    ];
    public $js = [
        'build/js/bootstrap-datetimepicker.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\AppAsset',
        'app\assets\ColorAdminAsset'
    ];
}