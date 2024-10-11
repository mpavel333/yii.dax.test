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
 * FlightController implements the CRUD actions for Flight model.
 */
class ArchiveController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'except' => ['application-form', 'map'],
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
        ini_set('memory_limit', -1);
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        $dataProvider->query->andWhere(['is not', 'archive_datetime', null]);
        $dataProvider->query->andWhere(['is_metal' => false]);

        // if(Yii::$app->user->identity->getRoleName() == "Заказчик"){
        // if(Yii::$app->user->identity->isSuperAdmin() == false && \Yii::$app->user->identity->can('flight_archive') == false){
        //     $dataProvider->query->andWhere(['created_by' => Yii::$app->user->getId()]);
        // }
        if(Yii::$app->user->identity->can('flight_btn_update_permament')){
            $dataProvider->query->andWhere(['flight.user_id' => Yii::$app->user->getId()]);
        }

        /*
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        */

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
        /////////////////

        //print_r($numeration); die;

        return $this->render('@app/views/flight/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numeration' => $numeration
        ]);

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
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-flight-pjax',
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
                    'forceReload'=>'#crud-datatable-flight-pjax',
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
            $file = array_filter($file, function($model) use($name){
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

        $templates = Template::find()->where(['type' => Template::TYPE_FLIGHT])->all();

        return [
            'title' => 'Печать',
            'content' => $this->renderAjax('@app/views/_print/print-templates', [
                'templates' => $templates,
                'id' => $id,
            ]),
            'footer' => ''
        ];
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

    public function actionDownloadPdf()
    {
        
    }

    public function actionPrintTemplate($id = null, $template_id = null, $signature = 0)
    {
        $claim = Flight::findOne($id);

        $template = Template::findOne($template_id);

        $content = $template->text;

        if($template->modifier == 'UPD'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = Template::findOne(17);
            $templContent = $claim->tags($templ->text);

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


    public function actionPrintTemplateMultiple($id = null, $templates = null)
    {
        $claim = Flight::findOne($id);

        $templates = explode(',', $templates);
        $templates = Template::find()->where(['id' => $templates])->all();
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
        $newModel->pay_us = null;
        $newModel->payment1 = null;
        $newModel->col2 = null;
        $newModel->status = null;
        // $newModel->payment_out = null;
        $newModel->otherwise2 = null;
        $newModel->otherwise3 = null;
        $newModel->col1 = null;
        $newModel->fio = null;
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
        $newModel->is_order = false;
        $newModel->salary = null;
        $newModel->save(false);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
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

        $columns = require('../views/flight/_export_columns.php');
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

        $columns = require('../views/flight/_export_columns2.php');

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

        $columns = require('../views/flight/_export_columns3.php');
        $data = [];

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

        $columns = require('../views/flight/_export_columns2.php');
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
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
