<?php

namespace app\controllers;

use app\components\ComponentReport;
use app\models\forms\ReportSearch;
use app\models\ReportColumn;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use Yii;

/**
 * Class ReportController
 * @package app\controllers
 */
class ReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        set_time_limit(0);
        $model = new ReportSearch();
        $model->oldSetting = $model->setting;
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $columns = [];
        if($model->setting != null){
            $setting = ReportColumn::findOne($model->setting);
            if($setting){
                $columns = $setting->getColumns();
            } else {
                $model->setting = null;
            }
        }

        return $this->render('index', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
    }

    /**
     *
     */
    public function actionExportExcel()
    {
        set_time_limit(0);
        $model = new ReportSearch();
        $model->oldSetting = $model->setting;
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        $columns = [];

        if($model->setting != null){
            $setting = ReportColumn::findOne($model->setting);
            if($setting){
                $columns = $setting->getColumns();
            } else {
                $model->setting = null;
            }
//            VarDumper::dump($setting->getColumns(), 10,true);
//            exit;
        }


        $reportColumn = new \app\components\ComponentReport(['columns' => $columns]);
        $columns = $reportColumn->getGridColumns(true);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setSubject("Отчет");
        $work_sheet = 0;
        $objWorkSheet = $objPHPExcel->createSheet($work_sheet);

        $objSet = $objPHPExcel->setActiveSheetIndex();
        $objGet = $objPHPExcel->getActiveSheet();

        $models = $dataProvider->models;

        for ($c = 0; $c < count($columns); $c++)
        {
            $column = $columns[$c];
            $header = $column['header'];
            $header = explode('</span></div>', $header);
            if(count($header) == 2){
                $name = $header[1];
                $modelName = explode('">', $header[0]);
                if(count($modelName) == 2){
                    $modelName = $modelName[1];
                    $objSet->setCellValueByColumnAndRow($c, 1, $modelName);
                    $objSet->setCellValueByColumnAndRow($c, 2, $name);
                    $objSet->getStyleByColumnAndRow($c, 1)->applyFromArray(
                        array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000')
                                )
                            ),
                        )
                    );
                    $objSet->getStyleByColumnAndRow($c, 2)->applyFromArray(
                        array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000')
                                )
                            ),
                            'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000'))
                        )
                    );
                }
            }
        }

        $objGet->getColumnDimension('A')->setWidth(20);
        $objGet->getColumnDimension('B')->setWidth(20);
        $objGet->getColumnDimension('C')->setWidth(20);
        $objGet->getColumnDimension('D')->setWidth(20);
        $objGet->getColumnDimension('E')->setWidth(20);
        $objGet->getColumnDimension('F')->setWidth(20);
        $objGet->getColumnDimension('G')->setWidth(20);
        $objGet->getColumnDimension('H')->setWidth(20);
        $objGet->getColumnDimension('I')->setWidth(20);
        $objGet->getColumnDimension('J')->setWidth(20);
        $objGet->getColumnDimension('L')->setWidth(20);
        $objGet->getColumnDimension('M')->setWidth(20);
        $objGet->getColumnDimension('N')->setWidth(20);
        $objGet->getColumnDimension('O')->setWidth(20);
        $objGet->getColumnDimension('P')->setWidth(20);
        $objGet->getColumnDimension('Q')->setWidth(20);
        $objGet->getColumnDimension('R')->setWidth(20);
        $objGet->getColumnDimension('S')->setWidth(20);
        $objGet->getColumnDimension('T')->setWidth(20);
        $objGet->getColumnDimension('U')->setWidth(20);
        $objGet->getColumnDimension('V')->setWidth(20);
        $objGet->getColumnDimension('W')->setWidth(20);
        $objGet->getColumnDimension('X')->setWidth(20);
        $objGet->getColumnDimension('Y')->setWidth(20);
        $objGet->getColumnDimension('Z')->setWidth(20);
        $objGet->getColumnDimension('AA')->setWidth(20);
        $objGet->getColumnDimension('AB')->setWidth(20);
        $objGet->getColumnDimension('AC')->setWidth(20);

        for($i = 0; $i < $dataProvider->count; $i++)
        {
            $model = $models[$i];
            for ($c = 0; $c < count($columns); $c++)
            {
                $column = $columns[$c];
                $attr = $column['attribute'];

                $row = $i + 3;

                if(isset($column['value']) || isset($column['content'])){
                    if(isset($column['content'])){
                        $content = call_user_func($column['content'], $model);
                    } else {
                        $content = call_user_func($column['value'], $model);

                    }
                } else {
                    $content = ArrayHelper::getValue($model, $attr);
                }

                if(in_array($column['attribute'], ComponentReport::DATE_COLUMNS)){
                    $content = $content ? Yii::$app->formatter->asDate($content, 'php:d.m.Y') : null;
                }

                $objSet->setCellValueByColumnAndRow($c, $row, $content);

                $objSet->getStyleByColumnAndRow($c, $row)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000000')
                            )
                        )
                    )
                );
            }
        }

        $filename = 'Отчет.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter->setPreCalculateFormulas(false);
        $objWriter->save('php://output');
        //без этой строки при открытии файла xlsx ошибка!!!!!!
        exit;

    }
}