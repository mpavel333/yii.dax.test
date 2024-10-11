<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\Flight;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class DriverController extends Controller
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
                'instanceQuery' => \app\models\Driver::find(),
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new \app\models\DriverSearch();
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
                "name" => 'data',
                'label' => Yii::t('app', 'Водитель ФИО'),
                "type" => "text",
            ], 
            [
                "name" => 'driver',
                'label' => Yii::t('app', 'Водитель паспортные данные'),
                "type" => "text",
            ], 
            [
                "name" => 'phone',
                'label' => Yii::t('app', 'Телефон'),
                "type" => "text",
            ],     
            [
                "name" => 'data_avto',
                'label' => Yii::t('app', 'Модель автомобиля'),
                "type" => "text",
            ],
            [
                "name" => 'info',
                'label' => Yii::t('app', 'Информация о водителе'),
                "type" => "text",
            ],
            [
                "name" => 'car_number',
                'label' => Yii::t('app', 'Гос.номер автомобиля'),
                "type" => "text",
            ],
            [
                "name" => 'file',
                'label' => Yii::t('app', 'Файлы'),
                "type" => "dropzone",
            ],
        ];
    
        $flightStatusData = [];


        $result["index"] = [
            [
                "name" => 'data',
                'label' => Yii::t('app', 'Водитель ФИО'),
                "type" => "text",
            ], 
            [
                "name" => 'driver',
                'label' => Yii::t('app', 'Водитель паспортные данные'),
                "type" => "text",
            ],     
            [
                "name" => 'phone',
                'label' => Yii::t('app', 'Телефон'),
                "type" => "text",
            ],
            [
                "name" => 'data_avto',
                'label' => Yii::t('app', 'Модель автомобиля'),
                "type" => "text",
            ],
        ];
    

        array_walk($models, function(&$model){
            $model->user_id = \yii\helpers\ArrayHelper::getValue($model, 'user.name');
            $model->file = json_decode($model->file, true);
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
        $model = new Flight();

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
        $model = Flight::findOne($id);

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
        $model = Flight::findOne($id);

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
        $model = Flight::findOne($id);

        if($model){
            $model->delete();
            return ['success' => true];
        }

        return ['success' => false];
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


            $user = \app\models\User::find()->where(['token' => $token])->one();

            if($user == null){
                Yii::$app->response->format = Response::FORMAT_JSON;
                throw new \yii\web\BadRequestHttpException('Неверный логин или пароль');
            }

            Yii::$app->user->login($user, 0);
        }

        return parent::beforeAction($action);
    }
}