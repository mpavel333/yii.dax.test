<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\Order;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class OrderController extends Controller
{
    public $rawData;

    private $user;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'role' => [
                'class' => RoleBehavior::class,
                'instanceQuery' => \app\models\Order::find(),
                'actions' => [
                    'create' => 'order_create',
                    'update' => 'order_update',
                    'view' => 'order_view',
                    'delete' => 'order_delete',
                    'bulk-delete' => 'order_delete',
                    'index' => ['order_view', 'order_view_all'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new \app\models\OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $page = isset($_GET['p']) ? $_GET['p'] : 0;

        $dataProvider->pagination->page = $page;

        foreach($_GET as $key => $value){
            if($key == 'token' || $key == 'p'){
                continue;
            }

            $dataProvider->query->andFilterWhere(['like', $key, $value]);
        }

        $models = $dataProvider->models;
        $result["action_left"] = [
            [
                "url" => "/update",
                "icon" => "fa fa-pencil",
                "color" => "#4B78BC"
            ],
            [
                "url" => "/delete",
                "icon" => "fa fa-trash",
                "color" => "#BC4B4B"
            ],
            [
                "url" => "/pay/create",
                "icon" => "fa fa-map",
                "color" => "#BC4B4B"
            ],


        ];
        $result["action_right"] = [
            [
                "url" => "/view",
                "icon" => "fa fa-eye",
                "color" => "#54BC4B"
            ],
        ];
        
        $result["form"] = [
            [
                "name" => 'aydi',
                'label' => Yii::t('app', 'ID'),
                "type" => "input"
            ],   
            [
                "name" => 'tovar_id',
                'label' => Yii::t('app', 'Товар'),
                "type" => "books",
                "filter" => "name",
                "url" => "goods/index"
            ],      
            [
                "name" => 'zakazchik_id',
                'label' => Yii::t('app', 'Заказчик'),
                "type" => "books",
                "filter" => "name",
                "url" => "customer/index"
            ],      
            [
                "name" => 'oplata',
                'label' => Yii::t('app', 'Оплачен'),
                "type" => "check"
            ],               
        ];
    
        $result["index"] = [
   
            [
                "name" => 'tovar_id',
                'label' => Yii::t('app', 'Товар'),
                "type" => "books",
                "filter" => "name",
                "url" => "goods/index"
            ],      
            [
                "name" => 'status_id',
                'label' => Yii::t('app', 'Статус'),
                "type" => "books",
                "filter" => "name",
                "url" => "status-order/index"
            ],      
            [
                "name" => 'zakazchik_id',
                'label' => Yii::t('app', 'Заказчик'),
                "type" => "books",
                "filter" => "name",
                "url" => "customer/index"
            ],      
            [
                "name" => 'oplata',
                'label' => Yii::t('app', 'Оплачен'),
                "type" => "check"
            ],      
            [
                "name" => 'prepayment',
                'label' => Yii::t('app', 'Предоплата'),
                "type" => "number"
            ],      
            [
                "name" => 'who_created_id',
                'label' => Yii::t('app', 'Кто создал'),
                "type" => "books",
                "filter" => "name",
                "url" => "user/index"
            ],      
            [
                "name" => 'created_at',
                'label' => Yii::t('app', 'Дата создания'),
                "type" => "datetime"
            ],       
            [
                "name" => 'date',
                'label' => Yii::t('app', 'Дата и время изменения'),
                "type" => "datetime"
            ],                
        ];
    

        array_walk($models, function(&$model){
            if ($model->tovar_id) $model->tovar_id = $model->tovar->name;
            if ($model->zakazchik_id) $model->zakazchik_id = $model->zakazchik->name;
            
        });
        $result["data"] = $models;
        return $result;
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $data = ['model' => $this->rawData];
        $model = new Order();

        if($model->load($data, 'model') && $model->validate())
        {
            $model->save(false);
            return $model;
        } else {
            return ["error" => $model->errors];
        }

        return ['result' => false];
    }

    /**
    * @return mixed
    */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $data = ['model' => $this->rawData];
        $model = Order::findOne($id);

        if($model->load($data, 'model') && $model->validate())
        {
            $model->save(false);
            return $model;
        } else {
            return ["error" => $model->errors];
        }

        return ['result' => false];
    }

    public function actionView($id)
    {
        $model = Order::findOne($id);

        $result["form"] = [            [
                "name" => 'aydi',
                'label' => Yii::t('app', 'ID'),
                "type" => "input"
            ],            [
                "name" => 'tovar_id',
                'label' => Yii::t('app', 'Товар'),
                "type" => "input"
            ],            [
                "name" => 'zakazchik_id',
                'label' => Yii::t('app', 'Заказчик'),
                "type" => "input"
            ],            [
                "name" => 'oplata',
                'label' => Yii::t('app', 'Оплачен'),
                "type" => "input"
            ],            
        ];
        
        $result["mini_menu"] = [
            'items' => [
                ['label' => Yii::t('app', 'Касса'),  'url' => ['/box-office?order_id='.$model->id]],
                ['label' => Yii::t('app', 'Оплатить'),  'url' => ['/pay?order_id='.$model->id]],
                ['label' => Yii::t('app', 'Логи'),  'url' => ['/logs?order_id='.$model->id]],
                   
            
        ]];
        $result["data"] = $model;
        return $result;
    }

    public function actionDelete($id)
    {
        $model = Order::findOne($id);

        if($model){
            $model->delete();
        }

        return ['success' => true];
    }

    public function actionFile()
    {
        $file = \yii\web\UploadedFile::getInstanceByName("file");

        if($file){
            if(is_dir('uploads') == false){
                mkdir('uploads');
            }

            $path = "uploads/".Yii::$app->security->generateRandomString().'.'.$file->extension;

            $file->saveAs($path);

            return ['link' => $path];
        }

        return ['link' => null];
    }

    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;

        $content = file_get_contents('php://input');
        $this->rawData = json_decode($content, true);

        if($action->id != 'login')
        {
            $token = null;
            if(isset($_GET['token'])){
                $token = $_GET['token'];
            } elseif(isset($this->rawData['token'])){
                $token = $this->rawData['token'];
            }

            if($token == null){
                Yii::$app->response->format = Response::FORMAT_JSON;
                throw new \yii\web\BadRequestHttpException('Токен не указан');
            }

            $token = explode('||', $token);

            $user = \app\models\User::find()->where(['login' => $token[0], 'password_hash' => $token[1]])->one();

            if($user == null){
                Yii::$app->response->format = Response::FORMAT_JSON;
                throw new \yii\web\BadRequestHttpException('Неверный логин или пароль');
            }

            Yii::$app->user->login($user, 0);
        }

        return parent::beforeAction($action);
    }
}