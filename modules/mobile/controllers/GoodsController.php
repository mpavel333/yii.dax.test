<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\Goods;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class GoodsController extends Controller
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
                'instanceQuery' => \app\models\Goods::find(),
                'actions' => [
                    'create' => 'goods_create',
                    'update' => 'goods_update',
                    'view' => 'goods_view',
                    'delete' => 'goods_delete',
                    'bulk-delete' => 'goods_delete',
                    'index' => ['goods_view', 'goods_view_all'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new \app\models\GoodsSearch();
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
                "name" => 'aydi',
                'label' => Yii::t('app', 'ID'),
                "type" => "input"
            ],            [
                "name" => 'name',
                'label' => Yii::t('app', 'Название'),
                "type" => "input"
            ],   
            [
                "name" => 'category_id',
                'label' => Yii::t('app', 'Категория'),
                "type" => "books",
                "filter" => "name",
                "url" => "manufacturer/index"
            ],      
            [
                "name" => 'brand_id',
                'label' => Yii::t('app', 'Бренд'),
                "type" => "books",
                "filter" => "name",
                "url" => "brand/index"
            ],      
            [
                "name" => 'menedjer_id',
                'label' => Yii::t('app', 'Менеджер'),
                "type" => "books",
                "filter" => "name",
                "url" => "user/index"
            ],      
            [
                "name" => 'master_id',
                'label' => Yii::t('app', 'Мастер'),
                "type" => "books",
                "filter" => "name",
                "url" => "user/index"
            ],               [
                "name" => 'fault',
                'label' => Yii::t('app', 'Неисправность'),
                "type" => "input"
            ],   
            [
                "name" => 'status_id',
                'label' => Yii::t('app', 'Статус'),
                "type" => "books",
                "filter" => "name",
                "url" => "status/index"
            ],               [
                "name" => 'komplektaciya',
                'label' => Yii::t('app', 'Комплектация'),
                "type" => "input"
            ],   
            [
                "name" => 'buy_price',
                'label' => Yii::t('app', 'Цена закупа'),
                "type" => "number"
            ],      
            [
                "name" => 'price',
                'label' => Yii::t('app', 'Стоимость'),
                "type" => "number"
            ],      
            [
                "name" => 'paid',
                'label' => Yii::t('app', 'Оплачено'),
                "type" => "number"
            ],               [
                "name" => 'kommentarii',
                'label' => Yii::t('app', 'Комментарии'),
                "type" => "input"
            ],            
        ];
    
        $result["index"] = [
            [
                "name" => 'name',
                'label' => Yii::t('app', 'Название'),
                "type" => "input"
            ],   
            [
                "name" => 'category_id',
                'label' => Yii::t('app', 'Категория'),
                "type" => "books",
                "filter" => "name",
                "url" => "manufacturer/index"
            ],      
            [
                "name" => 'brand_id',
                'label' => Yii::t('app', 'Бренд'),
                "type" => "books",
                "filter" => "name",
                "url" => "brand/index"
            ],      
            [
                "name" => 'menedjer_id',
                'label' => Yii::t('app', 'Менеджер'),
                "type" => "books",
                "filter" => "name",
                "url" => "user/index"
            ],      
            [
                "name" => 'master_id',
                'label' => Yii::t('app', 'Мастер'),
                "type" => "books",
                "filter" => "name",
                "url" => "user/index"
            ],               [
                "name" => 'fault',
                'label' => Yii::t('app', 'Неисправность'),
                "type" => "input"
            ],   
            [
                "name" => 'status_id',
                'label' => Yii::t('app', 'Статус'),
                "type" => "books",
                "filter" => "name",
                "url" => "status/index"
            ],      
            [
                "name" => 'buy_price',
                'label' => Yii::t('app', 'Цена закупа'),
                "type" => "number"
            ],      
            [
                "name" => 'price',
                'label' => Yii::t('app', 'Стоимость'),
                "type" => "number"
            ],      
            [
                "name" => 'paid',
                'label' => Yii::t('app', 'Оплачено'),
                "type" => "number"
            ],      
            [
                "name" => 'create_at',
                'label' => Yii::t('app', 'Дата и время'),
                "type" => "datetime"
            ],                
        ];
    

        array_walk($models, function(&$model){
            if ($model->category_id) $model->category_id = $model->category->name;
            if ($model->brand_id) $model->brand_id = $model->brand->name;
            if ($model->menedjer_id) $model->menedjer_id = $model->menedjer->name;
            if ($model->master_id) $model->master_id = $model->master->name;
            if ($model->status_id) $model->status_id = $model->status->name;
            
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
        $model = new Goods();

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
        $model = Goods::findOne($id);

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
        $model = Goods::findOne($id);

        $result["form"] = [            [
                "name" => 'aydi',
                'label' => Yii::t('app', 'ID'),
                "type" => "input"
            ],            [
                "name" => 'name',
                'label' => Yii::t('app', 'Название'),
                "type" => "input"
            ],            [
                "name" => 'category_id',
                'label' => Yii::t('app', 'Категория'),
                "type" => "input"
            ],            [
                "name" => 'brand_id',
                'label' => Yii::t('app', 'Бренд'),
                "type" => "input"
            ],            [
                "name" => 'menedjer_id',
                'label' => Yii::t('app', 'Менеджер'),
                "type" => "input"
            ],            [
                "name" => 'master_id',
                'label' => Yii::t('app', 'Мастер'),
                "type" => "input"
            ],            [
                "name" => 'fault',
                'label' => Yii::t('app', 'Неисправность'),
                "type" => "input"
            ],            [
                "name" => 'status_id',
                'label' => Yii::t('app', 'Статус'),
                "type" => "input"
            ],            [
                "name" => 'komplektaciya',
                'label' => Yii::t('app', 'Комплектация'),
                "type" => "input"
            ],   
            [
                "name" => 'buy_price',
                'label' => Yii::t('app', 'Цена закупа'),
                "type" => "number"
            ],             
            [
                "name" => 'price',
                'label' => Yii::t('app', 'Стоимость'),
                "type" => "number"
            ],             
            [
                "name" => 'paid',
                'label' => Yii::t('app', 'Оплачено'),
                "type" => "number"
            ],                      [
                "name" => 'kommentarii',
                'label' => Yii::t('app', 'Комментарии'),
                "type" => "input"
            ],            
        ];
        
        $result["mini_menu"] = [
            'items' => [
                ['label' => Yii::t('app', 'Заказы'),  'url' => ['/order?tovar_id='.$model->id]],
                ['label' => Yii::t('app', 'Оплатить'),  'url' => ['/pay?tovar_id='.$model->id]],
                ['label' => Yii::t('app', 'Логи'),  'url' => ['/logs?good_id='.$model->id]],
                   
            
        ]];
        $result["data"] = $model;
        return $result;
    }

    public function actionDelete($id)
    {
        $model = Goods::findOne($id);

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