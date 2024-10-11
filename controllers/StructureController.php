<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use app\behaviors\RoleBehavior;
use app\models\Structure;
use app\models\StructureSearch;
use app\models\User;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * StructureController implements the CRUD actions for Structure model.
 */
class StructureController extends Controller
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
                'instanceQuery' => \app\models\Structure::find(),
                'actions' => [
                    //'create' => 'driver_create',
                    //'update' => 'structure_update',
                    //'view' => 'driver_view',
                    //'delete' => 'driver_delete',
                    //'bulk-delete' => 'driver_delete',
                    //'index' => ['driver_view', 'driver_view_all'],
                ],
            ],
        ];
    }
    /**
     * Lists all Structure models.
     * @return mixed
     */
    public function actionIndex()
    {    
        
        $searchModel = new StructureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Structure model.
     * 
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = $this->findModel($id);

            $flightSearchModel = new \app\models\FlightSearch();
        $flightDataProvider = $flightSearchModel->search(Yii::$app->request->queryParams);
        $flightDataProvider->query->andWhere(['driver_id' => $id]);




        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Компания #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
			'flightSearchModel'=>$flightSearchModel,
			'flightDataProvider'=>$flightDataProvider,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Изменить',['update', 'id' => $model->id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
			'flightSearchModel'=>$flightSearchModel,
			'flightDataProvider'=>$flightDataProvider,
            ]);
        }
    }

    public function actionViewAjax($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);

        return $model;
    }

    public function actionUploadFile()
    {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        $fileName = Yii::$app->security->generateRandomString();
        if (is_dir('uploads') == false) {
            mkdir('uploads');
        }
        $uploadPath = 'uploads';
        if (isset($_FILES['file'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $path = $uploadPath . '/' . $fileName . '.' . $file->extension;
            
            $file->saveAs($path);
            $base = log($file->size, 1024);
            $suffixes = array('Bytes', 'Kb', 'Mb', 'Gb', 'Tb');
            $size = round(pow(1024, $base - floor($base)), 2) .' '. $suffixes[floor($base)];

            $commonRes = \app\components\YandexDisk::upload($path);
            $res = $commonRes['res'];
            $getRes = $commonRes['getRes'];

            unlink($path);

            \Yii::warning($res, 'yd response');

            return [
                'name' => $file->name,
                'url' => '/'.$path,
                'type' => null,
                'size' => $size,
                'preview_url' => $getRes['public_url'],
            ];
        }
    }

    /**
     * Creates a new Structure model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Structure();

        if($request->isGet){
            $model->load(Yii::$app->request->queryParams);
        }


        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Добавить Компанию",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Создать',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-structure-pjax',
                    'title'=> "Добавить Водителей",
                    'content'=>'<span class="text-success">Создание Водителей успешно завершено</span>',
                    'footer'=> Html::button('ОК',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Создать еще',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ]; 
        
            }else{           
                return [
                    'title'=> "Добавить Водителей",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Создать',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Structure model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        $model->rows = $model->getStructureUsers()->all();
        //

        if($request->isAjax){
            //
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Изменить Компанию #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-structure-pjax',
                    'forceClose' => true,
                ];    
            }else{
                 return [
                    'title'=> "Изменить Компанию #".$id,
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
    
    public function actionData($attr, $q = null, $id = null, $key = null)
    {
        if($key == null){
            $key = 'id';
        } else {
            $key = "{$key} as id";
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select("{$key}, {$attr} AS text")
                ->from('driver')
                ->where(['like', $attr, $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::find($id)->name];
        } else {
            
        }
        return $out;
    } 
   
     public function actionAdd()
    {
        $request=Yii::$app->request;
        $model = new Structure();

        if($request->isAjax){

            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->fileUploading = UploadedFile::getInstance($model, 'fileUploading');
            $error = 0;
            $success = 0;
            if (!empty($model->fileUploading)) {
                $filename = 'uploads/'.$model->fileUploading;
                $model->fileUploading->saveAs($filename);
                $file = fopen($filename, 'r');
                if($file) {
                $objPHPExcel = PHPExcel_IOFactory::load($filename);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle     = $worksheet->getTitle();
                    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;
                    for ($row = 2; $row <= $highestRow; ++ $row) {

                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        if (!$cell->getValue()) {
                            continue;
                        }
                        $newModel = new Structure();
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $newModel->data  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $newModel->data_avto  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $newModel->phone  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                        $newModel->driver  = $cell->getValue();
                        if (!$newModel->save()) {
                            $error++;
                        } else {
                            $success++;
                        }
                    }
                }

                    return [
                        'forceReload'=>'#crud-datatable-structure-pjax',
                        'title'=> "Загружения",
                        'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                        'footer'=> Html::button('Закрыть',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                    // exit;
                    return [
                        'forceReload'=>'#crud-datatable-structure-pjax',
                        'forceClose'=>true,
                    ];   
                } else {
                    return [
                        'forceReload'=>'#crud-datatable-structure-pjax',
                        'title'=> "Загружения",
                        'content'=>"<span class='text-danger'>Ошибка при загрузке файла</span>",
                        'footer'=> Html::button('Закрыть',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                }
            } else {
                return [
                    'title'=> "<span class='text-danger'>Выберите файл</span>",
                    'size'=>'normal',
                    'content'=>$this->renderAjax('add', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрыть',['class'=>'btn btn-primary pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-info','type'=>"submit"])
                    ];
            }
        }
    }


    /**
     * Temp an existing Nomenclature model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionTemp()
    {
        Yii::$app->response->sendFile('temp_driver.xlsx');
    }
    

    /**
     * Delete an existing Structure model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-structure-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Structure model.
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
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-structure-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    public function actionGetUser($id)
    {   
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (($model = User::findOne($id)) !== null) {
            return ['model'=>$model];
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }

    /**
     * Finds the Structure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return Structure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Structure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }
}
