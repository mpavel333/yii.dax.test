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
use app\components\MyCache;

use app\components\Ati;

use app\models\Client;
use app\models\ClientSearch;


/**
 * LawyerController implements the CRUD actions for Flight model.
 */
class LawyerController extends Controller
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
            'role' => [
                'class' => RoleBehavior::class,
                'instanceQuery' => \app\models\Flight::find(),
                'actions' => [
                    'create' => 'flight_create',
                    'update' => 'flight_update',
                    'view' => 'flight_view',
                    'delete' => 'flight_delete',
                    'bulk-delete' => 'flight_delete',
                    'index' => ['flight_view', 'flight_view_all'],
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
        // ini_set('memory_limit', -1);
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 5;


        // $dataProvider->query->andWhere(['or', ['flight.is_order' => false], ['and', ['flight.is_order' => true], ['is not', 'flight.user_id', null]]]);
        $dataProvider->query->andWhere(['or', ['flight.is_order' => false], ['and', ['flight.is_order' => true]]]);

        if(\Yii::$app->user->identity->can('lawyer') == false){
            $dataProvider->query->andWhere(['flight.user_id' => \Yii::$app->user->getId()]);
        }
        
        $dataProvider->query->andWhere(['flight.is_lawyer' => true]);        
        
        //////////// 
        // нумерация с обнулением с начала каждого года
        $counter = clone $dataProvider;
        $counter->pagination = false;
        $counter->setSort([
          'defaultOrder' => ['created_at'=>SORT_ASC],
        ]);
        $years = [];
        $numeration = [];
        $c = 1;

        $models = array_reverse($counter->models);
      
        foreach ($models as $key=>$model){
            
            if($model->date_cr):
                $year = date('Y',strtotime($model->date_cr));
            elseif($model->date):
                $year = date('Y',strtotime($model->date));
            elseif($model->created_at):
                $year = date('Y',strtotime($model->created_at));
           // else:
            //    continue;
            endif;

            if(!in_array($year,$years)){
                $years[]=$year;
                $c = 1;
            }
                
            $numeration[$model->id]=[$c,$year];
            $c++;

        }
        /////////////////

        $dataProvider->query->limit(1);

        return $this->render('@app/views/flight/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numeration' => $numeration
        ]);
    }

    public function actionContracts()
    { 
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['type' => Client::TYPE_CLIENT]);

        $dataProvider->query->andWhere(['client.is_lawyer' => true]);     

        return $this->render('@app/views/client/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }


}
