<?php

namespace app\controllers;

use Yii;
use app\behaviors\RoleBehavior;
use app\models\Flight;
use app\models\FlightSearch;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * FlightGroupController implements the CRUD actions for Flight model.
 */
class FlightGroupController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'except' => ['application-form'],
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
            'role' => [
                'class' => RoleBehavior::class,
                'instanceQuery' => \app\models\Flight::find(),
                'actions' => [
                    'create' => 'flight_create',
                    'update' => 'flight_update',
                    'view' => 'flight_view',
                    'delete' => 'flight_delete',
                    'bulk-delete' => 'flight_delete',
                    'index' => 'flight_orders_show',
                ],
            ],
        ];
    }

    /**
     * Lists all Flight models.
     * @return mixed
     */
    public function actionIndex()
    {    
        ini_set('memory_limit', -1);
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['is_metal' => false]);
        $dataProvider->pagination->pageSize = 50;

        $users = \app\models\User::find()->all();
        $users = array_filter($users, function($user){
            if($user->group == null){
                return false;
            }
            $role = explode(',', $user->group);
            $userRole = explode(',', \Yii::$app->user->identity->group);


            foreach ($role as $r) {
                foreach ($userRole as $userR) {
                    if($r == $userR){
                        return true;
                    }
                }
            }

            return false;
        });

        if(count($users) > 0){
            $usersPks = \yii\helpers\ArrayHelper::getColumn($users, 'id');
            $dataProvider->query->andWhere(['created_by' => $usersPks]);
        } else {
            $dataProvider->query->andWhere(['created_by' => -1]);
        }

        $dataProvider->query->andWhere(['is_register' => true]);


        return $this->render('@app/views/flight/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

/**
     * Delete multiple existing Flight model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = \app\models\Flight::findOne($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }
}
