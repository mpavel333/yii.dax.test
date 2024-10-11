<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use app\behaviors\RoleBehavior;
use app\models\Requisite;
use app\models\RequisiteSearch;
use yii\web\Controller;
use app\models\Template;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * RequisiteController implements the CRUD actions for Requisite model.
 */
class RequisiteController extends Controller
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
                'instanceQuery' => \app\models\Requisite::find(),
                'actions' => [
                    'create' => 'requisite_create',
                    'update' => 'requisite_update',
                    'view' => 'requisite_view',
                    'delete' => 'requisite_delete',
                    'bulk-delete' => 'requisite_delete',
                    'index' => ['requisite_view', 'requisite_view_all'],
                ],
            ],
        ];
    }
    /**
     * Lists all Requisite models.
     * @return mixed
     */
    public function actionIndex()
    {    
        
        $searchModel = new RequisiteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 12;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFindBank($name)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = \app\models\Bank::find()->where(['name' => $name])->one();
        return $model;
    }

    public function actionGetBank($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $model = \app\models\Bank::find()->where(['name' => $model->bank_name])->one();
        return ['id' => $model ? $model->id : null];
    }

    public function actionGetEnsurance($value)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = \app\models\RequisiteEnsurance::find()->where(['condition' => $value])->one();
        return ['model' => $model ? $model : null];
    }

    public function actionGetEnsuranceConditions($value)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = \app\models\RequisiteEnsuranceConditions::find()->where(['id' => $value])->one();
        return ['model' => $model ? $model : null];
    }

    public function actionDeleteEnsuranceConditions($value)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        \app\models\RequisiteEnsuranceConditions::deleteAll(['requisite_ensurance_id' => $value]);
        return ['id'=>$value,'deleteAll' => 'ok'];
    }
    
    public function actionGetEnsuranceById($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = \app\models\RequisiteEnsurance::findOne($id);
        return ['model' => $model ? $model : null];
    }

    public function actionGetEnsuranceByName($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = \app\models\RequisiteEnsurance::findOne($id);
        $conditions = [];

        if($model){
            $items = \app\models\RequisiteEnsurance::find()->where(['name' => $model->name])->all();

            foreach($items as $item)
            {
                if(isset($conditions[$item->condition]) == false && $item->percent){
                    $conditions[$item->condition] = $item->percent;
                }
            }
        }

        return ['model' => $model ? $model : null, 'conditions' => $conditions];
    }

    public function actionData($attr, $q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select("id, {$attr} AS text")
                ->from('requisite')
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


    /**
     * Displays a single Requisite model.
     * 
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = $this->findModel($id);

            $flightSearchModel = new \app\models\FlightSearch();
        $flightDataProvider = $flightSearchModel->search(Yii::$app->request->queryParams);
        $flightDataProvider->query->andWhere(['organization_id' => $id]);

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Реквизиты #".$id,
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

    public function actionBankMap($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $data = '';

        $counter = 1;
        foreach ($model->banksMap() as $value => $label) {
            if($counter == 1){
                $data .= "<option value='{$value}' selected>{$label}</option>";
            } else {
                $data .= "<option value='{$value}'>{$label}</option>";
            }
            $counter++;
        }

        return ['data' => $data];
    }


    public function actionEnsuranceMap($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $data = '<option>Выберите</option>';
        $data2 = '<option>Выберите</option>';
        $data3 = '<option>Выберите</option>';

        foreach (\app\models\RequisiteEnsurance::find()->where(['requisite_id' => $id])->all() as $ensurance) {
            $data .= "<option value='{$ensurance->id}'>{$ensurance->name}</option>";
            $data2 .= "<option value='{$ensurance->id}'>{$ensurance->condition}</option>";
            $data3 .= "<option value='{$ensurance->id}'>{$ensurance->contract}</option>";
        }

        return ['data' => $data, 'data2' => $data2, 'data3' => $data3];
    }
    /**
     * Creates a new Requisite model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Requisite();

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
                    'title'=> "Добавить Реквизиты",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Создать',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-requisite-pjax',
                    'title'=> "Добавить Реквизиты",
                    'content'=>'<span class="text-success">Создание Реквизитов успешно завершено</span>',
                    'footer'=> Html::button('ОК',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Создать еще',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ]; 
        
            }else{           
                return [
                    'title'=> "Добавить Реквизиты",
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
     * Updates an existing Requisite model.
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
                    'title'=> "Изменить Реквизиты #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-requisite-pjax',
                    'forceClose' => true,
                ];    
            }else{
                 return [
                    'title'=> "Изменить Реквизиты #".$id,
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

        $newModel = new \app\models\Requisite($model->attributes);
        $newModel->id = null;
        $newModel->save(false);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-requisite-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }
   
   
    public function actionToggleAttribute($id, $attr)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);
        $model->$attr = $model->$attr == 1 ? 0 : 1;
        $model->save(false);

        return ['result' => $model->$attr];
    }
   
 
    public function actionAdd()
    {
        $request=Yii::$app->request;
        $model = new Requisite();

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
                        $newModel = new Requisite();
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
                        $newModel->fio_buhgaltera  = (string)$cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(13, $row);
                        $newModel->nds  = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(14, $row);
                        $newModel->pechat  = $cell->getValue();
                        if (!$newModel->save()) {
                            $error++;
                        } else {
                            $success++;
                        }
                    }
                }

                    return [
                        'forceReload'=>'#crud-datatable-requisite-pjax',
                        'title'=> "Загружения",
                        'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                        'footer'=> Html::button('Закрыть',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                    // exit;
                    return [
                        'forceReload'=>'#crud-datatable-requisite-pjax',
                        'forceClose'=>true,
                    ];   
                } else {
                    return [
                        'forceReload'=>'#crud-datatable-requisite-pjax',
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
        $model = new Requisite();

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
     * Delete an existing Requisite model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-requisite-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Requisite model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-requisite-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Requisite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return Requisite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requisite::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }
}
