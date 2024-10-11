<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\Pay;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class PayController extends Controller
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
                'instanceQuery' => \app\models\Pay::find(),
                'actions' => [
                    'create' => 'pay_create',
                    'update' => 'pay_update',
                    'view' => 'pay_view',
                    'delete' => 'pay_delete',
                    'bulk-delete' => 'pay_delete',
                    'index' => ['pay_view', 'pay_view_all'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new \app\models\PaySearch();
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
                "name" => 'box_office_id',
                'label' => Yii::t('app', 'Касса'),
                "type" => "books",
                "filter" => "name",
                "url" => "purse/index"
            ],      
            [
                "name" => 'amounts',
                'label' => Yii::t('app', 'Сумма'),
                "type" => "number"
            ],               
        ];
    
        $result["index"] = [
   
            [
                "name" => 'user_id',
                'label' => Yii::t('app', 'Сотрудник'),
                "type" => "books",
                "filter" => "name",
                "url" => "user/index"
            ],      
            [
                "name" => 'tovar_id',
                'label' => Yii::t('app', 'Товар'),
                "type" => "books",
                "filter" => "name",
                "url" => "goods/index"
            ],      
            [
                "name" => 'order_id',
                'label' => Yii::t('app', 'Заказ'),
                "type" => "books",
                "filter" => "created_at",
                "url" => "order/index"
            ],      
            [
                "name" => 'box_office_id',
                'label' => Yii::t('app', 'Касса'),
                "type" => "books",
                "filter" => "name",
                "url" => "purse/index"
            ],      
            [
                "name" => 'amounts',
                'label' => Yii::t('app', 'Сумма'),
                "type" => "number"
            ],               
        ];
    

        array_walk($models, function(&$model){
            if ($model->box_office_id) $model->box_office_id = $model->boxOffice->name;
            
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
        $model = new Pay();

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
        $model = Pay::findOne($id);

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
        $model = Pay::findOne($id);

        $result["form"] = [            [
                "name" => 'box_office_id',
                'label' => Yii::t('app', 'Касса'),
                "type" => "input"
            ],   
            [
                "name" => 'amounts',
                'label' => Yii::t('app', 'Сумма'),
                "type" => "number"
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
        $model = Pay::findOne($id);

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