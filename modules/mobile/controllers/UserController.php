<?php

namespace app\modules\mobile\controllers;

use app\models\forms\UploadFileForm;
use app\models\MobileUserFile;
use Yii;
use app\models\MobileUser;
use app\models\PasswordForget;
use app\modules\mobile\models\Login;
use app\modules\api\models\RegisterModel;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use app\models\User;

class UserController extends Controller
{
    /**
     * @var MobileUser
     */
    private $user;

    private $rawData;

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
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new \app\models\UserSearch();
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
                "name" => 'login',
                'label' => Yii::t('app', 'Логин'),
                "type" => "text",
            ],
            [
                "name" => 'official_address',
                'label' => Yii::t('app', 'Должность'),
                "type" => "text",
            ], 
            [
                "name" => 'name',
                'label' => Yii::t('app', 'ФИО'),
                "type" => "text",
            ],     
            [
                "name" => 'phone',
                'label' => Yii::t('app', 'Телефон'),
                "type" => "text",
            ],
            [
                "name" => 'email',
                'label' => Yii::t('app', 'Email'),
                "type" => "text",
            ],
            [
                "name" => 'access',
                'label' => Yii::t('app', 'Доступ'),
                "type" => "check",
            ],
            [
                "name" => 'percent_salary',
                'label' => Yii::t('app', 'Процент зарплаты'),
                "type" => "text",
            ],
            [
                "name" => 'percent',
                'label' => Yii::t('app', 'Процент'),
                "type" => "dropdown",
                "data" => \app\models\User::percentLabels(),
            ],
            [
                "name" => 'role_id',
                'label' => Yii::t('app', 'Роль'),
                "type" => "book",
                "filter" => "name",
                "url" => "role/index",
            ],
            [
                "name" => 'password',
                'label' => Yii::t('app', 'Пароль'),
                "type" => "text",
            ],
        ];
    
        $flightStatusData = [];


        $result["index"] = [
            [
                "name" => 'login',
                'label' => Yii::t('app', 'Логин'),
                "type" => "text",
            ], 
            [
                "name" => 'name',
                'label' => Yii::t('app', 'ФИО'),
                "type" => "text",
            ],     
            [
                "name" => 'phone',
                'label' => Yii::t('app', 'Телефон'),
                "type" => "text",
            ],
            [
                "name" => 'official_address',
                'label' => Yii::t('app', 'Должность'),
                "type" => "text",
            ],
        ];
    

        array_walk($models, function(&$model){
            $model->percent = \yii\helpers\ArrayHelper::getValue(\app\models\User::percentLabels(), $model->percent);
        });


        $result["data"] = $models;
        return $result;
    }


    /**
     * POST
     */
    /*public function actionRegister()
    {
        $request = Yii::$app->request;
        $data = ['model' => $request->post()];
        $model = new RegisterModel();

        if($model->load($data, 'model')) {

            $token = $model->register();

            if($token == null){
                return $model->errors;
            }

            return ['result' => $token];
        }

        return ['result' => false];
    }*/


    public function actionRegister()
    {
        $request = \Yii::$app->request;

        if($request->isPost){
            $name = isset($this->rawData['name']) ? $this->rawData['name'] : null;
            $inn = isset($this->rawData['inn']) ? $this->rawData['inn'] : null;
            $email = isset($this->rawData['email']) ? $this->rawData['email'] : null;
            $password = isset($this->rawData['password']) ? $this->rawData['password'] : null;
            $phone = isset($this->rawData['phone']) ? $this->rawData['phone'] : null;
            $organizationName = isset($this->rawData['organizationName']) ? $this->rawData['organizationName'] : null;

            $client = new \app\models\Client([
                'name' => $organizationName,
                'inn' => $inn,
                'email' => $email,
                'tel' => $phone,
                'type' => \app\models\Client::TYPE_CLIENT,
            ]);
            $client->save(false);

            $roleClient = \app\models\Role::find()->where(['name' => 'Заказчик'])->one();

            $user = new \app\models\User([
            	'name' => $name,
                'login' => $email,
                'password' => $password,
                'role_id' => $roleClient ? $roleClient->id : null,
                'client_id' => $client->id,
                'phone' => $phone,
                'email' => $email,
            ]);
            $user->save(false);


            $login = new Login();

            $login->login = $email;
            $login->password = $password;

            $token = $login->login();

            if($token == null){
                return $login->errors;
            }

            return ['result' => $token];
        }

        return $result;   
    }

    /**
     * POST
     */
    public function actionSetFcmToken()
    {
        $token = $_POST['fcm_token'];

        $this->user->fcm_token = $token;
        $result = $this->user->save(false);

        return ['result' => $result];
    }


    /**
     * POST
     */
    public function actionChangePassword()
    {
        $user = MobileUser::find()->where(['password' => $_POST['oldPassword']])->andWhere(['id' => $this->user->id])->one();

        if($user == null){
            return ['error' => 'Неверный пароль'];
        }

        $user->password = $_POST['newPassword'];
        $result = $user->save(false);

        return ['result' => $result];
    }

    public function actionEdit()
    {
        $request = Yii::$app->request;
        $data = ['model' => $request->post()];
        $model = $this->user;

        if($model->load($data, 'model') && $model->validate())
        {
            $model->save(false);
            return $model;
        } else {
            return $model->errors;
        }
    }

    /**
    * @return mixed
    */
    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $data = ['model' => $this->rawData];
        $model = User::findOne(Yii::$app->user->getId());

        if($model->load($data, 'model') && $model->validate())
        {
            $model->save(false);
            return $model;
        } else {
            return ["error" => $model->errors];
        }

        return ['result' => false];
    }

    public function actionView()
    {
        // $model = User::findOne(Yii::$app->user->identity->id);
        $model = User::find()->where(['id' => Yii::$app->user->identity->id])->asArray()->one();

        $result["lang"] = 
               [ "name" => 'lang',
                'label' => Yii::t('app', 'Язык'),
                "type" => "dropdown",
                "data" => [
                    ["label" => "Английский", "value" => "en",],
                    ["label" => "Русский", "value" => "ru",],
  
                ]
            ];


        $percentLabels = [];

        foreach (\app\models\User::percentLabels() as $key => $label) {
            $percentLabels[] = ['label' => $label, 'value' => $key];
        }

        $result["form"] = [            
            // [
            //     "name" => 'login',
            //     'label' => (new \app\models\User())->getAttributeLabel('login'),
            //     "type" => "input"
            // ],    
            // [
            //     "name" => 'role',
            //     'label' => (new \app\models\User())->getAttributeLabel('role'),
            //     "type" => "input"
            // ],             
            [
                "name" => 'name',
                'label' => (new \app\models\User())->getAttributeLabel('name'),
                "type" => "input"
            ],   
            [
                "name" => 'phone',
                'label' => (new \app\models\User())->getAttributeLabel('phone'),
                "type" => "input"
            ], 
            [
                "name" => 'email',
                'label' => (new \app\models\User())->getAttributeLabel('email'),
                "type" => "input"
            ], 
            [
                "name" => 'access',
                'label' => (new \app\models\User())->getAttributeLabel('access'),
                "type" => "check"
            ], 
            // [
            //     "name" => 'percent_salary',
            //     'label' => (new \app\models\User())->getAttributeLabel('percent_salary'),
            //     "type" => "text"
            // ],
            // [
            //     "name" => 'percent',
            //     'label' => (new \app\models\User())->getAttributeLabel('percent'),
            //     "type" => "dropdown",
            //     'data' => $percentLabels,
            // ], 
            // [
            //     "name" => 'role_id',
            //     'label' => (new \app\models\User())->getAttributeLabel('role_id'),
            //     "type" => "books",
            //     "filter" => "name",
            //     "url" => "role/index"
            // ],  
            // [
            //     "name" => 'password',
            //     'label' => (new \app\models\User())->getAttributeLabel('password'),
            //     "type" => "input"
            // ],
            [
                "name" => 'inn',
                'label' => \Yii::t('app', 'ИНН'),
                "type" => "input"
            ], 
            [
                "name" => 'organizationName',
                'label' => \Yii::t('app', 'Наименование организации'),
                "type" => "input"
            ],  
        ];

        if($model['client_id']){
            $client = \app\models\Client::findOne($model['client_id']);
            if($client){
                $model['inn'] = $client->inn;
                $model['organizationName'] = $client->name;
            }
        }
        

        $result["data"] = $model;

        return $result;
    }

    public function actionForgetPassword($email)
    {
        $form = new \app\models\forms\ForgetPasswordForm(['email' => $email]);

        return ['result' => $form->changePassword(), 'error' => $form->errors];
    }

    /**
     * POST
     */
    public function actionGetToken()
    {
        $request = Yii::$app->request;
        $data = $_POST;
        $model = new Login();

        $model->login = \yii\helpers\ArrayHelper::getValue($data, 'login');
        $model->password = \yii\helpers\ArrayHelper::getValue($data, 'password');

        \Yii::warning($model->login, 'login');
        \Yii::warning($model->password, 'password');

        // if($model->load($data, 'model')) {

            $token = $model->login();

            if($token == null){
                return $model->errors;
            }

            return ['result' => $token];
        // }

        return ['result' => false];
    }

    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;

        $content = file_get_contents('php://input');
        $this->rawData = json_decode($content, true);

        if($action->id != 'login' && $action->id != 'get-token' && $action->id != 'register' && $action->id != 'forget-password')
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