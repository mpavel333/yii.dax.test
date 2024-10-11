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
 * SettingController implements the CRUD actions for Flight model.
 */
class SettingController extends Controller
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
        ];
    }

    /**
     * Lists all Flight models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if(\Yii::$app->user->identity->isSuperAdmin() == false){
    		throw new \yii\web\NotFoundHttpException('Раздел не найден');
    	}

    	$request = \Yii::$app->request;
    	$model = \app\models\Setting::find()->one();

    	if($model == null){
    		$model = new \app\models\Setting(['flight_index' => 1]);
    		$model->save(false);
    	}

    	if($model->load($request->post()) && $model->save()){
    		\Yii::$app->session->setFlash('success', 'Настройки сохранены');
    	}

        return $this->render('index', [
        	'model' => $model,
        ]);
    }
}
