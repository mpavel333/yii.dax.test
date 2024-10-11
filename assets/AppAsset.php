<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'libs/datepicker/datepicker.min.css',
        'libs/pickadate/lib/themes/default.css',
        'libs/pickadate/lib/themes/classic.date.css',
        'libs/magnific-popup/magnific-popup.css',
        'css/design.css',
        'css/site.css',
//        'libs/flatpickr-master/dist/flatpickr.min.css'
    ];
    public $js = [
//        'libs/flatpickr-master/dist/flatpickr.min.js',
//        'libs/flatpickr-master/dist/I10n/ru.js',
//        'libs/cleave/cleave.min.js',
        'libs/datepicker/datepicker.min.js',
        'libs/pickadate/lib/legacy.js',
        'libs/pickadate/lib/picker.js',
        'libs/pickadate/lib/picker.date.js',
        //'libs/pickadate/lib/translations/ru_RU.js',
        'libs/magnific-popup/jquery.magnific-popup.min.js',
        'js/common.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
