<?php

namespace app\controllers;

use Yii;
use app\behaviors\RoleBehavior;
use app\models\Flight;
use app\models\FlightSearch;
use yii\web\Controller;
use app\models\TemplateMetal;
use app\components\TagsHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * MetalController implements the CRUD actions for Flight model.
 */
class MetalController extends Controller
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
                    'create' => 'metal_create',
                    'update' => 'metal_update',
                    'view' => 'metal_view',
                    'delete' => 'metal_delete',
                    'bulk-delete' => 'metal_delete',
                    'index' => ['metal_view', 'metal_view_all'],
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


        $dataProvider->query->andWhere(['or', ['flight.is_order' => false], ['and', ['flight.is_order' => true], ['is not', 'flight.user_id', null]]]);
        $dataProvider->query->andWhere(['is_metal' => true]);


        //////////// 
        // нумерация с обнулением с начала каждого года
        $counter = clone $dataProvider;
        $counter->pagination = false;
        $counter->setSort([
          'defaultOrder' => ['date'=>SORT_ASC],
        ]);
        $numeration = [];
        $c = 1;

        $models = array_reverse($counter->models);

        foreach ($models as $key=>$model){
          //if($key>0 && !empty($model->date)):
            //if(date('Y',strtotime($model->date)) > date('Y',strtotime($counter->models[$key-1]->date))):
                //$c = 1;  
            //endif;
            $numeration[$model->id]=[$c,$model->date];
            $c++;
          //endif;
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numeration' => $numeration
        ]);
    }

    public function actionCountEnsurance($value, $we, $reverse = 0)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if($reverse == false){
            $result = ($value / 100) * 0.1;
            $we = $we - $result;
        } else {
            $result = ($value / 100) * 0.1;
            $we = $we + $result;
        }
        
        return ['result' => $we];
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
                ->from('flight')
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
     * Lists all Flight models.
     * @return mixed
     */
    public function actionIndex2()
    {    
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionArchive($id)
    {
        $request = \Yii::$app->request;
        $model = $this->findModel($id);
        $model->archive_datetime = date('Y-m-d H:i:s');
        $model->save(false);
    
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-metal-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    public function actionArchiveBack($id)
    {
        $request = \Yii::$app->request;
        $model = $this->findModel($id);
        $model->archive_datetime = null;
        $model->is_register = null;
        $model->is_signature = null;
        $model->is_driver_signature = null;
        $model->save(false);
    
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-metal-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    public function actionApiSend($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $flight = \app\models\Flight::findOne($id);
        $ati = new \app\components\Ati();

        $result = json_decode($ati->addCargo($flight), true);

        return [
            'title'=> "API",
            'content'=>$this->renderAjax('result', [
                'result' => $result,
            ]),
            'footer'=> Html::button('ОК',['class'=>'btn btn-block btn-default','data-dismiss'=>"modal"])

        ]; 
    }


    public function actionTest()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ati = new \app\components\Ati();
        $result = $ati->getContacts(\Yii::$app->user->identity->phone);

        \yii\helpers\VarDumper::dump($result, 10, true);
        exit;

        return [
            'title'=> "API",
            'content'=>$this->renderAjax('result', [
                'result' => $result,
            ]),
            'footer'=> Html::button('ОК',['class'=>'btn btn-block btn-default','data-dismiss'=>"modal"])

        ]; 
    }


    public function actionBulkArchive()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys


        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->archive_datetime = date('Y-m-d H:i:s');
            $model->save(false);
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-metal-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Displays a single Flight model.
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
                    'title'=> "Рейсы #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Изменить',['update', 'id' => $model->id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionUpdDoc($id)
    {
        $claim = Flight::findOne($id);
        $template_id = 16;
        $signature = null;

        $template = TemplateMetal::findOne($template_id);

        $content = $template->text;

        if($template->modifier == 'UPD'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = TemplateMetal::findOne(17);
            $templContent = $claim->tags($templ->text);

            // $content .= $content;

            $content .= '<div id="schet" style="">'.$templContent.'</div><div style="position: absolute; top: 1160px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 1160px; right: 750px;">М.П.</div><div style="position: absolute; top: 2550px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 2550px; right: 750px;">М.П.</div>';

            \Yii::warning($signature, '$signature');

            $content = $content.$signature;

        } else {

            if(($claim->is_signature && $template_id == 11 && $signature) || ($claim->is_driver_signature && $template_id == 12 && $signature)){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 30px;right: 110px;height: 200px;">';
            } else {
                $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $claim->tags($content).$signature;
        }

        return $this->renderPartial('@app/views/_print/print', [
            'content' => $content,
        ]);
    }

    public function actionMap($id)
    {
        $model = \app\models\Flight::findOne($id);
        return $this->render('map', [
            'model' => $model,
        ]);
    }

    public function actionDistance($id)
    {
        return $this->render('distance', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionEditAttr($id, $attr, $value)
    {
        $model = $this->findModel($id);

        $model->countSalary = false;
        $model->$attr = $value;

        $model->save(false);
    }

    public function actionDownloadResource($path)
    {
        $res = \app\components\YandexDisk::download($path);
    
        $path = str_replace('uploads', "TEO disk", $path);

        $extension = explode('.', $path)[1];

        $res = \app\components\YandexDisk::download($path);

        $res = json_decode($res, true);

        if(isset($res['error']) && isset($res['message'])){
            throw new \yii\web\BadRequestHttpException($res['message']);
        }

        $href = $res['href'];

        file_put_contents("tmp.".$extension, fopen($href, 'r'));

        return Yii::$app->response->sendFile('tmp.'.$extension);        
    }

    /**
     * Creates a new Flight model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Flight();
        $model->recoil = 0;
        if(\Yii::$app->user->identity->client_id != null){
            $model->zakazchik_id = \Yii::$app->user->identity->client_id;
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
                    'title'=> "Добавить Рейсы",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Создать',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->validate()){
                $model->is_metal = true;
                $model->save(false);
                return [
                    'forceReload'=>'#crud-datatable-metal-pjax',
                    'title'=> "Добавить Рейсы",
                    'content'=>'<span class="text-success">Создание Рейсов успешно завершено</span>',
                    'footer'=> Html::button('ОК',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Создать еще',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ]; 
        
            }else{           
                return [
                    'title'=> "Добавить Рейсы",
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

    public function actionCheckSession($pks)
    {
        $pks = explode(',', $pks);

        $session = Yii::$app->session;

        $session->set('check-flight-session', $pks);
    }

    /**
     * Updates an existing Flight model.
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
                    'title'=> "Изменить Рейсы #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-metal-pjax',
                    'forceClose' => true,
                ];    
            }else{
                 return [
                    'title'=> "Изменить Рейсы #".$id,
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


    public function actionFindByNumber($number)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $distance = \app\models\Flight::find()->where(['car_number' => $number])->sum('distance');

        return ['distance' => floatval($distance)];
    }
    
   
   public function actionCheck()
   {
		$token = 'AQAAAABgExhwAAdPkKxvAdoneENxtc3YlC3403U';

        // $path = '/TEO disk/'; 
         
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/?path=' . urlencode('/TEO disk/'));
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        \yii\helpers\VarDumper::dump($res, 10, true);
        exit;
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

    public function actionUpdateAttr($id, $attr, $value)
    {
        $model = $this->findModel($id);

        if($attr == 'status' && $value == '1'){
            $model->user_id = \Yii::$app->user->getId();
        }

        $model->$attr = $value;

        $model->save(false);
    }

    public function actionUpdateFileAttr($id, $i, $value)
    {
        $model = $this->findModel($id);

        $file = json_decode($model->file, true);

        if(isset($file[$i])){
            $file[$i]['type'] = $value;
        }

        $model->file = json_encode($file, JSON_UNESCAPED_UNICODE);

        $model->save(false);
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
            \Yii::warning($file, '$file');
            $file = array_filter($file, function($model) use($name){
                if(isset($model['url']) == false){
                    return false;
                }
                \Yii::warning("this: {$model['url']}, founding: {$name}", "Compare image path");
                return $name != $model['url'];
            });
            $model->file = json_encode($file);
            $model->save(false);
        }
        return null;
    }

    public function actionImageDeleteProvider($name, $id = null)
    {
        $path = substr($name, 1);
        if (is_file($path)) {
            unlink($path);
        }

        if($id){
            $model = $this->findModel($id);
            $file = json_decode($model->file_provider, true);
            $file = array_filter($file, function($model) use($name){
                if(isset($model['url']) == false){
                    return false;
                }
                \Yii::warning("this: {$model['url']}, founding: {$name}", "Compare image path");
                return $name != $model['url'];
            });
            $model->file_provider = json_encode($file);
            $model->save(false);
        }
        return null;
    }
    

    public function actionPrint($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $templates = TemplateMetal::find()->where(['type' => TemplateMetal::TYPE_METAL])->all();

        return [
            'title' => 'Печать',
            'content' => $this->renderAjax('@app/views/_print/print-templates', [
                'templates' => $templates,
                'id' => $id,
            ]),
            'footer' => ''
        ];
    }

    public function actionPrintFromClient($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $templates = TemplateMetal::find()->where(['type' => TemplateMetal::TYPE_METAL])->all();

        return [
            'title' => 'Печать',
            'content' => $this->renderAjax('@app/views/_print/print-client-templates', [
                'templates' => $templates,
                'id' => $id,
            ]),
            'footer' => ''
        ];
    }

    public function actionAdd()
    {
        $request=Yii::$app->request;
        $model = new Flight();

        if($request->isAjax){

            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->fileUploading = \yii\web\UploadedFile::getInstance($model, 'fileUploading');
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
                        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                        $nrColumns = ord($highestColumn) - 64;
                        for ($row = 2; $row <= $highestRow; ++ $row) {

                            $bodyTypes = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../data/cars.php'), 'Name', 'TypeId');
                            $loadingTypes = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../data/cars.php'), 'Name', 'Id');
                            $weightTypes = array_combine(array_values(\app\models\Flight::typeWeightLabels()), array_keys(\app\models\Flight::typeWeightLabels()));

                            $newModel = new Flight();
                            $cell = $worksheet->getCellByColumnAndRow(0, $row);
                            $managerPhone = trim($cell->getFormattedValue());
                            $managerPhone = str_replace('+', '', $managerPhone);
                            $managerPhone = str_replace('(', '', $managerPhone);
                            $managerPhone = str_replace(')', '', $managerPhone);
                            $managerPhone = str_replace('-', '', $managerPhone);
                            $managerPhone = str_replace(' ', '', $managerPhone);
                            $users = \app\models\User::find()->all();
                            foreach($users as $user){
                                $phone = $user->phone;

                                $phone = trim($phone);
                                $phone = str_replace('+', '', $phone);
                                $phone = str_replace('(', '', $phone);
                                $phone = str_replace(')', '', $phone);
                                $phone = str_replace('-', '', $phone);
                                $phone = str_replace(' ', '', $phone);

                                if($phone === $managerPhone){
                                    $newModel->user_id = $user->id;
                                    break;
                                }
                            }
                            $cell = $worksheet->getCellByColumnAndRow(1, $row);
                            $newModel->rout = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(2, $row);
                            $newModel->contract_place = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(3, $row);
                            $newModel->date = date('Y-m-d', trim(\PHPExcel_Shared_Date::ExcelToPHP($cell->getFormattedValue())));
                            $cell = $worksheet->getCellByColumnAndRow(4, $row);
                            $newModel->order = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(5, $row);
                            $newModel->address1 = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(6, $row);
                            $newModel->shipping_date = date('Y-m-d', trim(\PHPExcel_Shared_Date::ExcelToPHP($cell->getFormattedValue())));
                            $cell = $worksheet->getCellByColumnAndRow(7, $row);
                            $newModel->address_out4 = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(8, $row);
                            $newModel->date_out4 = date('Y-m-d', trim(\PHPExcel_Shared_Date::ExcelToPHP($cell->getFormattedValue())));
                            $cell = $worksheet->getCellByColumnAndRow(9, $row);
                            $newModel->we = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(10, $row);
                            $newModel->payment_out = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(11, $row);
                            $bodyType = trim($cell->getFormattedValue());
                            $newModel->body_type = \yii\helpers\ArrayHelper::getValue($bodyTypes, $bodyType);
                            $cell = $worksheet->getCellByColumnAndRow(12, $row);
                            $loadingType = trim($cell->getFormattedValue());
                            $newModel->loading_type = \yii\helpers\ArrayHelper::getValue($loadingTypes, $loadingType);
                            $cell = $worksheet->getCellByColumnAndRow(13, $row);
                            $uploadingType = trim($cell->getFormattedValue());
                            $newModel->uploading_type = \yii\helpers\ArrayHelper::getValue($loadingTypes, $uploadingType);
                            $cell = $worksheet->getCellByColumnAndRow(14, $row);
                            $newModel->name = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(15, $row);
                            $newModel->name_price = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(16, $row);
                            $newModel->volume = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(17, $row);
                            $newModel->length = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(18, $row);
                            $newModel->width = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(19, $row);
                            $newModel->height = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(20, $row);
                            $newModel->cargo_weight = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(21, $row);
                            $typeWeight = trim($cell->getFormattedValue());
                            $newModel->type_weight = \yii\helpers\ArrayHelper::getValue($weightTypes, $typeWeight);
                            $cell = $worksheet->getCellByColumnAndRow(22, $row);
                            $newModel->place_count = trim($cell->getFormattedValue());
                            $cell = $worksheet->getCellByColumnAndRow(23, $row);
                            $newModel->belts_count = trim($cell->getFormattedValue());

                            $newModel->diameter = 10;
                            $newModel->status = \app\models\Flight::STATUS_SEARCHING;


                            $ati = new \app\components\Ati();
                            $result = json_decode($ati->addCargo($newModel), true);



                            if (!$newModel->save() && \yii\helpers\ArrayHelper::getValue($result, 'cargo_application.cargo_id') === null) {
                                $error++;
                            } else {
                                $success++;
                            }
                        }
                    }

                        return [
                            'forceReload'=>'#crud-datatable-metal-pjax',
                            'title'=> "Загружения",
                            'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                            'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];
                        // exit;
                        return [
                            'forceReload'=>'#crud-datatable-metal-pjax',
                            'forceClose'=>true,
                        ];   
                    } else {
                        return [
                            'forceReload'=>'#crud-datatable-metal-pjax',
                            'title'=> "Загружения",
                            'content'=>"<span class='text-danger'>Ошибка при загрузке файла</span>",
                            'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];
                    }
                } catch (\Exception $e){
                    \Yii::warning($e->getMessage(), "Error while import");
                        return [
                            'forceReload'=>'#crud-datatable-metal-pjax',
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

    public function actionPrintPdf($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => 'Скачать PDF',
            'content' => $this->renderAjax('@app/views/_print/print-pdf', [
                'id' => $id,
            ]),
            'footer' => ''
        ];
    }

    public function actionPrintJpg($id = null, $template_id = null, $signature = 0)
    {
        self::delTree('pdf_jpg');
        mkdir('pdf_jpg');

        exec("xvfb-run wkhtmltopdf -O landscape http://194.67.110.144/flight/print-template/{$id}/{$template_id}/{$signature} output.pdf && pdftoppm -jpeg -r 600 -f 1 output.pdf pdf_jpg/output");

        // Get real path for our folder
        $rootPath = realpath('pdf_jpg');

        // Initialize archive object
        $zip = new \ZipArchive();
        $zip->open('file.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        \Yii::$app->response->sendFile('file.zip');
    }

    public static function delTree($dir) {

       $files = array_diff(scandir($dir), array('.','..'));

        foreach ($files as $file) {

          (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");

        }

        return rmdir($dir);

      }

    public function actionPrintTemplate($id = null, $template_id = null, $signature = 0)
    {
        $claim = Flight::findOne($id);

        $template = TemplateMetal::findOne($template_id);

        $content = $template->text;

        if($template->modifier == 'UPD'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = TemplateMetal::findOne(17);
            $templContent = $claim->tags($templ->text);

            $content .= '<div id="schet" style="">'.$templContent.'</div><div style="position: absolute; top: 1160px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 1160px; right: 750px;">М.П.</div><div style="position: absolute; top: 2550px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 2550px; right: 750px;">М.П.</div>';

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 16 && $signature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: 30px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -1300px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -2400px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 606px; left: 579px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 606px; left: 1280px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 845px; left: 450px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 1010px; left: 450px; height: 50px;">';

            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $content.$signature;

        } else {

            $boolSingnature = $signature;

            if(($claim->is_signature && $template_id == 11 && $signature) || ($claim->is_driver_signature && $template_id == 12 && $signature)){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 30px;right: 25px;height: 200px;">';
            } else {
                $signature = '';
            }

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 20 && $boolSingnature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 30px;right: 25px;height: 200px;">';
            } else {
                // $signature = '';
            }

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 21 && $boolSingnature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 250px;right: 800px;height: 200px;">';
            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $claim->tags($content).$signature;
        }

        return $this->renderPartial('@app/views/_print/print', [
            'content' => $content,
        ]);
    }

    public function actionPrintClientTemplate($id = null, $template_id = null, $signature = 0)
    {
        $client = \app\models\Client::findOne($id);
        $claim = new Flight();
        $claim->printClient = $client;

        $template = TemplateMetal::findOne($template_id);

        $content = $template->text;

        if($template->modifier == 'UPD'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = TemplateMetal::findOne(17);
            $templContent = $claim->tags($templ->text);

            $content .= '<div id="schet" style="">'.$templContent.'</div><div style="position: absolute; top: 1160px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 1160px; right: 750px;">М.П.</div><div style="position: absolute; top: 2550px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 2550px; right: 750px;">М.П.</div>';

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 16 && $signature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: 30px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -1300px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -2400px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 606px; left: 579px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 606px; left: 1280px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 845px; left: 450px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 1010px; left: 450px; height: 50px;">';

            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $content.$signature;

        } else {

            $boolSingnature = $signature;

            if(($claim->is_signature && $template_id == 11 && $signature) || ($claim->is_driver_signature && $template_id == 12 && $signature)){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 30px;right: 25px;height: 200px;">';
            } else {
                $signature = '';
            }

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 20 && $boolSingnature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 30px;right: 25px;height: 200px;">';
            } else {
                // $signature = '';
            }

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 21 && $boolSingnature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 250px;right: 800px;height: 200px;">';
            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $claim->tags($content).$signature;
        }

        return $this->renderPartial('@app/views/_print/print', [
            'content' => $content,
        ]);
    }


    public function actionPrintTemplateMultiple($id = null, $templates = null)
    {
        $claim = Flight::findOne($id);

        $templates = explode(',', $templates);

        $templates = TemplateMetal::find()->where(['id' => $templates])->all();


        $totalContent = '';


        foreach ($templates as $template) {
            $content = $template->text;

            if($template->modifier == 'UPD'){
                $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));
            } else {
                $content = $claim->tags($content);
            }

            $totalContent .= $content;
        }

        return $this->renderPartial('@app/views/_print/print', [
            'content' => $totalContent,
        ]);
    }


    public function actionPrintUpd($id = null, $template_id = null)
    {
        $claim = Flight::findOne($id);

        $content = $this->renderPartial('_upd');

        $content = $claim->tags($content);

        return $this->renderPartial('@app/views/_print/print', [
            'content' => $content,
        ]);
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

        $newModel = new \app\models\Flight($model->attributes);
        $newModel->file = null;
        $newModel->file_provider = null;
        $newModel->id = null;
        // $newModel->we = null;
        $newModel->organization_id = null;
        $newModel->bank = null;
        $newModel->pay_us = null;
        $newModel->payment1 = null;
        $newModel->col2 = null;
        $newModel->status = null;
        // $newModel->payment_out = null;
        $newModel->otherwise2 = null;
        $newModel->otherwise3 = null;
        $newModel->col1 = null;
        $newModel->fio = null;
        $newModel->date = null;
        $newModel->shipping_date = null;
        $newModel->shipping_date_2 = null;
        $newModel->date_out4 = null;
        $newModel->date_out4_2 = null;
        $newModel->date_cr = null;
        $newModel->number = null;
        $newModel->upd = null;
        $newModel->otherwise4 = null;
        $newModel->otherwise = null;
        $newModel->recoil = 0;
        $newModel->your_text = null;
        $newModel->date2 = null;
        $newModel->date3 = null;
        $newModel->name3 = null;
        $newModel->is_register = false;
        $newModel->is_signature = false;
        $newModel->is_driver_signature = false;
        $newModel->created_at = date('Y-m-d H:i:s');
        $newModel->is_driver_payed = false;
        $newModel->is_payed = false;
        // $newModel->is_order = true;
        $newModel->is_order = false;
        $newModel->salary = null;
        $newModel->user_id = null;
        $newModel->status = null;
        $newModel->archive_datetime = null;
        $newModel->save(false);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-metal-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }


    public function actionExport($pks = '')
    {
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $columns = require('../views/metal/_export_columns.php');

        $pks = $pks ? explode(',', $pks) : [];


        $data = [];

        foreach ($columns as $column){

            if(isset($column['label'])){
                $data[0][] = $column['label'];
            } else {
                $data[0][] = isset($column['attribute']) ? (new \app\models\Flight())->getAttributeLabel($column['attribute']) : null;
            }

        }

        foreach ($dataProvider->models as $model) {
            $row = [];
            foreach ($columns as $column) {
                $attribute = $column['attribute'];
                $value = $model->$attribute;
                if(isset($column['value'])){
                    $value = \yii\helpers\ArrayHelper::getValue($model, $column['value']);
                }

                if(isset($column['format'])){
                    $value = Yii::$app->formatter->format($value, $column['format']);
                }


                if(isset($column['content']) && is_callable($column['content'])){
                    $value = call_user_func($column['content'], $model);
                }

                $row[] = $value;
            }
            $data[] = $row;
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $headers = $response->getHeaders();
        $headers->set('Content-Type', "application/vnd.ms-excel; charset=utf-8");
        $headers->set('Content-Transfer-Encoding', 'utf-8');
        $headers->set('Cache-Control', 'public, must-revalidate, max-age=0');
        $headers->set('Pragma', 'public');
        $headers->set('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
        // $headers->set('Content-Disposition', "attachment; filename={$name}.{$type}");
        $headers->set('Content-Disposition', "attachment; filename=Рейсы.xls");

        $content = $this->renderPartial('excel_export', [
            'data' => $data,
        ]);
        return $content;
    }

    public function actionExport2($pks = '')
    {
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $columns = require('../views/metal/_export_columns2.php');

        $pks = $pks ? explode(',', $pks) : [];


        $data = [];

        foreach ($columns as $column){

            if(isset($column['label'])){
                $data[0][] = $column['label'];
            } else {
                $data[0][] = isset($column['attribute']) ? (new \app\models\Flight())->getAttributeLabel($column['attribute']) : null;
            }

        }

        foreach ($dataProvider->models as $model) {
            $row = [];
            foreach ($columns as $column) {
            	$value = null;
            	if(isset($column['attribute'])){
	                $attribute = $column['attribute'];
	                $value = $model->$attribute;
	                if(isset($column['value'])){
	                    $value = \yii\helpers\ArrayHelper::getValue($model, $column['value']);
	                }

	                if(isset($column['format'])){
	                    $value = Yii::$app->formatter->format($value, $column['format']);
	                }


	                if(isset($column['content']) && is_callable($column['content'])){
	                    $value = call_user_func($column['content'], $model);
	                }
            	}


                $row[] = $value;
            }
            $data[] = $row;
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $headers = $response->getHeaders();
        $headers->set('Content-Type', "application/vnd.ms-excel; charset=utf-8");
        $headers->set('Content-Transfer-Encoding', 'utf-8');
        $headers->set('Cache-Control', 'public, must-revalidate, max-age=0');
        $headers->set('Pragma', 'public');
        $headers->set('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
        // $headers->set('Content-Disposition', "attachment; filename={$name}.{$type}");
        $headers->set('Content-Disposition', "attachment; filename=Рейсы.xls");

        $content = $this->renderPartial('excel_export', [
            'data' => $data,
        ]);
        return $content;

    }

    public function actionExport3()
    {
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $columns = require('../views/metal/_export_columns3.php');

        $data = [];

        foreach ($dataProvider->models as $model) {
        //foreach ([$model] as $model) {
                $row = [];
            foreach ($columns as $column) {
            	$value = null;
            	if(isset($column['attribute'])){
	                $attribute = $column['attribute'];
	                $value = $model->$attribute;
	                if(isset($column['value'])){
	                    $value = \yii\helpers\ArrayHelper::getValue($model, $column['value']);
	                }

	                if(isset($column['format'])){
	                    $value = Yii::$app->formatter->format($value, $column['format']);
	                }


	                if(isset($column['content']) && is_callable($column['content'])){
	                    $value = call_user_func($column['content'], $model);
	                }
            	}

            	if($value === null && $value != '0'){
            		$value = '(Не задано)';
            	}

                $row[] = $value;
            }
            $data[] = $row;
        }


        $content = "";

        foreach ($data as $datum) {
        	$row = implode('@', $datum);
        	$content = $row."\n";
        }

		$fp = fopen("Report.txt", "w");
		 

		fwrite($fp, mb_convert_encoding($content, 'windows-1251', 'utf-8'));
		 
		fclose($fp);

        Yii::$app->response->sendFile('Report.txt');
    }

    public function actionExport4()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title'=> "Экспорт",
            'content'=>$this->renderAjax('export4', [
            ]),
            'footer'=> '',
        ]; 
    }


    public function actionExportDownload4($dateStart, $dateEnd)
    {
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['between', 'date', $dateStart, $dateEnd]);
        $dataProvider->pagination = false;

        $columns = require('../views/metal/_export_columns2.php');

        $data = [];

        foreach ($columns as $column){

            if(isset($column['label'])){
                $data[0][] = $column['label'];
            } else {
                $data[0][] = isset($column['attribute']) ? (new \app\models\Flight())->getAttributeLabel($column['attribute']) : null;
            }

        }

        foreach ($dataProvider->models as $model) {
            $row = [];
            foreach ($columns as $column) {
                $attribute = $column['attribute'];
                $value = $model->$attribute;
                if(isset($column['value'])){
                    $value = \yii\helpers\ArrayHelper::getValue($model, $column['value']);
                }

                if(isset($column['format'])){
                    $value = Yii::$app->formatter->format($value, $column['format']);
                }


                if(isset($column['content']) && is_callable($column['content'])){
                    $value = call_user_func($column['content'], $model);
                }

                $row[] = $value;
            }
            $data[] = $row;
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $headers = $response->getHeaders();
        $headers->set('Content-Type', "application/vnd.ms-excel; charset=utf-8");
        $headers->set('Content-Transfer-Encoding', 'utf-8');
        $headers->set('Cache-Control', 'public, must-revalidate, max-age=0');
        $headers->set('Pragma', 'public');
        $headers->set('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
        // $headers->set('Content-Disposition', "attachment; filename={$name}.{$type}");
        $headers->set('Content-Disposition', "attachment; filename=Рейсы.xls");

        $content = $this->renderPartial('excel_export', [
            'data' => $data,
        ]);
        return $content;
    }

    public function actionExport5()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title'=> "Экспорт",
            'content'=>$this->renderAjax('export5', [
            ]),
            'footer'=> '',
        ]; 
    }

    public function actionExportDownload5($dateStart, $dateEnd)
    {
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['between', 'date', $dateStart, $dateEnd]);
        $dataProvider->pagination = false;
        $columns = require('../views/metal/_export_columns3.php');

        $data = [];

        foreach ($columns as $column){

            if(isset($column['label'])){
                $data[0][] = $column['label'];
            } else {
                $data[0][] = isset($column['attribute']) ? (new \app\models\Flight())->getAttributeLabel($column['attribute']) : null;
            }

        }

        foreach ($dataProvider->models as $model) {
            $row = [];
            foreach ($columns as $column) {
                $attribute = $column['attribute'];
                $value = $model->$attribute;
                if(isset($column['value'])){
                    $value = \yii\helpers\ArrayHelper::getValue($model, $column['value']);
                }

                if(isset($column['format'])){
                    $value = Yii::$app->formatter->format($value, $column['format']);
                }


                if(isset($column['content']) && is_callable($column['content'])){
                    $value = call_user_func($column['content'], $model);
                }

                $row[] = $value;
            }
            $data[] = $row;
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $headers = $response->getHeaders();
        $headers->set('Content-Type', "application/vnd.ms-excel; charset=utf-8");
        $headers->set('Content-Transfer-Encoding', 'utf-8');
        $headers->set('Cache-Control', 'public, must-revalidate, max-age=0');
        $headers->set('Pragma', 'public');
        $headers->set('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
        // $headers->set('Content-Disposition', "attachment; filename={$name}.{$type}");
        $headers->set('Content-Disposition', "attachment; filename=Рейсы.xls");

        $content = $this->renderPartial('excel_export', [
            'data' => $data,
        ]);
        return $content;
    }

    public function actionUpdateAjax($id, $attribute, $value)
    {
    	Yii::$app->response->format = Response::FORMAT_JSON;
    	$model = $this->findModel($id);
    	$model->$attribute = $value;
    	$model->save(false);
    	return ['result' => true];
    }

    /**
     * Delete an existing Flight model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-metal-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
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
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-metal-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Flight model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return Flight the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Flight::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }
}
