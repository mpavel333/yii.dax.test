<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use app\behaviors\RoleBehavior;
use app\models\Message;
use app\models\MessageSearch;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
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
            // 'role' => [
            //     'class' => RoleBehavior::class,
            //     'instanceQuery' => \app\models\Message::find(),
            //     'actions' => [
            //         'create' => 'message_create',
            //         'update' => 'message_update',
            //         'view' => 'message_view',
            //         'delete' => 'message_delete',
            //         'bulk-delete' => 'message_delete',
            //         'index' => ['message_view', 'message_view_all'],
            //     ],
            // ],
        ];
    }
    /**
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data = [];    
        $messages = \app\models\Message::find()
            ->where([
                'or',
                ['user_id' => \Yii::$app->user->getId()],
                ['user_to_id' => \Yii::$app->user->getId()],
            ])->all();

        foreach($messages as $message)
        {
            
            if(($message->user_id == \Yii::$app->user->getId() && $message->user_id_delete == true) or 
               ($message->user_to_id == \Yii::$app->user->getId() && $message->user_to_id_delete == true)){
                continue;
            } 
            
            $user_id = null;
            if($message->user_id == \Yii::$app->user->getId()){
                $user_id = $message->user_to_id;
            } else {
                $user_id = $message->user_id;
            }

            if(isset($data["{$message->flight_id}-{$user_id}"]) == false){
                $flight = \app\models\Flight::findOne($message->flight_id);
                $user = \app\models\User::findOne($user_id);
                $data["{$message->flight_id}-{$user_id}"] = [
                    'id' => $message->id,
                    'unread_messages' => Message::find()->where(['user_id' => $user_id, 'user_to_id' => \Yii::$app->user->getId()])->andWhere(['is', 'is_read', null])->count(),
                    'flight_id' => $message->flight_id,
                    'user_id' => $user_id,
                    'user_name' => $user->name,
                    'upd' => $flight->upd,
                ];
            }
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'models' => $data,
            'sort' => [
                'defaultOrder' => [
                    'unread_messages' => SORT_ASC,
                ],
            ],
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Message model.
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

    public function actionSend($flight_id, $user_id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $text = isset($_POST['text']) ? $_POST['text'] : null;

        $message = new Message([
            'flight_id' => $flight_id,
            'user_id' => \Yii::$app->user->getId(),
            'user_to_id' => $user_id,
            'text' => $text,
            'create_at' => date('Y-m-d H:i:s'),
        ]);

        return ['result' => $message->save(false)];
    }



    /**
     * Creates a new Message model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($pjax = '#crud-datatable-message-pjax', $clouse = false, $atr = null, $value = null) 
    {
        $request = Yii::$app->request;
        $model = new Message();
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
     * Updates an existing Message model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionUpdate($id, $pjax = '#crud-datatable-message-pjax')
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
    
   
   
   
   
   
   
   
   
   
   
   
   

    public function actionAddOld()
    {
        $request=Yii::$app->request;
        $model = new Message();

        if($request->isAjax){

            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->fileUploading = UploadedFile::getInstance($model, 'fileUploading');
            $error = 0;
            $success = 0;
            if (!empty($model->fileUploading)) {

                try {
                    $filename = 'uploads/'.$model->fileUploading;
                    $model->fileUploading->saveAs($filename);
                    $file = fopen($filename, 'r');
                    if($file) {
                    $Reader = \PHPExcel_IOFactory::createReaderForFile($filename); 
                    $Reader->setReadDataOnly(true); // set this, to not read all excel properties, just data 
                    $objXLS = $Reader->load($filename);
                    foreach ($objXLS->getWorksheetIterator() as $worksheet) {
                        $worksheetTitle     = $worksheet->getTitle();
                        $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                        $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                        $nrColumns = ord($highestColumn) - 64;
                        for ($row = 2; $row <= $highestRow; ++ $row) {

                            //$cell = $worksheet->getCellByColumnAndRow(0, $row);
                            //if (!$cell->getFormattedValue()) {
                             //   continue;
                            //}
                            $newModel = new Message();
                                $cell = $worksheet->getCellByColumnAndRow(0, $row);
                            $newModel->flight_id  = trim($cell->getFormattedValue());
                                    $relatedModel = \app\models\Flight::find()->where(['index' => $newModel->flight_id])->one();
                            if($relatedModel == null){
                                $relatedModel = new \app\models\Flight(['index' => $newModel->flight_id]);
                                $relatedModel->save(false);
                            }
                            $newModel->flight_id = isset($relatedModel->id) ? $relatedModel->id : null;
                                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                            $newModel->text  = trim($cell->getFormattedValue());
                                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                            $newModel->user_id  = trim($cell->getFormattedValue());
                                    $relatedModel = \app\models\User::find()->where(['name' => $newModel->user_id])->one();
                            if($relatedModel == null){
                                $relatedModel = new \app\models\User(['name' => $newModel->user_id]);
                                $relatedModel->save(false);
                            }
                            $newModel->user_id = isset($relatedModel->id) ? $relatedModel->id : null;
                                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                            $newModel->user_to_id  = trim($cell->getFormattedValue());
                                    $relatedModel = \app\models\User::find()->where(['login' => $newModel->user_to_id])->one();
                            if($relatedModel == null){
                                $relatedModel = new \app\models\User(['login' => $newModel->user_to_id]);
                                $relatedModel->save(false);
                            }
                            $newModel->user_to_id = isset($relatedModel->id) ? $relatedModel->id : null;
                                        $cell = $worksheet->getCellByColumnAndRow(4, $row);
                            $newModel->create_at  = trim($cell->getFormattedValue());
                                        $cell = $worksheet->getCellByColumnAndRow(5, $row);
                            $newModel->is_read  = trim($cell->getFormattedValue());
                                        if (!$newModel->save()) {
                                $error++;
                            } else {
                                $success++;
                            }
                        }
                    }

                        return [
                            'forceReload'=>'#crud-datatable-message-pjax',
                            'title'=> "Загружения",
                            'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                            'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];
                        // exit;
                        return [
                            'forceReload'=>'#crud-datatable-message-pjax',
                            'forceClose'=>true,
                        ];   
                    } else {
                        return [
                            'forceReload'=>'#crud-datatable-message-pjax',
                            'title'=> "Загружения",
                            'content'=>"<span class='text-danger'>Ошибка при загрузке файла</span>",
                            'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];
                    }
                } catch (\Exception $e){
                    \Yii::warning($e->getMessage(), "Error while import");
                        return [
                            'forceReload'=>'#crud-datatable-message-pjax',
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


    public function actionAdd()
    {
        $request=Yii::$app->request;
        $model = new Message();

        if($request->isAjax){

            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->fileUploading = UploadedFile::getInstance($model, 'fileUploading');
            $error = 0;
            $success = 0;
            if (!empty($model->fileUploading) || isset($_POST['columns'])) {

                try {

                        if(isset($_POST['columns']) == false){

                            $filename = 'uploads/'.$model->fileUploading;
                            $model->fileUploading->saveAs($filename);

                            return [
                                'forceReload'=>'#crud-datatable-message-pjax',
                                'title'=> "Загружения",
                                'content'=> $this->renderAjax('@app/views/_excel/settings', [
                                    'excelFile' => $filename,
                                    'class' => Message::class,
                                ]),
                                'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).Html::button(\Yii::t('app', 'Сохранить'),['class'=>'btn btn-info','type'=>"submit"]),
                            ];

                        } else {
                            $filename = $_POST['excel_file'];
                            $columns = $_POST['columns'];

                            $file = fopen($filename, 'r');
                            if($file) {
                            $Reader = \PHPExcel_IOFactory::createReaderForFile($filename); 
                            $Reader->setReadDataOnly(true); // set this, to not read all excel properties, just data 
                            $objXLS = $Reader->load($filename);
                            foreach ($objXLS->getWorksheetIterator() as $worksheet) {

                                $worksheetTitle     = $worksheet->getTitle();
                                $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                                $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                                $nrColumns = ord($highestColumn) - 64;
                                for ($row = 2; $row <= $highestRow; ++ $row) {

                                    $newModel = new Message();

                                    foreach ($columns as $index => $attribute) {
                                        if($attribute){
                                            $cell = $worksheet->getCellByColumnAndRow($index, $row);
                                            if(Message::isRelatedAttr($attribute)){
                                                $newModel->$attribute = Message::getAttributeModelId($attribute, trim($cell->getFormattedValue()));
                                            } else {
                                                $newModel->$attribute = trim($cell->getFormattedValue());
                                            }
                                        }
                                    }

                                    if (!$newModel->save()) {
                                        \Yii::warning($newModel->errors, '$newModel->errors');
                                        $error++;
                                    } else {
                                        $success++;
                                    }
                                }

                                break;
                            }

                                return [
                                    'forceReload'=>'#crud-datatable-message-pjax',
                                    'title'=> "Загружения",
                                    'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                                    'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                                ];
                            }
                        }


                        // exit;
                        return [
                            'forceReload'=>'#crud-datatable-message-pjax',
                            'forceClose'=>true,
                        ];   
                    // } else {
                    //     return [
                    //         'forceReload'=>'#crud-datatable-message-pjax',
                    //         'title'=> "Загружения",
                    //         'content'=>"<span class='text-danger'>Ошибка при загрузке файла</span>",
                    //         'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    //     ];
                    // }
                } catch (\Exception $e){
                    \Yii::warning($e->getMessage(), "Error while import");
                        return [
                            'forceReload'=>'#crud-datatable-message-pjax',
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
        $model = new Message();
		$columns = require('../views/message/_export_columns.php');


        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("creater");
        $objPHPExcel->getProperties()->setLastModifiedBy("Middle field");
        $objPHPExcel->getProperties()->setSubject("Subject");
        $objGet = $objPHPExcel->getActiveSheet();

        $i = 0;
        foreach ($columns as $column){

            $label = null;

            if(isset($column['visible'])){
                if($column['visible'] == false){
                    continue;
                }
            }

            if(isset($column['label'])){
                $label = $column['label'];
            } elseif(isset($column['attribute'])) {
                $label = $model->getAttributeLabel($column['attribute']);
            }

            $objGet->setCellValueByColumnAndRow($i, 1 , $label);
            $i++;
        }

        $filename = 'temp.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save('temp.xlsx');

        Yii::$app->response->sendFile('temp.xlsx');
    }
  




    

    public function actionUpdateAttr($id, $attr, $value)
    {
        $model = $this->findModel($id);
        $model->$attr = $value;
        $model->save(false);
    }
    /**
     * Delete an existing Message model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionDelete($id, $pjax = '#crud-datatable-message-pjax')
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

    public function actionData($attr, $q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select("id, {$attr} AS text")
                ->from('message')
                ->where(['like', $attr, $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Message::find($id)->name];
        } else {
            
        }
        return $out;
    }

     /**
     * Delete multiple existing Message model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionBulkDelete($pjax = '#crud-datatable-message-pjax')
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
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
                if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
        }
}
