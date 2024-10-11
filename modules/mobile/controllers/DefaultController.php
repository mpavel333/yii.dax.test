<?php
namespace app\modules\mobile\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Questionary;
use app\models\Questions;
use app\models\Applications;
use app\models\Resume;
use app\models\additional\Contacts;
use yii\web\NotFoundHttpException;
use app\models\Users;
use app\models\Settings;
use \yii\web\Response;
use yii\filters\ContentNegotiator;

/**
 * Default controller for the `mobile` module
 */
class DefaultController extends Controller
{

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
     * Lists all Machine models.
     * @return mixed
     */
    public function actionIndex()
    {    
        return ["seccess"];
    }  


    /**
     * Lists all Machine models.
     * @return mixed
     */
    public function actionGetVersion()
    {    
        return 2.0;
    }  

    
    /**
     * Lists all Machine models.
     * @return mixed
     */
    public function actionGetLangConst()
    {    
        return [
            "Выберите" =>  Yii::t('app', 'Выберите'),
            "Настройки" =>  Yii::t('app', 'Настройки'),
            "Справочник" =>  Yii::t('app', 'Справочник'),
            "Добавить" =>  Yii::t('app', 'Добавить'),
            "Изменить" =>  Yii::t('app', 'Изменить'),
            "Создать" =>  Yii::t('app', 'Создать'),
        ];
    }  

    /**
     * Lists all Machine models.
     * @return mixed
     */
    // public function actionReserv()
    public function actionGetMenu()
    {   
        $list =  [
            'items' => [
                ['label' => Yii::t('app', 'Показатели '), 'icon' => 'fa fa-bar-chart', 'url' => ['/dashboard']],
                ['label' => Yii::t('app', 'Касса'), 'icon' => 'fa fa-user', 'url' => ['/box-office']],
                ['label' => Yii::t('app', 'Товары'), 'icon' => 'fa fa-product-hunt', 'url' => ['/goods']],
                ['label' => Yii::t('app', 'Заказы'), 'icon' => 'fa fa-apple', 'url' => ['/order']],
                ['label' => Yii::t('app', 'Чат'), 'icon' => 'fa fa-wechat', 'url' => ['/chat']],

            ],
        ];
        return $list;
    }  

    /**
     * Lists all Machine models.
     * @return mixed
     */
    public function actionGetMenuReferences()
    {   
        $list =  [
                ['label' => Yii::t('app', 'Статусы товара'), 'icon' => 'fa fa-user', 'url' => ['/status']],
                ['label' => Yii::t('app', 'Кошелек'), 'icon' => 'fa fa-user', 'url' => ['/purse']],
                ['label' => Yii::t('app', 'Группа касса'), 'icon' => 'fa fa-user', 'url' => ['/group']],
                ['label' => Yii::t('app', 'Статусы заказ'), 'icon' => 'fa fa-user', 'url' => ['/status-order']],
                ['label' => Yii::t('app', 'Заказчик'), 'icon' => 'fa fa-user', 'url' => ['/customer']],
                ['label' => Yii::t('app', 'Брэнд'), 'icon' => 'fa fa-user', 'url' => ['/brand']],
                ['label' => Yii::t('app', 'Контрагент'), 'icon' => 'fa fa-adjust', 'url' => ['/contractor']],
                ['label' => Yii::t('app', 'Категория'), 'icon' => 'fa fa-user', 'url' => ['/manufacturer']],
        ];
        return $list;
    }  

    /**
     * Lists all Machine models.
     * @return mixed
     */
    public function actionGetMenuSetting()
    {   
        $list =  [
                ['label' => Yii::t('app', 'Пользователи'), 'icon' => 'fa fa-user-o', 'url' => ['/user']],
                ['label' => Yii::t('app', 'Роли'), 'icon' => 'fa fa-star', 'url' => ['/role']],
        ];
        return $list;
    }          

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new \app\models\forms\LoginForm();

        $content = file_get_contents('php://input');
        $rawData = json_decode($content, true);

        // Yii::$app->user->identity = ;

        $model->username = isset($rawData['username']) ? $rawData['username'] : null;
        $model->password = isset($rawData['password']) ? $rawData['password'] : null;



        if ($model->login()) {
            //Кандидату пользователя проставляем дату входа
            // return $this->goBack();

            $user = \app\models\User::findByUsername($model->username);

            return ['token' => $user->login.'||'.$user->password_hash];
        } else {
            return ['errors' => $model->errors];
        }
    }

    public function actionTest()
    {
        return ['success' => Yii::$app->user->identity->login]; 
    }

    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

}
