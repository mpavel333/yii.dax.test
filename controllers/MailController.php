<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use app\behaviors\RoleBehavior;
use app\models\Mail;
use app\models\MailSearch;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * MailController implements the CRUD actions for Mail model.
 */
class MailController extends Controller
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
                'instanceQuery' => \app\models\Mail::find(),
                'actions' => [
                    'create' => 'mail_create',
                    'update' => 'mail_update',
                    'view' => 'mail_view',
                    'delete' => 'mail_delete',
                    'bulk-delete' => 'mail_delete',
                    'index' => ['mail_view', 'mail_view_all'],
                ],
            ],
        ];
    }
    /**
     * Lists all Mail models.
     * @return mixed
     */
    public function actionIndex()
    {    
        
        $searchModel = new MailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['status' => Mail::STATUS_SENT]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mail model.
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
                    'title'=> \Yii::t('app', 'Почта')." #".$id,
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

    public function actionGetByUpd($upd)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = \app\models\Mail::find()->where(['upd' => $upd])->one();

        return ['model' => $model];
    }

    /**
     * Creates a new Mail model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mail();
        $model->status = Mail::STATUS_SENT;

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
                    'title'=> \Yii::t('app', "Добавить Почту"),
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Отмена'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(\Yii::t('app', 'Создать'),['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-mail-pjax',
                    'title'=> \Yii::t('app', "Добавить Почту"),
                    'content'=>'<span class="text-success">'.\Yii::t('app', 'Создание Почты успешно завершено').'</span>',
                    'footer'=> Html::button(\Yii::t('app', 'ОК'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a(\Yii::t('app', 'Создать еще'),['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ]; 
        
            }else{           
                return [
                    'title'=> \Yii::t('app', "Добавить Почту"),
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
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
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mail model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionUpdate($id)
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
                    'title'=> \Yii::t('app', "Изменить Почту #").$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Отмена'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(\Yii::t('app', 'Сохранить'),['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-mail-pjax',
                    'forceClose' => true,
                ];    
            }else{
                 return [
                    'title'=> \Yii::t('app', "Изменить Почту #").$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
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
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
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
                // 'url' => '/img/no-photo.png',
                'type' => null,
                'size' => $size,
                'preview_url' => $getRes['public_url'],
            ];
        }
    }
   
    /**
     * Delete an existing Flight model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionCopy($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $newModel = new \app\models\Mail($model->attributes);
        $newModel->id = null;
        $newModel->save(false);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-mail-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    public function actionAdd()
    {
        $request=Yii::$app->request;
        $model = new Mail();

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
                        $newModel = new Mail();
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $newModel->number  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $newModel->organization_name  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $newModel->from  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                        $newModel->to  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(4, $row);
                        $newModel->track  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(5, $row);
                         if(\PHPExcel_Shared_Date::isDateTime($cell)) {
                            $newModel->when_send  = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($cell->getValue()));
                         }
                        $newModel->status = Mail::STATUS_SENT;
                        \Yii::warning($newModel->when_receive, 'when_receive');
                        if (!$newModel->save()) {
                            \Yii::warning($newModel->errors, '$model->errors');
                            $error++;
                        } else {
                            $success++;
                        }
                    }
                }

                    return [
                        'forceReload'=>'#crud-datatable-mail-pjax',
                        'title'=> "Загружения",
                        'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                        'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];

                    return [
                        'forceReload'=>'#crud-datatable-mail-pjax',
                        'forceClose'=>true,
                    ];   
                } else {
                    return [
                        'forceReload'=>'#crud-datatable-mail-pjax',
                        'title'=> "Загружения",
                        'content'=>"<span class='text-danger'>Ошибка при загрузке файла</span>",
                        'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                }
            } else {
                return [
                    'title'=> "<span class='text-danger'>Выберите файл</span>",
                    'size'=>'normal',
                    'content'=>$this->renderAjax('add', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-primary pull-left','data-dismiss'=>"modal"]).
                                Html::button(\Yii::t('app', 'Сохранить'),['class'=>'btn btn-info','type'=>"submit"])
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
        Yii::$app->response->sendFile('mail_example.xlsx');
    }
    /**
     * Delete an existing Mail model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-mail-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Mail model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-mail-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Mail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return Mail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }
}
