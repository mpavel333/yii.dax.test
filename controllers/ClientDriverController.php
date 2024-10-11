<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use app\behaviors\RoleBehavior;
use app\models\Client;
use app\models\ClientSearch;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientDriverController extends Controller
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
                'instanceQuery' => \app\models\Client::find(),
                'actions' => [
                    'create' => 'client_create',
                    'update' => 'client_update',
                    'view' => 'client_view',
                    'delete' => 'client_delete',
                    'bulk-delete' => 'client_delete',
                    'index' => ['client_view', 'client_view_all'],
                ],
            ],
        ];
    }
    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['type' => Client::TYPE_DRIVER]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Client model.
     * 
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = $this->findModel($id);

            $flightSearchModel = new \app\models\FlightSearch();
        $flightDataProvider = $flightSearchModel->search(Yii::$app->request->queryParams);
        $flightDataProvider->query->andWhere(['carrier_id' => $id]);

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Организации #".$id,
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

    /**
     * Creates a new Client model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Client();
        $model->type = Client::TYPE_DRIVER;
        if(isset($_POST['id'])){
            if($_POST['id']){
                $model = $this->findModel($_POST['id']);
                $usersArr = json_decode($model->users);
                $usersArr[] = strval(\Yii::$app->user->getId());
                $model->users = json_encode($usersArr);
            } else {
                $usersArr = json_decode($model->users);
                $usersArr[] = strval(\Yii::$app->user->getId());
                $model->users = json_encode($usersArr);                
            }
        } else {
            $usersArr = json_decode($model->users);
            $usersArr[] = strval(\Yii::$app->user->getId());
            $model->users = json_encode($usersArr);
        }
        
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
                    'title'=> "Добавить Организации",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Создать',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-client-pjax',
                    'title'=> "Добавить Организации",
                    'content'=>'<span class="text-success">Создание Организаций успешно завершено</span>',
                    'footer'=> Html::button('ОК',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Создать еще',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ]; 
        
            }else{           
                return [
                    'title'=> "Добавить Организации",
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
     * Updates an existing Client model.
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
                    'title'=> "Изменить Организации #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-client-pjax',
                    'forceClose' => true,
                ];    
            }else{
                 return [
                    'title'=> "Изменить Организации #".$id,
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
   

    public function actionAdd()
    {
        $request=Yii::$app->request;
        $model = new Client();

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
                        $newModel = new Client();
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $newModel->name  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $newModel->doljnost_rukovoditelya  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $newModel->fio_polnostyu  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                        $newModel->official_address  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(4, $row);
                        $newModel->bank_name  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(5, $row);
                        $newModel->inn  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(6, $row);
                        $newModel->kpp  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(7, $row);
                        $newModel->ogrn  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(8, $row);
                        $newModel->bic  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(9, $row);
                        $newModel->kr  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(10, $row);
                        $newModel->nomer_rascheta  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(11, $row);
                        $newModel->tel  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(12, $row);
                        $newModel->email  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(13, $row);
                        $newModel->nds  = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(14, $row);
                        $newModel->doc  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(15, $row);
                        $newModel->mailing_address  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(16, $row);
                        $newModel->code  = (string)$cell->getValue();
                        if (!$newModel->save()) {
                            $error++;
                        } else {
                            $success++;
                        }
                    }
                }

                    return [
                        'forceReload'=>'#crud-datatable-client-pjax',
                        'title'=> "Загружения",
                        'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                        'footer'=> Html::button('Закрыть',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                    // exit;
                    return [
                        'forceReload'=>'#crud-datatable-client-pjax',
                        'forceClose'=>true,
                    ];   
                } else {
                    return [
                        'forceReload'=>'#crud-datatable-client-pjax',
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

    public function actionDownloadResource($path)
    {
        $res = \app\components\YandexDisk::download($path);
    
        $path = str_replace('uploads', "TEO disk", $path);

        $extension = explode('.', $path)[1];

        $res = \app\components\YandexDisk::download($path);

        $res = json_decode($res, true);

        $href = $res['href'];

        file_put_contents("tmp.".$extension, fopen($href, 'r'));

        return Yii::$app->response->sendFile('tmp.'.$extension);        
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
        $model = new Client();

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("creater");
        $objPHPExcel->getProperties()->setLastModifiedBy("Middle field");
        $objPHPExcel->getProperties()->setSubject("Subject");
        $objGet = $objPHPExcel->getActiveSheet();

        $i = 0;
        foreach ($model->attributeLabels() as $attr){
            $objGet->setCellValueByColumnAndRow($i, 1 , $attr);
            $i++;
        }

        $filename = 'temp.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save('temp.xlsx');

        Yii::$app->response->sendFile('temp.xlsx');
    }
    

    /**
     * Delete an existing Client model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-client-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }


    public function actionViewAjax($inn)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Client::find()->where(['inn' => $inn])->one();

        return ['model' => $model];
    }

    public function actionImageDelete($name, $id = null)
    {
        $path = substr($name, 1);
        if (is_file($path)) {
            unlink($path);
        }

        if($id){
            $model = $this->findModel($id);
            $file = json_decode($model->file, true);
            $file = array_filter($file, function($model) use($name){
                return $name != $model['url'];
            });
            $model->file = json_encode($file);
            $model->save(false);
        }
        return null;
    }

     /**
     * Delete multiple existing Client model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-client-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }
}
