<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\BoxOffice;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class BoxOfficeController extends Controller
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
                'instanceQuery' => \app\models\BoxOffice::find(),
                'actions' => [
                    'create' => 'box_office_create',
                    'update' => 'box_office_update',
                    'view' => 'box_office_view',
                    'delete' => 'box_office_delete',
                    'bulk-delete' => 'box_office_delete',
                    'index' => ['box_office_view', 'box_office_view_all'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new \app\models\BoxOfficeSearch();
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
                "name" => 'where_id',
                'label' => Yii::t('app', 'Куда перемещаем'),
                "type" => "books",
                "filter" => "name",
                "url" => "purse/index"
            ],      
            [
                "name" => 'counterparty_id',
                'label' => Yii::t('app', 'Контрагент'),
                "type" => "books",
                "filter" => "name",
                "url" => "contractor/index"
            ],      
            [
                "name" => 'group_id',
                'label' => Yii::t('app', 'Группа'),
                "type" => "books",
                "filter" => "name",
                "url" => "group/index"
            ],      
            [
                "name" => 'amounts',
                'label' => Yii::t('app', 'Сумма'),
                "type" => "number"
            ],      
            [
                "name" => 'comment',
                'label' => Yii::t('app', 'Комментарий'),
                "type" => "text"
            ],      
            [
                "name" => 'cheque',
                'label' => Yii::t('app', 'Чеки'),
                "type" => "dropzone"
            ],               
        ];
    
        $result["index"] = [
   
            [
                "name" => 'where_id',
                'label' => Yii::t('app', 'Куда перемещаем'),
                "type" => "books",
                "filter" => "name",
                "url" => "purse/index"
            ],      
            [
                "name" => 'counterparty_id',
                'label' => Yii::t('app', 'Контрагент'),
                "type" => "books",
                "filter" => "name",
                "url" => "contractor/index"
            ],      
            [
                "name" => 'box_office_id',
                'label' => Yii::t('app', 'Касса'),
                "type" => "books",
                "filter" => "name",
                "url" => "purse/index"
            ],      
            [
                "name" => 'group_id',
                'label' => Yii::t('app', 'Группа'),
                "type" => "books",
                "filter" => "name",
                "url" => "group/index"
            ],      
            [
                "name" => 'amounts',
                'label' => Yii::t('app', 'Сумма'),
                "type" => "number"
            ],               
            [
                "name" => 'type',
                'label' => Yii::t('app', 'Тип'),
                "type" => "dropdown",
                "data" => [
                    ["label" => "Приход", "value" => BoxOffice::COMING,],
                    ["label" => "Расход", "value" => BoxOffice::EXPENDITURE,],
                    ["label" => "Перемещение", "value" => BoxOffice::MOVING,],
                    ["label" => "Отменено", "value" => BoxOffice::CANCELLED,],
  
                ]
            ],   
            [
                "name" => 'comment',
                'label' => Yii::t('app', 'Комментарий'),
                "type" => "text"
            ],      
            [
                "name" => 'who_id',
                'label' => Yii::t('app', 'Кто провел'),
                "type" => "books",
                "filter" => "name",
                "url" => "user/index"
            ],      
            [
                "name" => 'create_at',
                'label' => Yii::t('app', 'Дата и время'),
                "type" => "datetime"
            ],                
        ];
    

        array_walk($models, function(&$model){
            if ($model->where_id) $model->where_id = $model->where->name;
            if ($model->counterparty_id) $model->counterparty_id = $model->counterparty->name;
            if ($model->group_id) $model->group_id = $model->group->name;
            
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
        $model = new BoxOffice();

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
        $model = BoxOffice::findOne($id);

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
        $model = BoxOffice::findOne($id);

        $result["form"] = [            [
                "name" => 'cancel',
                'label' => Yii::t('app', 'Отменить'),
                "type" => "input"
            ],            [
                "name" => 'where_id',
                'label' => Yii::t('app', 'Куда перемещаем'),
                "type" => "input"
            ],            [
                "name" => 'counterparty_id',
                'label' => Yii::t('app', 'Контрагент'),
                "type" => "input"
            ],            [
                "name" => 'group_id',
                'label' => Yii::t('app', 'Группа'),
                "type" => "input"
            ],   
            [
                "name" => 'amounts',
                'label' => Yii::t('app', 'Сумма'),
                "type" => "number"
            ],             
            [
                "name" => 'comment',
                'label' => Yii::t('app', 'Комментарий'),
                "type" => "text"
            ],               [
                "name" => 'cheque',
                'label' => Yii::t('app', 'Чеки'),
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
        $model = BoxOffice::findOne($id);

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