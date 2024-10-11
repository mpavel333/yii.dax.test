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
use app\components\MyCache;

use app\components\Ati;


/**
 * FlightController implements the CRUD actions for Flight model.
 */
class FlightController extends Controller
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
        // ini_set('memory_limit', -1);
        $searchModel = new FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        $dataProvider->query->andWhere(['or', ['flight.is_order' => false], ['and', ['flight.is_order' => true], ['is not', 'flight.user_id', null]]]);
        $dataProvider->query->andWhere(['is_metal' => false]);

        //////////// 
        // нумерация с обнулением с начала каждого года
        $counter = clone $dataProvider;
        // $counter->pagination = false;
        $counter->setSort([
          'defaultOrder' => ['created_at'=>SORT_ASC],
        ]);
        $years = [];
        $numeration = [];
        $c = 1;

        $models = array_reverse($counter->models);
        

      
        foreach ($models as $key=>$model){

            if(\Yii::$app->user->identity->can('flight_view_all')==false && $model->user_id!=\Yii::$app->user->id){
                continue;
            }            
            
            if($model->date_cr):
                $year = date('Y',strtotime($model->date_cr));
            elseif($model->date):
                $year = date('Y',strtotime($model->date));
            elseif($model->created_at):
                $year = date('Y',strtotime($model->created_at));
           // else:
            //    continue;
            endif;

            if(!in_array($year,$years)){
                $years[]=$year;
                $c = 1;
            }
                
            $numeration[$model->id]=[$c,$year];
            $c++;

        }
        /////////////////

        $dataProvider->query->limit(1);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'numeration' => $numeration
        ]);
    }

    
    public function actionCalculateSalary()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $model = new Flight();

        $model->load($request->post());

        $salaryModel = \app\models\Salary::find()->one();
        $createdBy = \app\models\User::findOne($model->created_by);
        
        if($model->countSalary && $salaryModel && is_numeric($model->we) && is_numeric($salaryModel->percent_with_nds) && is_numeric($salaryModel->percent_with_nds) && is_numeric($model->recoil) && is_numeric($model->recoil) && $createdBy){
            Yii::warning('inSalary', 'inSalary');
            $delta = 0;
            $salary = 0;
            $a1 = (100 - $salaryModel->percent)/100;
            $a2 = $salaryModel->percent_with_nds/100 + 1;
            $a3 = (100-$salaryModel->withdraw)/100;
            $percentSalary = 0;
            $we = $model->we;
            if($createdBy){
                $percentSalary = $createdBy->percent_salary;
                $percentSalary = explode('/', $percentSalary);
            }
            Yii::warning($model->pay_us, 'pay_us');
            Yii::warning($model->otherwise2, 'otherwise2');
            
            if($model->is_insurance){
                $result = ($model->name_price / 100) * 0.1;
                $we = $we - $result;
            }

            if($createdBy->percent == \app\models\User::PERCENT_CLASSIC || $createdBy->percent == \app\models\User::PERCENT_DYNAMIC || $createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if($model->pay_us == 'с НДС' && $model->otherwise2 == 'без НДС'){
                    $delta = ($we * $a1 - ($model->payment_out + $model->payment_out * $salaryModel->percent_with_nds / (100 - $salaryModel->percent_with_nds))) * $a3 - $model->recoil;
                } elseif($model->pay_us == 'без НДС' && $model->otherwise2 == 'без НДС') {
                    $delta = ((($we * $a1) - $model->payment_out) * $a3) - $model->recoil;
                } elseif($model->pay_us == 'на карту' && $model->otherwise2 == 'на карту') {
                    $delta = ($we - $model->payment_out) - $model->recoil;
                } elseif($model->pay_us == 'без НДС' && $model->otherwise2 == 'на карту') {
                    $delta = ((($we * $a1) - $model->payment_out) * $a3) - $model->recoil;
                } elseif($model->pay_us == 'с НДС' && $model->otherwise2 == 'с НДС') {
                    $delta = ((($we * $a1) - $model->payment_out) * $a3) - $model->recoil;
                } elseif($model->pay_us == 'с НДС' && $model->otherwise2 == 'на карту') {
                    $delta = ($we * $a1) * $a3 - $model->payment_out - $model->recoil;
                } elseif($model->pay_us == 'Наличными' && $model->otherwise2 == 'Наличными') {
                    $delta = $we - $model->payment_out;
                }
            } elseif($createdBy->percent == \app\models\User::PERCENT_PERCENT) {
                $delta = ($we - $model->payment_out) - $model->recoil;
            }
            

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if($model->pay_us == 'без НДС' && $model->otherwise2 == 'без НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($model->pay_us == 'с НДС' && $model->otherwise2 == 'без НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($model->pay_us == 'без НДС' && $model->otherwise2 == 'с НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($model->pay_us == 'с НДС' && $model->otherwise2 == 'c НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($model->pay_us == 'Наличными' && $model->otherwise2 == 'Наличными'){
                    $percentSalary = 50;
                }
                if($model->pay_us == 'на карту' && $model->otherwise2 == 'на карту'){
                    $percentSalary = 50;
                }
                $delta = ($we - $model->payment_out) - $model->recoil;
            }

            if($createdBy->percent == \app\models\User::PERCENT_DYNAMIC){
                $start = date('Y-m-01');
                $end = date('Y-m-t');
                $flights = self::find()->where(['created_by' => $model->created_by])->andWhere(['between', 'date', $start, $end])->all();
                $delta2 = array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'we')); - array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'payment_out'));
                if($delta2 < 100000){
                    $percentSalary = 45; 
                } elseif($delta2 > 100000 && $delta2 <= 200000) {
                    $percentSalary = 50; 
                } elseif($delta2 > 200000 && $delta2 <= 300000) {
                    $percentSalary = 55;
                } elseif($delta2 > 300000) {
                    $percentSalary = 60;
                }
            }

            if(is_array($percentSalary)){
                $percentSalary = $percentSalary[0];
            }

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if(is_numeric($model->additional_credit) && is_numeric($percentSalary)){
                    $salary = ($model->we - $model->payment_out - $model->recoil - $model->additional_credit) / 100 * $percentSalary;
                } else {
                    $salary = 0;
                }
            } else {
                $percentSalary = $createdBy->percent_salary; //
                if($percentSalary == null){
                    $percentSalary = 50;
                }
                \Yii::warning("({$delta} / 100) * {$percentSalary}", 'calc');
                if(is_numeric($delta) && is_numeric($percentSalary)){
                    $salary = ($delta / 100) * $percentSalary; 
                }
            }

            Yii::warning($delta, 'delta');
            Yii::warning($salary, 'salary');
            $model->delta = $delta;
            $model->salary = $salary;
        }

        if($model->created_at == null && $model->is_register){
            $model->created_at = date('Y-m-d H:i:s');
        }

        if(\Yii::$app->controller->module->id == 'mobile'){
            $datesAttrs = ['date', 'date_out2', 'date_out3', 'date_out4', 'date_out5', 'date_out6', 'date_cr', 'date2', 'date3', 'date_cr_check', 'act_date', 'shipping_date'];
            foreach($datesAttrs as $attr)
            {
                if($model->$attr)
                {
                    $dateValue = $model->$attr;
                    if($model->validateDate($dateValue) == false){
                        $dateValueArr = array_reverse(explode('-', $dateValue));
                        if(strlen($dateValueArr[2]) == 1){
                            $dateValueArr[2] = '0'.$dateValueArr[2];
                        }
                        $model->$attr = implode('-', $dateValueArr);
                    }
                }
            }
        }

            if($model->created_by && $model->isNewRecord){
                $createdBy = \app\models\User::findOne($model->created_by);
                if($createdBy)
                {
                    if($model->daks_balance){
                        $createdBy->daks_balance = doubleval($createdBy->daks_balance) - $model->daks_balance;
                        $model->we = $model->we - $model->daks_balance;
                    } else {
                        $flightDaks = doubleval($model->we) * 0.01;
                        $createdBy->daks_balance = doubleval($createdBy->daks_balance) + $flightDaks;
                    }
                    $createdBy->save(false);
                }
            }

        if($model->old_information_file_path != $model->information_file_path && $model->old_information_file_path == null){

            $url = \yii\helpers\Url::toRoute($model->information_file_path, true);
            \Yii::warning($url, 'information url');
            $jsonResponse = json_decode(file_get_contents('http://188.225.38.17:9011/link/https://bl.teo-app.ru/test.ogg'), true);

            \Yii::warning($jsonResponse, '$jsonResponse');

            if(isset($jsonResponse['text'])){
                $model->information = $jsonResponse['text'];
            }
        }

        if($model->is_register && $model->is_register_old != $model->is_register){
            $model->status = 1;
            $model->user_id = \Yii::$app->user->getId();
        }

        return ['salary' => $model->salary];
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
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

    public function actionSessionToggleFilters($state)
    {
        $session = \Yii::$app->session;

        $session->set('flight-index-filters-open', (boolean) $state);
    }

    public function actionTest()
    {
        /*
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
        */
    }
    

    public function actionSetIndex()
    {
    	$index = 1;
    	foreach(Flight::find()->all() as $flight)
    	{
    		Flight::updateAll(['index' => $index], ['id' => $flight->id]);
    		$index++;
    	}
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
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

    public function actionUpdDownload($id)
    {
        $model = $this->findModel($id);

        $html = $this->renderPartial('upd_pdf', [
            'model' => $model,
        ]);

        $myfile = fopen("upd.xml", "w") or die("Unable to open file!");
        fwrite($myfile, $html);
        fclose($myfile);

        \Yii::$app->response->sendFile("upd.xml");
    }

    public function actionUpdDoc($id)
    {
        $claim = Flight::findOne($id);
        $template_id = 16;
        $signature = null;

        $template = Template::findOne($template_id);

        $content = $template->text;

        if($template->modifier == 'UPD'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = Template::findOne(17);
            $templContent = $claim->tags($templ->text);

            $content .= '<div id="schet" style="">'.$templContent.'</div><div style="position: absolute;top: 1220px;left: 88px;">М.П.</div>
<div style="position: absolute; top: 1220px; right: 750px;">М.П.</div><div style="position: absolute; top: 2550px; left: 50px;">М.П.</div>
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

    public function actionSignature($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        if($model->is_signature == 0 || $model->is_driver_signature == 0){
            $model->is_signature = 1;
            $model->is_driver_signature = 1;
        } else {
            $model->is_signature = 0;
            $model->is_driver_signature = 0;
        } 

        $model->save(false);

        return ['forceClose'=>true,'forceReload'=>'#crud-datatable-flight-pjax'];
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

        $validate = !\Yii::$app->user->identity->can('flight_disable_validation');


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
                         
            }else if($model->load($request->post()) && $model->save($validate)){

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

    /**
     * Creates a new Flight model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionHistory($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        
        return [
            'title'=> "История изменений",
            'content'=>$this->renderAjax('history', [
                'id' => $id,
                'model' => $model,
            ]),
            'footer'=> '',

        ];
    }


        /**
     * Creates a new Flight model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionChatUsers($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $allMessages = \app\models\Message::find()->where(['flight_id' => $id])->all();
        $userPks = array_unique(\yii\helpers\ArrayHelper::merge(
            \yii\helpers\ArrayHelper::getColumn($allMessages, 'user_id'),
            \yii\helpers\ArrayHelper::getColumn($allMessages, 'user_to_id')
        ));

        $users = \app\models\User::find()
            ->where(['in', 'id', \yii\helpers\ArrayHelper::merge([$model->created_by, $model->user_id], $userPks)])
            ->all();

        return [
            'title'=> "Чат #{$model->upd}",
            'content'=>$this->renderAjax('chat-users', [
                'model' => $model,
                'users' => $users,
            ]),
            'footer'=> Html::button('Закрыть',['class'=>'btn btn-default btn-block','data-dismiss'=>"modal"]),
        ];
    }

        /**
     * Creates a new Flight model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionChat($id, $user)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $user = \app\models\User::findOne($user);

        $messages = \app\models\Message::find()
            ->where(['flight_id' => $id])
            ->andWhere([
                'or',
                ['and', ['user_id' => \Yii::$app->user->getId(), 'user_to_id' => $user->id]],
                ['and', ['user_id' => $user->id, 'user_to_id' => \Yii::$app->user->getId()]],
            ])
            ->all();

        \app\models\Message::updateAll([
            'is_read' => true,
        ], ['user_id' => $user->id, 'user_to_id' => \Yii::$app->user->getId()]);

        return [
            'title'=> "Чат #{$model->upd} | {$user->name}",
            'content'=>$this->renderAjax('chat', [
                'model' => $model,
                'user' => $user,
                'messages' => $messages,
            ]),
            'footer'=> Html::button('Закрыть',['class'=>'btn btn-default btn-block','data-dismiss'=>"modal"]),
        ];
    }
    
    
    public function actionChatDelete($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $message = \app\models\Message::findOne($id);
        
        if($message->user_id == \Yii::$app->user->getId()){
            $message->user_id_delete = true;
        }elseif($message->user_to_id == \Yii::$app->user->getId()){
            $message->user_to_id_delete = true;
        }
        
        $message->save();
        
        
        return ['forceClose'=>true,'forceReload'=>'#crud-datatable-message-pjax'];

    }    
    
    

    public function actionLoadMessages($flight_id, $user_id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $user = \app\models\User::findOne($user_id);

        \app\models\Message::updateAll([
            'is_read' => true,
        ], ['flight_id' => $flight_id, 'user_id' => $user_id, 'user_to_id' => \Yii::$app->user->getId()]);

        $messages = \app\models\Message::find()
            ->where(['flight_id' => $flight_id])
            ->andWhere([
                'or',
                ['and', ['user_id' => \Yii::$app->user->getId(), 'user_to_id' => $user_id]],
                ['and', ['user_id' => $user_id, 'user_to_id' => \Yii::$app->user->getId()]],
            ])
            ->all();
        
        return ['html' => $this->renderPartial("messages-container", [
            'user' => $user,
            'messages' => $messages,
        ])];
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

        $validate = !\Yii::$app->user->identity->can('flight_disable_validation');

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
            }else if($model->load($request->post()) && $model->save($validate)){
                return [
                    'forceReload'=>'#crud-datatable-flight-pjax',
                    'forceClose' => true,
                ];    
            }else{

                if($request->isPost){

                    $model->save(false);

                }

                \Yii::warning($model->errors, '$errors');
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


    public function actionUpdateFiles($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        $validate = !\Yii::$app->user->identity->can('flight_disable_validation');

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Загрузка файлов #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Отмена',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Сохранить',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save($validate)){
                return [
                    'forceReload'=>'#crud-datatable-flight-pjax',
                    'forceClose' => true,
                ];    
            }else{

                if($request->isPost){

                    $model->save(false);

                }

                \Yii::warning($model->errors, '$errors');
                 return [
                    'title'=> "Загрузка файлов #".$id,
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


    public function actionZipXmlDownload($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        $filesList = [];

        // <Счёт на оплату>
        $xml = $this->renderPartial('xml/invoice', [
            'model' => $model,
        ]);

        $xml = iconv('utf-8//IGNORE', 'windows-1251//IGNORE', $xml);

        $myfile = fopen("schet_na_oplatu.xml", "w") or die("Unable to open file!");
        fwrite($myfile, $xml);
        fclose($myfile);
        $filesList['schet_na_oplatu.xml'] = "schet_na_oplatu.xml";
        // </Счёт на оплату>


        // <УПД>
        $idSender = trim(\yii\helpers\ArrayHelper::getValue($model, 'organization.inn'))."_".trim(\yii\helpers\ArrayHelper::getValue($model, 'organization.kpp'));
        $idReceiver = trim(\yii\helpers\ArrayHelper::getValue($model, 'zakazchik.inn'))."_".trim(\yii\helpers\ArrayHelper::getValue($model, 'zakazchik.kpp'));

        $fileId = "ON_NSCHFDOPPR_".$idReceiver.'_'.$idSender.'_'.date('Ymd').'_'.$this->guid();
        $filename = $fileId.'.xml';

        $statementGuid = $this->guid();
        $oneCGuid = $this->guid(); 

        if(\yii\helpers\ArrayHelper::getValue($model, 'organization.nds')){
            $we = $model->we;
            if(is_numeric($we) == false){
                $we = 0;
            }
            $taxNds = round($we * 20 / 120, 2);
            $weNoNds = round(($we - $taxNds), 2);

            $goodAttributes = 'ЦенаТов="'.$weNoNds.'" СтТовБезНДС="'.$weNoNds.'" НалСт="20%" СтТовУчНал="'.$we.'"';
        } else {
            $we = $model->we;
            if(is_numeric($we) == false){
                $we = 0;
            }
            $taxNds = null;
            $weNoNds = $we;

            $goodAttributes = 'ЦенаТов="'.$we.'" СтТовБезНДС="'.$we.'" НалСт="без НДС" СтТовУчНал="'.$we.'"';
        }

        $xml = $this->renderPartial('xml/upd', [
            'model' => $model,
            'fileId' => $fileId,
            'idSender' => $idSender,
            'idReceiver' => $idReceiver,
            'statementGuid' => $statementGuid,
            'goodAttributes' => $goodAttributes,
            'taxNds' => $taxNds,
            'oneCGuid' => $oneCGuid,
            'weNoNds' => $weNoNds,
            'we' => $we,
        ]);

        $xml = iconv('utf-8//IGNORE', 'windows-1251//IGNORE', $xml);


        if(is_dir('upd_xml') == false){
            mkdir('upd_xml');
        } 

        $myfile = fopen("upd_xml/{$filename}", "w") or die("Unable to open file!");
        fwrite($myfile, $xml);
        fclose($myfile);
        $filesList[$filename] = "upd_xml/{$filename}";
        // </УПД>

        /*
        if(file_exists("uploads/{$model->number}.zip")){
            unlink("uploads/{$model->number}.zip");
        }

        $zip = new \ZipArchive();
        if ($zip->open("uploads/{$model->number}.zip", \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create a zip file');
        }

        \Yii::warning($filesList, '$filesList');

        foreach($filesList as $name => $path){
            $zip->addFile($path, $name);
        }

        $zip->close();

        \Yii::$app->response->sendFile("uploads/{$model->number}.zip");
        */

        //\Yii::$app->response->sendFile('schet_na_oplatu.xml')->send();
        //\Yii::$app->response->sendFile('upd_xml/'.$filename)->send();


         return ['filesList' => $filesList];
    }

    private function guid()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
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

            $image_ext = [ 'jpeg', 'jpg', 'gif', 'png' ];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            
            unlink($path);

            if(in_array($ext,$image_ext)){
                $data = file_get_contents($getRes['file']);
                $path = 'data:image/' . $ext . ';base64,' . base64_encode($data);
            }

            \Yii::warning($res, 'yd response');

            return [
                'name' => $file->name,
                'url' => $path,
                'ext' => $ext,
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

    public function actionUpdateChecks($id, $attr, $key, $value)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $value = $value == 'true' ? 1 : 0;
        $model = $this->findModel($id);

        $json = json_decode($model->$attr, true);

        if(is_array($json) == false){
            $json = [];
        }

        \Yii::warning($json, '$json');

        // $json[$key] = $value;
        \yii\helpers\ArrayHelper::setValue($json, "{$key}.value", $value);
        if($value){
            \yii\helpers\ArrayHelper::setValue($json, "{$key}.datetime", date('Y-m-d H:i:s'));
        }else{
            \yii\helpers\ArrayHelper::setValue($json, "{$key}.datetime", null);
        }

        \Yii::warning($json, '$json 2');

        $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
        $docs = explode(',', $role ? $role->docs : '');

        $allChecks = false;
        foreach($docs as $doc){
            if(isset($json[$doc]) && isset($json[$doc]['value']) && $json[$doc]['value']){
                $allChecks = true;
            } else {
                $allChecks = false;
                break;
            }
        }

        $model->$attr = json_encode($json);
        $model->save(false);

        return ['allChecks' => $allChecks];
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

        $model = $this->findModel($id);

        $templates = Template::find()->where(['type' => Template::TYPE_FLIGHT])
                    ->orderBy([ new \yii\db\Expression('ord IS NULL ASC, ord ASC')])
                              
                    ->all();
 
        return [
            'title' => 'Печать',
            'content' => $this->renderAjax('@app/views/_print/print-templates', [
                'templates' => $templates,
                'id' => $id,
                'model' => $model,
            ]),
            'footer' => ''
        ];
    }

    public function actionPrintFromClient($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $templates = Template::find()->where(['type' => Template::TYPE_FLIGHT])->all();

        return [
            'title' => 'Печать',
            'content' => $this->renderAjax('@app/views/_print/print-client-templates', [
                'templates' => $templates,
                'id' => $id,
                'model' => \app\models\Client::findOne($id),
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
                            'forceReload'=>'#crud-datatable-flight-pjax',
                            'title'=> "Загружения",
                            'content'=>"Удачно загруженно: {$success} <br/> Ошибка загрузки: {$error}",
                            'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];
                        // exit;
                        return [
                            'forceReload'=>'#crud-datatable-flight-pjax',
                            'forceClose'=>true,
                        ];   
                    } else {
                        return [
                            'forceReload'=>'#crud-datatable-flight-pjax',
                            'title'=> "Загружения",
                            'content'=>"<span class='text-danger'>Ошибка при загрузке файла</span>",
                            'footer'=> Html::button(\Yii::t('app', 'Закрыть'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];
                    }
                } catch (\Exception $e){
                    \Yii::warning($e->getMessage(), "Error while import");
                        return [
                            'forceReload'=>'#crud-datatable-flight-pjax',
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




    public function actionPrintTemplate($id = null, $template_id = null, $signature = 0, $subject = null)
    {
        $claim = Flight::findOne($id);

        $template = Template::findOne($template_id);

        $content = $template->text;
        
        if($template->modifier == 'PREPAYMENT'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = Template::findOne(30);
            $templContent = $claim->tags($templ->text);

            $content .= '<div id="schet" style="">'.$templContent.'</div><div style="position: absolute;top: 1220px;left: 88px;">М.П.</div>
<div style="position: absolute; top: 1220px; right: 750px;">М.П.</div><div style="position: absolute; top: 2550px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 2550px; right: 750px;">М.П.</div>';

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 16 && $signature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: 30px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -1300px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -2400px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 579px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 1280px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 890px; left: 450px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 1055px; left: 450px; height: 50px;">';

            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $content.$signature;        
        

        } elseif($template->modifier == 'UPD'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = Template::findOne(17);
            $templContent = $claim->tags($templ->text);

            $content .= '<div id="schet" style="">'.$templContent.'</div><div style="position: absolute;top: 1220px;left: 88px;">М.П.</div>
<div style="position: absolute; top: 1220px; right: 750px;">М.П.</div><div style="position: absolute; top: 2550px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 2550px; right: 750px;">М.П.</div>';

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 16 && $signature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: 30px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -1300px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -2400px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 579px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 1280px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 890px; left: 450px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 1055px; left: 450px; height: 50px;">';

            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $content.$signature;

        } elseif($template->modifier == 'TN'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $signature = '';

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 16 && $signature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: 30px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -1300px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -2400px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 579px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 1280px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 890px; left: 450px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 1055px; left: 450px; height: 50px;">';
            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $content.$signature;
        } else {

            $boolSingnature = $signature;

            if(($claim->is_signature && $template_id == 11 && $signature)){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute;bottom: 30px;right: 25px;height: 200px;"><img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute;bottom: -400px;right: 25px;height: 200px;">';
            } else {
                $signature = '';
            }

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 12 && $boolSingnature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute;bottom: 30px;right: 25px;height: 200px;"><img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute;bottom: -1250px;right: 25px;height: 200px;">';
            } else {
                // $signature = '';
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

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 28 && $boolSingnature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: fixed;bottom: 250px;right: 800px;height: 200px;">';
            } else {
                // $signature = '';
            }

            \Yii::warning($signature, '$signature');

            $content = $claim->tags($content).$signature;
        }
        

        return $this->renderPartial('@app/views/_print/print', [
            'content' => $content,
            'subject' => $subject ? $subject : $template->name,
        ]);
    }

    public function actionPrintClientTemplate($id = null, $template_id = null, $signature = 0)
    {
        $client = \app\models\Client::findOne($id);
        $claim = new Flight();
        $claim->printClient = $client;

        $template = Template::findOne($template_id);

        $content = $template->text;

        if($template->modifier == 'UPD'){
            $content = $claim->tags($this->renderFile($template->text, ['model' => $claim]));

            $templ = Template::findOne(17);
            $templContent = $claim->tags($templ->text);

            $content .= '<div id="schet" style="">'.$templContent.'</div><div style="position: absolute;top: 1220px;left: 88px;">М.П.</div>
<div style="position: absolute; top: 1220px; right: 750px;">М.П.</div><div style="position: absolute; top: 2550px; left: 50px;">М.П.</div>
<div style="position: absolute; top: 2550px; right: 750px;">М.П.</div>';

            if($claim->is_signature && $claim->is_driver_signature && $template_id == 16 && $signature){
                $signature = '<img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: 30px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -1300px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.pechat').'" style="position: absolute; bottom: -2400px;right: 1500px;height: 200px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 579px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 651px; left: 1280px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 890px; left: 450px; height: 50px;"> <img src="/'.\yii\helpers\ArrayHelper::getValue($claim, 'organization.signature').'" style="position: absolute; top: 1055px; left: 450px; height: 50px;">';

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

        //print_r($model->attributes); die;

        //$model->attributes['salary'] = '';

        $newModel = new \app\models\Flight($model->attributes);

        

        $newModel->file = null;
        $newModel->file_provider = null;
        $newModel->id = null;
        // $newModel->we = null;
        //$newModel->organization_id = null;
        //$newModel->bank = null;
        //$newModel->pay_us = null;
        //$newModel->payment1 = null;
        //$newModel->col2 = null;
        $newModel->status = null;
        // $newModel->payment_out = null;
        //$newModel->otherwise2 = null;
        //$newModel->otherwise3 = null;
        //$newModel->col1 = null;
        $newModel->fio = "Стандарт";
        $newModel->date = date('Y-m-d');
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
        $newModel->we_prepayment = null;
        $newModel->payment_out_prepayment = null;
        //$newModel->pay_us = null;
        //$newModel->otherwise2 = null;
        $newModel->bill_type_prepayed = null;
        $newModel->date_cr = null;
        // $newModel->number = $setting->flight_index;
        // $newModel->upd = $setting->flight_index;
        $newModel->date2 = null;
        $newModel->track_number = null;
        $newModel->date3 = null;
        $newModel->track_number_driver = null;
        $newModel->is_payed = null;
        $newModel->is_salary_payed = null;
        $newModel->is_ensurance_payment = null;
        $newModel->is_recoil_payment = null;
        $newModel->is_additional_credit_payment = null;
        $newModel->is_driver_payed = null;
        $newModel->checks = null;
        $newModel->checks1 = null;
        $newModel->checks2 = null;
        $newModel->checks3 = null;

        $flightCount = \app\models\Flight::find()->orderBy('id desc')->one();
        if($flightCount){
            $flightCount = $flightCount->index;
        } else {
            $flightCount = 1;
        }
        if(\Yii::$app->user->identity->role){
            $userPks = \yii\helpers\ArrayHelper::getColumn(\app\models\User::find()->where(['role' => \Yii::$app->user->identity->role])->all(), 'id');
            $flightCount = (string) \app\models\Flight::find()->where(['created_by' => $userPks])->andWhere(['>', 'date', date('Y').'-01-01'])->count();

            if(mb_strlen($flightCount) == 1){
                $flightCount = '00'.$flightCount;
            } elseif(mb_strlen($flightCount) == 2) {
                $flightCount = '0'.$flightCount;
            }
            $newModel->order = \Yii::$app->user->identity->role.$flightCount + 1;

        }

        $newModel->index = $flightCount + 1;
        $newModel->we = $model->we;
        $newModel->payment_out = $model->payment_out;

        $newModel->is_we_prepayment = null;
        $newModel->date_we_prepayment = null;

        $newModel->is_payment_out_prepayment = null;
        $newModel->date_payment_out_prepayment = null;

        $newModel->is_payed_date = null;
        $newModel->is_driver_payed_date = null;

        
        $newModel->track_number = null;
        $newModel->date2 = null;
        $newModel->track_number_driver = null;
        $newModel->date3 = null;

        $newModel->date_cr_prepayed = null;
        $newModel->number_prepayed = null;
        $newModel->upd_prepayed = null;
        
        
        $newModel->is_register_letter = null;
        $newModel->is_register_letter_driver = null;

        //print_r($newModel); die;

        $newModel->save(false);
        \app\models\Flight::updateAll(['we' => $model->we, 'payment_out' => $model->payment_out], ['id' => $newModel->id]);

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

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \app\models\Flight::find(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $dataProvider->query->andWhere(['between', 'date_cr', $dateStart, $dateEnd]);
        $dataProvider->pagination = false;
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

        $dataProvider->query->andWhere(['between', 'date_cr', $dateStart, $dateEnd]);
        $dataProvider->pagination = false;
        $columns = require('../views/flight/_export_columns3.php');

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
    	
    	if($attribute == 'date2' or $attribute == 'date3'){
            if($value)
                $value = date('Y-m-d',strtotime($value));
    	}        
        
        
        $model->$attribute = $value;

        \Yii::warning($value, '$value');

    	if($attribute == 'is_payed'){
            if($value == 1):
                $model->is_payed_date = date('Y-m-d H:i:s');
            else:
                $model->is_payed_date = null;
            endif;                   
    	}
    	if($attribute == 'is_driver_payed'){
            if($value == 1):
                $model->is_driver_payed_date = date('Y-m-d H:i:s');
            else:
                $model->is_driver_payed_date = null;
            endif;                   
    	}
    	if($attribute == 'is_recoil_payment'){
            if($value == 1):
                $model->is_recoil_payment_date = date('Y-m-d H:i:s');
            else:
                $model->is_recoil_payment_date = null;
            endif;                
    	}
     	if($attribute == 'we_prepayment'){
            if($value == 1):
                $model->we_prepayment_datetime = date('Y-m-d H:i:s');
            else:
                $model->we_prepayment_datetime = null;
            endif;                 
    	}
    	if($attribute == 'payment_out_prepayment'){
            if($value == 1):
                $model->payment_out_prepayment_datetime = date('Y-m-d H:i:s');
            else:
                $model->payment_out_prepayment_datetime = null;
            endif;                 
    	}
        if($attribute == 'is_we_prepayment'){
            if($value == 1):
                $model->date_we_prepayment = date('Y-m-d H:i:s');
            else:
                $model->date_we_prepayment = null;
            endif;                 
    	}
    	if($attribute == 'is_payment_out_prepayment'){
            if($value == 1):
                $model->date_payment_out_prepayment = date('Y-m-d H:i:s');
            else:
                $model->date_payment_out_prepayment = null;
            endif;                 
    	}
        if($attribute == 'is_salary_payed'){
            if($value == 1):
                $model->is_salary_payed_datetime = date('Y-m-d H:i:s');
            else:
                $model->is_salary_payed_datetime = null;
            endif;
        }

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

        //print_r($pks); die;

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

    public function actionGetByAttr($attr,$value)
    {   
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Flight::find()->where([$attr => $value])->one();
        return $model;
    }

}
