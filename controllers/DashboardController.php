<?php

namespace app\controllers;

use app\models\CuttingParams;
use Yii;
use app\models\Cutting;
use app\models\CuttingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * DashboardController implements the CRUD actions for Cutting model.
 */
class DashboardController extends Controller
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
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }



    /**
     * Lists all Cutting models.
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit', -1);

        return $this->render('index', [
        	
        ]);
    }

    public function actionUpload1CFile($type)
    {
        $request = Yii::$app->request;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Загрузить 1С-файл",
                    'content'=>$this->renderAjax('upload-1c', [
                        // 'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Загрузить',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($request->isPost){

                $file = \yii\web\UploadedFile::getInstanceByName('file');
                $file->saveAs('1cupload.txt');

                $content = file_get_contents('1cupload.txt');
                $content = iconv('CP1251', 'UTF-8', $content);
                $content = explode("\n", $content);


                // Parsing
                $parsingData = [];
                $row = [];
                foreach($content as $line){
                    \Yii::warning($line, '$line');
                    $line = trim($line);
                    $parts = explode('=', $line);
                    if(count($parts) > 1){
                        $key = $parts[0];
                        $value = $parts[1];

                        if($key == 'Дата'){
                            $row['date'] = implode('-', array_reverse(explode('.', $value)));
                        }
                        if($key == 'Сумма'){
                            $row['amount'] = $value;
                        }
                        if($key == 'Плательщик'){
                            $row['payment'] = $value;
                        }
                        if($key == 'ПлательщикИНН'){
                            $row['payment_inn'] = $value;
                        }
                        if($key == 'Получатель'){
                            $row['receiver'] = $value;
                        }
                        if($key == 'ПолучательИНН'){
                            $row['receiver_inn'] = $value;
                        }
                    }

                    if($line == 'КонецДокумента'){
	                    \Yii::warning('КонецДокумента', 'КонецДокумента');
                        $parsingData[] = $row;
                        $row = [];
                    }
                }
                \Yii::warning($parsingData, '$parsingData');

                foreach($parsingData as $datum)
                {
                	$client = \app\models\Client::find()->where(['inn' => $datum['receiver_inn']])->one();

                	if($client){
                		$payment = new \app\models\Payment([
                			'client_id' => $client->id,
                			'type' => $type,
                			'amount' => $datum['amount'],
                			'payment' => $datum['payment'],
                			'payment_inn' => $datum['payment_inn'],
                			'receiver' => $datum['receiver'],
                			'receiver_inn' => $datum['receiver_inn'],
                			'date' => $datum['date'],
                		]);
                		$payment->save(false);
                	}
                }

                return [
                    // 'forceReload'=>'#crud-datatable-flight-pjax',
                    'forceClose' => true,
                ]; 
        
            }else{           
                return [
                    'title'=> "Загрузить 1С-файл",
                    'content'=>$this->renderAjax('upload-1c', [
                        // 'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Загрузить',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if (true) {
                // return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }
}
