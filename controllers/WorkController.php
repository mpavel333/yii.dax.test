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
 * WorkController implements the CRUD actions for Flight model.
 */
class WorkController extends Controller
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
        $dataProvider->pagination->pageSize = 50;
        $dataProvider->query->andWhere(['is_metal' => false]);


        if(Yii::$app->user->identity->isSuperAdmin()){
            $users = \app\models\User::find()->where(['and', ['!=', 'role', null], ['role_id' => 8]])->all();
            $users = array_filter($users, function($user){
                $role = explode(',', $user->role);
                return in_array(\Yii::$app->user->getId(), $role);
            });
            $usersPks = \yii\helpers\ArrayHelper::getColumn($users, 'id');

            $dataProvider->query->andWhere(['flight.user_id' => null, 'flight.is_order' => true]);

        } else {

            $users = \app\models\User::find()->where(['role_id' => 8])->all();
            $users = array_filter($users, function($user){
                if($user->role == null){
                    return true;
                }
                $role = explode(',', $user->role);
                $userRole = explode(',', \Yii::$app->user->identity->role);


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

                $dataProvider->query->andWhere(['flight.created_by' => $usersPks, 'flight.is_order' => true]);
            } else {
                $dataProvider->query->andWhere(['flight.created_by' => null, 'flight.is_order' => true]);
            }
        }

        $dataProvider->query->andWhere(['status' => \app\models\Flight::STATUS_SEARCHING]);

        return $this->render('@app/views/flight/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
