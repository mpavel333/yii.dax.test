<?php

namespace app\controllers;

use Yii;
use app\behaviors\RoleBehavior;
use app\models\PlanReport;
use app\models\Car;
use app\models\CarSearch;
use app\models\Flight;
use app\models\FlightSearch;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\components\MyCache;

use app\components\Ati;


/**
 * FlightController implements the CRUD actions for PlanReport model.
 */
class PlanReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'except' => ['application-form', 'map', 'upd-doc', 'print-template'],
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
            /*
            'role' => [
                'class' => RoleBehavior::class,
                'instanceQuery' => \app\models\PlanReport::find(),
                'actions' => [
                    'create' => 'flight_create',
                    'update' => 'flight_update',
                    'view' => 'flight_view',
                    'delete' => 'flight_delete',
                    'bulk-delete' => 'flight_delete',
                    'index' => ['flight_view', 'flight_view_all'],
                ],
            ],
            */
        ];
    }

    /**
     * Lists all PlanReport models.
     * @return mixed
     */
    public function actionIndex()
    { 
       
        $params = Yii::$app->request->queryParams;

        $searchModel = new CarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(!empty($params) && isset($params['CarSearch'])){
                
            $date_from = $params['CarSearch']['date_from'];
            if(isset($date_from) && !empty($date_from)){
                $searchModel->date_from = $date_from;
            }

            $date_to = $params['CarSearch']['date_to'];
            if(isset($date_to) && !empty($date_to)){
                $searchModel->date_to = $date_to;
            }  

        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);        


    }


    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        $validate = 1; //!\Yii::$app->user->identity->can('flight_disable_validation');

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Изменить Отчет #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save($validate)){
                return [
                    'forceReload'=>'#crud-datatable-planreport-pjax',
                    'forceClose' => true,
                ];    
            }else{

                if($request->isPost){

                    $model->save(false);

                }

                \Yii::warning($model->errors, '$errors');
                 return [
                    'title'=> "Изменить Отчет #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
 
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }


    protected function findModel($id)
    {
        if (($model = Car::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }

    public function actionGetByAttr($attr,$value)
    {   
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = PlanReport::find()->where([$attr => $value])->one();
        return $model;
    }

}
