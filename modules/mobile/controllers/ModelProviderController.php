<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\ModelProvider;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class ModelProviderController extends Controller
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
                'instanceQuery' => \app\models\ModelProvider::find(),
                'actions' => [
                    'create' => 'books',
                    'update' => 'books',
                    'view' => 'books',
                    'delete' => 'books',
                    'index' => 'books',
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new \app\models\ModelProviderSearch();
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
                "name" => 'name',
                'label' => Yii::t('app', 'Название'),
                "type" => "input"
            ],   
            [
                "name" => 'producer_id',
                'label' => Yii::t('app', 'Производитель'),
                "type" => "books",
                "filter" => "name",
                "url" => "manufacturer/index"
            ],               
        ];
    
        $result["index"] = [
            [
                "name" => 'name',
                'label' => Yii::t('app', 'Название'),
                "type" => "input"
            ],   
            [
                "name" => 'producer_id',
                'label' => Yii::t('app', 'Производитель'),
                "type" => "books",
                "filter" => "name",
                "url" => "manufacturer/index"
            ],               
        ];
    

        array_walk($models, function(&$model){
            if ($model->producer_id) $model->producer_id = $model->producer->name;
            
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
        $model = new ModelProvider();

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
        $model = ModelProvider::findOne($id);

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
        $model = ModelProvider::findOne($id);

        $result["form"] = [            [
                "name" => 'name',
                'label' => Yii::t('app', 'Название'),
                "type" => "input"
            ],            [
                "name" => 'producer_id',
                'label' => Yii::t('app', 'Производитель'),
                "type" => "input"
            ],            
        ];
        
        $result["mini_menu"] = [
            'items' => [
                   
            
        ]];
        $result["data"] = $model;
        return $result;
    }

    public function actionDelete($id)
    {
        $model = ModelProvider::findOne($id);

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