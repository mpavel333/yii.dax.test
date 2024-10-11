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
class ColorAdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/theme';
    public $css = [
        // 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700',
        'assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css',
        'assets/plugins/bootstrap/css/bootstrap.min.css',
        'assets/plugins/font-awesome/css/font-awesome.min.css',
        'assets/css/animate.min.css',
        'assets/css/style.min.css',
        'assets/css/style-responsive.min.css',
        'assets/css/theme/default.css',
        'assets/plugins/bootstrap-wizard/css/bwizard.min.css'
//        'assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css',
//        'assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css',
//        'assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css',
//        'assets/plugins/DataTables/extensions/AutoFill/css/autoFill.bootstrap.min.css',
//        'assets/plugins/DataTables/extensions/ColReorder/css/colReorder.bootstrap.min.css',
//        'assets/plugins/DataTables/extensions/KeyTable/css/keyTable.bootstrap.min.css',
//        'assets/plugins/DataTables/extensions/RowReorder/css/rowReorder.bootstrap.min.css',
//        'assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css',
    ];
    public $js = [
        'assets/plugins/jquery/jquery-migrate-1.1.0.min.js',
        'assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js',
         'assets/plugins/bootstrap/js/bootstrap.min.js',
//        'assets/plugins/slimscroll/jquery.slimscroll.min.js',
        'assets/plugins/jquery-cookie/jquery.cookie.js',
//        'assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
//        'assets/plugins/DataTables/media/js/jquery.dataTables.js',
//        'assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js',
//        'assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js',
//        'assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js',
//        'assets/plugins/DataTables/extensions/AutoFill/js/dataTables.autoFill.min.js',
//        'assets/plugins/DataTables/extensions/ColReorder/js/dataTables.colReorder.min.js',
//        'assets/plugins/DataTables/extensions/KeyTable/js/dataTables.keyTable.min.js',
//        'assets/plugins/DataTables/extensions/RowReorder/js/dataTables.rowReorder.min.js',
//        'assets/plugins/DataTables/extensions/Select/js/dataTables.select.min.js',
        'assets/js/table-manage-combine.demo.min.js',
        'assets/js/apps.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
