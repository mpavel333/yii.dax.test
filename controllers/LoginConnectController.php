<?php

namespace app\controllers;

use Yii;
use app\behaviors\RoleBehavior;
use app\models\LoginConnect;
use app\models\LoginConnectSearch;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * LoginConnectController implements the CRUD actions for LoginConnect model.
 */
class LoginConnectController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
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
            'role' => [
                'class' => RoleBehavior::class,
                'instanceQuery' => \app\models\LoginConnect::find(),
                'actions' => [
                    'create' => 'login',
                    'update' => 'login',
                    'view' => 'login',
                    'delete' => 'login',
                    'bulk-delete' => 'login',
                    'index' => ['login', 'login'],
                ],
            ],
        ];
    }
    /**
     * Lists all LoginConnect models.
     * @return mixed
     */
    public function actionIndex()
    {    
        foreach($_SESSION as $key => $value) {
            if(stripos($key, 'form-session')){
                \Yii::$app->session->remove($key);
            }
        }
               
        
        $searchModel = new LoginConnectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single LoginConnect model.
     * 
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> \Yii::t('app', '')." #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Отмена'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a(\Yii::t('app', 'Изменить'),['update', 'id' => $model->id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new LoginConnect model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($pjax = '#crud-datatable-login_connect-pjax', $clouse = false, $atr = null, $value = null) 
    {
        $request = Yii::$app->request;
        $model = new LoginConnect();
        if($request->isGet){
            $model->load(Yii::$app->request->queryParams);
        }


        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($atr != null) {
                $model->$atr = $value;
            }
            if($request->isGet){
 
                return [
                    'title'=>  \Yii::t('app', "Добавить "),
                    'content'=>$this->renderAjax('_form', [
                    'model' => $model,                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Отмена'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(\Yii::t('app', 'Создать'),['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                                if ($clouse) {
                    return [
                        'forceReload'=> $pjax,
                        'forceClose' => true,
                    ];
                } else {
                    return [
                        'forceReload'=> $pjax,
                        'title'=>  \Yii::t('app', "Добавить "),
                        'content'=>'<span class="text-success">'.\Yii::t('app', 'Создание  успешно завершено').'</span>',
                        'footer'=> Html::button(\Yii::t('app', 'ОК'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a(\Yii::t('app', 'Создать еще'),['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            
                    ]; 
                }
                
        
            }else{ 
          
                return [
                    'title'=>  \Yii::t('app', "Добавить "),
                    'content'=>$this->renderAjax('_form', [
                        'model' => $model,                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Отмена'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(\Yii::t('app', \Yii::t('app', 'Создать')),['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('_form', [
                    'model' => $model,                ]);
            }
        }
       
    }

    /**
     * Updates an existing LoginConnect model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionUpdate($id, $pjax = '#crud-datatable-login_connect-pjax')
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
 
                return [
                    'title'=> \Yii::t('app', "Изменить  #").$id,
                    'content'=>$this->renderAjax('_form', [
                        'model' => $model,                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Отмена'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(\Yii::t('app', 'Сохранить'),['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){

                return [
                    'forceReload'=> $pjax,
                    'forceClose' => true,
                ];
 
                    
            }else{
 
                 return [
                    'title'=> \Yii::t('app', "Изменить  #").$id,
                    'content'=>$this->renderAjax('_form', [
                        'model' => $model,                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Отмена'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(\Yii::t('app', 'Сохранить'),['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
 
            } else {
                return $this->render('_form', [
                    'model' => $model,                ]);
            }
        }
    }

    public function actionUpdateAttr($id, $attr, $value)
    {
        $model = $this->findModel($id);
        $model->$attr = $value;
        $model->save(false);
    }
    /**
     * Delete an existing LoginConnect model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionDelete($id, $pjax = '#crud-datatable-login_connect-pjax')
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=> $pjax];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

     /**
     * Delete multiple existing LoginConnect model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionBulkDelete($pjax = '#crud-datatable-login_connect-pjax')
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>$pjax];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the LoginConnect model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return LoginConnect the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
                if (($model = LoginConnect::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
        }
}
