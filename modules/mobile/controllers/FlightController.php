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

class FlightController extends Controller
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
                'instanceQuery' => \app\models\Flight::find(),
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new \app\models\FlightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        $page = isset($_GET['p']) ? $_GET['p'] : 0;

        $dataProvider->pagination->page = $page;

        foreach($_GET as $key => $value){
            if($key == 'token' || $key == 'p'){
                continue;
            }

            $dataProvider->query->andFilterWhere(['like', $key, $value]);
        }

        $models = $dataProvider->models;




        // \Yii::warning($this->user);
        $notClient = stripos($this->user->getRoleName(), "Заказчик") == -1;
        
        \Yii::warning($notClient, '$notClient');


        $result["form"] = [
            [
                "name" => 'rout',
                'label' => Yii::t('app', 'Маршрут'),
                "type" => "string",
                // 'visible'=> $notClient,
            ],   
            [
                "name" => 'organization_id',
                'label' => Yii::t('app', 'Организация'),
                // "type" => "dropdown",
                // "data" => $requisiteData,
                "type" => "books",
                "filter" => "name",
                "url" => "requisite/index",
                'visible'=>\app\models\Flight::isVisibleAttr('organization_id', $this->user) && $notClient,
            ],   
            [
                "name" => 'zakazchik_id',
                'label' => Yii::t('app', 'Заказчик'),
                "type" => "books",
                "filter" => "name",
                "url" => "client/index",
                'visible'=>\app\models\Flight::isVisibleAttr('zakazchik_id', $this->user) && $notClient,
            ],      
            [
                "name" => 'carrier_id',
                'label' => Yii::t('app', 'Перевозчик'),
                "type" => "books",
                "filter" => "name",
                "url" => "client/index",
                'visible'=>\app\models\Flight::isVisibleAttr('carrier_id', $this->user) && $notClient,
            ],   
            [
                "name" => 'driver_id',
                'label' => Yii::t('app', 'Водитель'),
                "type" => "books",
                "filter" => "data",
                "url" => "driver/index",
                'visible'=>\app\models\Flight::isVisibleAttr('driver_id', $this->user) && $notClient,
            ],      
            [
                "name" => 'auto',
                'label' => Yii::t('app', 'Авто'),
                "type" => "books",
                "filter" => "data",
                "url" => "driver/index",
                'visible'=>\app\models\Flight::isVisibleAttr('auto', $this->user) && $notClient,
            ],                 
            [
                "name" => 'date',
                'label' => Yii::t('app', 'Дата заявки'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date', $this->user) && $notClient,
            ],                
            [
                "name" => 'order',
                'label' => Yii::t('app', 'Номер'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('order', $this->user) && $notClient,
            ],                
            [
                "name" => 'driver_order',
                'label' => Yii::t('app', 'Заявка перевозчика'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('driver_order', $this->user) && $notClient,
            ],     
            [
                "name" => 'shipping_date',
                'label' => Yii::t('app', 'Дата загрузки'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('shipping_date', $this->user),
            ],          
            [
                "name" => 'shipping_date_2',
                'label' => Yii::t('app', 'Дата загрузки 2'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('shipping_date_2', $this->user) && $notClient,
            ],
            [
                "name" => 'address1',
                'label' => Yii::t('app', 'Адрес загрузки'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('address1', $this->user),
            ],
            [
                "name" => 'address_out4',
                'label' => Yii::t('app', 'Адрес разгрузки'),
                "type" => "string",
                // 'visible'=> $notClient,
            ],
            [
                "name" => 'date_out4',
                'label' => Yii::t('app', 'Дата разгрузки'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out4', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out4_2',
                'label' => Yii::t('app', 'Дата разгрузки 2'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out4_2', $this->user) && $notClient,
            ],

            [
                "name" => 'driver_order',
                'label' => Yii::t('app', 'Заявка перевозчика'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('driver_order', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out2',
                'label' => Yii::t('app', 'Дата загрузки2'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out2', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out2_2',
                'label' => Yii::t('app', 'Дата загрузки2 2'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out2_2', $this->user) && $notClient,
            ],
            [
                "name" => 'address_out5',
                'label' => Yii::t('app', 'Адрес разгрузки 2'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('address_out5', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out5',
                'label' => Yii::t('app', 'Дата разгрузки2'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out5', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out4',
                'label' => Yii::t('app', 'Дата разгрузки'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out4', $this->user),
            ],
            [
                "name" => 'address_out3',
                'label' => Yii::t('app', 'Адрес загрузки3'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('address_out3', $this->user) && $notClient,
            ],
            [
                "name" => 'driver_order',
                'label' => Yii::t('app', 'Заявка перевозчика'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('driver_order', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out3',
                'label' => Yii::t('app', 'Дата загрузки3'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out3', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out3_2',
                'label' => Yii::t('app', 'Дата загрузки3 2'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out3_2', $this->user) && $notClient,
            ],
            [
                "name" => 'address',
                'label' => Yii::t('app', 'Адрес разгрузки3'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('address', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out6',
                'label' => Yii::t('app', 'Дата разгрузки3'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out6', $this->user) && $notClient,
            ],
            [
                "name" => 'date_out6_2',
                'label' => Yii::t('app', 'Дата разгрузки3 2'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_out6_2', $this->user) && $notClient,
            ],
            [
                "name" => 'telephone1',
                'label' => Yii::t('app', 'Контактное лицо и Телефон на загрузки'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('telephone1', $this->user),
            ],
            [
                "name" => 'telephone',
                'label' => Yii::t('app', 'Контактное лицо и Телефон на разгрузки'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('telephone', $this->user),
            ],
            [
                "name" => 'contact_out2',
                'label' => Yii::t('app', 'Контактное лицо и телефон2'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('contract_out2', $this->user) && $notClient,
            ],
            [
                "name" => 'contact_out',
                'label' => Yii::t('app', 'Контактное лицо и телефон2') && $notClient,
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('contact_out', $this->user),
            ],
            [
                "name" => 'driver_order',
                'label' => Yii::t('app', 'Заявка перевозчика'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('driver_order', $this->user) && $notClient,
            ],
            [
                "name" => 'contact',
                'label' => Yii::t('app', 'Контактное лицо и телефон3'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('contact', $this->user) && $notClient,
            ],
            // [
            //     "name" => 'contact_out3',
            //     'label' => Yii::t('app', 'Контактное лицо и телефон3'),
            //     "type" => "text",
            //     'visible'=>\app\models\Flight::isVisibleAttr('contact_out3', $this->user),
            // ],
            [
                "name" => 'view_auto',
                'label' => Yii::t('app', 'Вид автоперевозчика'),
                "type" => "dropdown",
                "data" => $this->convertArray([
                    'Отдельной машиной' => 'Отдельной машиной',
                    'Догруз' => 'Догруз',
                ]),
                'visible'=>\app\models\Flight::isVisibleAttr('view_auto', $this->user),
            ],
            [
                "name" => 'type',
                'label' => Yii::t('app', 'Тип загрузки/выгрузки'),
                "type" => "dropdown",
                "data" => $this->convertArray(\app\models\Flight::typeLabels()),
                'visible'=>\app\models\Flight::isVisibleAttr('type', $this->user),
            ],
            [
                "name" => 'name',
                'label' => Yii::t('app', 'Наименование груза'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('name', $this->user),
            ],
            [
                "name" => 'name_price',
                'label' => Yii::t('app', 'Стоимость груза'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('name_price', $this->user),
            ],
            [
                "name" => 'volume',
                'label' => Yii::t('app', 'Объем М3'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('volume', $this->user),
            ],
            [
                "name" => 'cargo_weight',
                'label' => Yii::t('app', 'Вес груза'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('cargo_weight', $this->user),
            ],
            [
                "name" => 'length',
                'label' => Yii::t('app', 'Длинна'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('length', $this->user),
            ],
            [
                "name" => 'width',
                'label' => Yii::t('app', 'Ширина'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('width', $this->user),
            ],
            [
                "name" => 'height',
                'label' => Yii::t('app', 'Высота'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('height', $this->user),
            ],
            [
                "name" => 'place_count',
                'label' => Yii::t('app', 'Количество мест'),
                "type" => "string",
                'visible'=>\app\models\Flight::isVisibleAttr('place_count', $this->user),
            ],
            [
                "name" => 'name2',
                'label' => Yii::t('app', 'Упаковка'),
                "type" => "dropdown",
                "data" => $this->convertArray(\app\models\Flight::name2Labels()),
                'visible'=>\app\models\Flight::isVisibleAttr('name2', $this->user),
            ],
            [
                "name" => 'daks_balance',
                'label' => Yii::t('app', 'Даксы'),
                "type" => "text",
            ],
            [
                "name" => 'information_file_path',
                'label' => Yii::t('app', 'Информация'),
                "type" => "audio",
            ],
            [
                "name" => 'dop_informaciya_o_gruze',
                'label' => Yii::t('app', 'Доп Информация о грузе'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('dop_informaciya_o_gruze', $this->user) && $notClient,
            ],
            [
                "name" => 'we',
                'label' => Yii::t('app', 'Сумма по рейсу'),
                "type" => "number",
                // 'visible'=>\app\models\Flight::isVisibleAttr('we', $this->user) && $notClient,
            ],
            [
                "name" => 'pay_us',
                'label' => Yii::t('app', 'Форма оплаты'),
                "type" => "dropdown",
                "data" => $this->convertArray(\app\models\Flight::pay_usLabels()),
                'visible'=>\app\models\Flight::isVisibleAttr('pay_us', $this->user) && $notClient,
            ],
            [
                "name" => 'payment1',
                'label' => Yii::t('app', 'Тип оплаты'),
                "type" => "dropdown",
                "data" => $this->convertArray(\app\models\Flight::payment1Labels()),
                'visible'=>\app\models\Flight::isVisibleAttr('payment1', $this->user) && $notClient,
            ],
            [
                "name" => 'col2',
                'label' => Yii::t('app', 'Кол-дней'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('col2', $this->user) && $notClient,
            ],
            [
                "name" => 'payment_out',
                'label' => Yii::t('app', 'Оплата водителя'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('payment_out', $this->user) && $notClient,
            ],
            [
                "name" => 'otherwise3',
                'label' => Yii::t('app', 'Форма оплаты'),
                "type" => "dropdown",
                "data" => $this->convertArray(\app\models\Flight::pay_usLabels()),
                'visible'=>\app\models\Flight::isVisibleAttr('otherwise3', $this->user) && $notClient,
            ],
            [
                "name" => 'payment1',
                'label' => Yii::t('app', 'Тип оплаты'),
                "type" => "dropdown",
                "data" => $this->convertArray(\app\models\Flight::payment1Labels()),
                'visible'=>\app\models\Flight::isVisibleAttr('payment1', $this->user) && $notClient,
            ],
            [
                "name" => 'col1',
                'label' => Yii::t('app', 'Кол-во дней'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('col1', $this->user) && $notClient,
            ],
            [
                "name" => 'payment_out',
                'label' => Yii::t('app', 'Оплата водителя'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('payment_out', $this->user) && $notClient,
            ],
            [
                "name" => 'fio',
                'label' => Yii::t('app', 'Тип счета'),
                "type" => "dropdown",
                "data" => $this->convertArray([
                    'Стандарт' => 'Стандарт',
                    'Срыв погрузки' => 'Срыв погрузки',
                    'Предоплата' => 'Предоплата',
                    'Ваш текст' => 'Ваш текст',
                 ]),
                'visible'=>\app\models\Flight::isVisibleAttr('fio', $this->user) && $notClient,
            ],
            [
                "name" => 'date_cr',
                'label' => Yii::t('app', 'Дата'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_cr', $this->user) && $notClient,
            ],
            [
                "name" => 'number',
                'label' => Yii::t('app', 'Счёт'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('number', $this->user) && $notClient,
            ],
            [
                "name" => 'upd',
                'label' => Yii::t('app', 'УПД'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('upd', $this->user) && $notClient,
            ],
            [
                "name" => 'otherwise4',
                'label' => Yii::t('app', 'Иное условие  для Заказчика'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('otherwise4', $this->user) && $notClient,
            ],
            [
                "name" => 'otherwise',
                'label' => Yii::t('app', 'Иное условие для Водителя'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('otherwise', $this->user) && $notClient,
            ],
            [
                "name" => 'recoil',
                'label' => Yii::t('app', 'Откат'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('recoil', $this->user) && $notClient,
            ],
            [
                "name" => 'your_text',
                'label' => Yii::t('app', 'Ваш текст'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('your_text', $this->user) && $notClient,
            ],
            [
                "name" => 'date2',
                'label' => Yii::t('app', 'Дата письма от заказчика'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date2', $this->user) && $notClient,
            ],
            [
                "name" => 'date3',
                'label' => Yii::t('app', 'Дата письма от водителя'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date3', $this->user) && $notClient,
            ],
            // [
            //     "name" => 'sum',
            //     'label' => Yii::t('app', 'Сумма по рейсу'),
            //     "type" => "number",
            //     'visible'=>\app\models\Flight::isVisibleAttr('name3', $this->user),
            // ],
            [
                "name" => 'name3',
                'label' => Yii::t('app', 'Информацию по рейсу'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('name3', $this->user),
            ],
            // [
            //     "name" => 'is_register',
            //     'label' => Yii::t('app', 'Регистрация рейса'),
            //     "type" => "check",
            //     'visible'=>\app\models\Flight::isVisibleAttr('is_register', $this->user),
            // ],
            // [
            //     "name" => 'is_order',
            //     'label' => Yii::t('app', 'Заявка'),
            //     "type" => "check",
            //     'visible'=>\app\models\Flight::isVisibleAttr('is_order', $this->user),
            // ],
            [
                "name" => 'is_signature',
                'label' => Yii::t('app', 'Подпись заказчика'),
                "type" => "check",
                'visible'=>\app\models\Flight::isVisibleAttr('is_signature', $this->user) && $notClient,
            ],
            [
                "name" => 'is_driver_signature',
                'label' => Yii::t('app', 'Подпись водителя'),
                "type" => "check",
                'visible'=>\app\models\Flight::isVisibleAttr('is_driver_signature', $this->user),
            ],
            [
                "name" => 'is_signature',
                'label' => Yii::t('app', 'Подпись заказчика'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('is_signature', $this->user) && $notClient,
            ],
            [
                "name" => 'file',
                'label' => Yii::t('app', 'Файлы'),
                "type" => "dropzone",
                // 'visible'=>\app\models\Flight::isVisibleAttr('file', $this->user),
            ],
            // [
            //     "name" => 'provider_file',
            //     'label' => Yii::t('app', 'Файлы водителя'),
            //     "type" => "dropzone",
            //     'visible'=>\app\models\Flight::isVisibleAttr('provider_file', $this->user) && $notClient,
            // ],
        ];

        $result['form'] = array_values(array_filter($result['form'], function($model){
            return isset($model['visible']) ? $model['visible'] : true;
        }));

        array_walk($result['form'], function(&$model){
            unset($model['visible']);
        });
    
        $flightStatusData = [];

        foreach (\app\models\Flight::statusLabels() as $id => $label) {
            $flightStatusData[] = ['label' => $label, 'value' => $id];
        }

        $result["index"] = [
            [
                "name" => 'rout',
                'label' => Yii::t('app', 'Маршрут'),
                "type" => "string",
                // 'visible'=>\app\models\Flight::isVisibleAttr('route', $this->user),
            ],
            [
                "name" => 'status',
                'label' => Yii::t('app', 'Статус'),
                "type" => "dropdown",
                "data" => $flightStatusData,
                'visible'=>\app\models\Flight::isVisibleAttr('status', $this->user),
            ],     
            // [
            //     "name" => 'user_id',
            //     'label' => Yii::t('app', 'Менеджер'),
            //     "type" => "books",
            //     "filter" => "name",
            //     "url" => "user/index",
            //     'visible'=>\app\models\Flight::isVisibleAttr('user_id', $this->user),
            // ],      
            // [
            //     "name" => 'organization_id',
            //     'label' => Yii::t('app', 'Наша фирма'),
            //     "type" => "books",
            //     "filter" => "name",
            //     "url" => "client/index",
            //     'visible'=>\app\models\Flight::isVisibleAttr('organization_id', $this->user),
            // ],      
            [
                "name" => 'order',
                'label' => Yii::t('app', 'Номер'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('order', $this->user),
            ],     
            [
                "name" => 'date',
                'label' => Yii::t('app', 'Дата'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date', $this->user),
            ],      
            // [
            //     "name" => 'rout',
            //     'label' => Yii::t('app', 'Предоплата'),
            //     "type" => "number",
            //     'visible'=>\app\models\Flight::isVisibleAttr('rout', $this->user),
            // ],      
            // [
            //     "name" => 'zakazchik_id',
            //     'label' => Yii::t('app', 'Заказчик'),
            //     "type" => "books",
            //     "filter" => "name",
            //     "url" => "client/index",
            //     'visible'=>\app\models\Flight::isVisibleAttr('zakazchik_id', $this->user),
            // ],      
            [
                "name" => 'date_cr',
                'label' => Yii::t('app', 'Дата'),
                "type" => "date",
                'visible'=>\app\models\Flight::isVisibleAttr('date_cr', $this->user),
            ],       
            [
                "name" => 'upd',
                'label' => Yii::t('app', 'УПД'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('upd', $this->user),
            ],                 
            // [
            //     "name" => 'carrier_id',
            //     'label' => Yii::t('app', 'Перевозчик'),
            //     "type" => "book",
            //     "filter" => "name",
            //     'url' => 'client/index',
            //     'visible'=>\app\models\Flight::isVisibleAttr('carrier_id', $this->user),
            // ],   
            [
                "name" => 'carrierTel',
                'label' => Yii::t('app', 'Перевозчик тел'),
                "type" => "book",
                "filter" => "name",
                'url' => 'client/index',
                'visible'=>\app\models\Flight::isVisibleAttr('carrierTel', $this->user),
            ], 
            // [
            //     "name" => 'driver_id',
            //     'label' => Yii::t('app', 'ФИО водителя'),
            //     "type" => "book",
            //     "filter" => "phone",
            //     'url' => 'driver/index',
            //     'visible'=>\app\models\Flight::isVisibleAttr('driver_id', $this->user),
            // ], 
            [
                "name" => 'driver_id',
                'label' => Yii::t('app', 'Данные авто'),
                "type" => "book",
                "filter" => "data_avto",
                'url' => 'driver/index',
                'visible'=>\app\models\Flight::isVisibleAttr('driver_id', $this->user),
            ], 
            // [
            //     "name" => 'we',
            //     'label' => Yii::t('app', 'Оплата от заказчика'),
            //     "type" => "text",
            //     'visible'=>\app\models\Flight::isVisibleAttr('we', $this->user),
            // ], 
            // [
            //     "name" => 'pay_us',
            //     'label' => Yii::t('app', 'Форма оплаты'),
            //     "type" => "text",
            //     'visible'=>\app\models\Flight::isVisibleAttr('pay_us', $this->user),
            // ], 
            // [
            //     "name" => 'payment_out',
            //     'label' => Yii::t('app', 'Оплата водителя'),
            //     "type" => "text",
            //     'visible'=>\app\models\Flight::isVisibleAttr('payment_out', $this->user),
            // ], 
            // [
            //     "name" => 'otherwise2',
            //     'label' => Yii::t('app', 'Форма оплаты'),
            //     "type" => "text",
            //     'visible'=>\app\models\Flight::isVisibleAttr('otherwise2', $this->user),
            // ], 
            // [
            //     "name" => 'carrier_id',
            //     'label' => Yii::t('app', 'АТИ водителя'),
            //     "type" => "book",
            //     "fiter" => "code",
            //     'url' => 'client/index',
            //     'visible'=>\app\models\Flight::isVisibleAttr('carrier_id', $this->user),
            // ], 
            // [
            //     "name" => 'organization_id',
            //     'label' => Yii::t('app', 'АТИ заказчика'),
            //     "type" => "book",
            //     "fiter" => "code",
            //     'url' => 'client/index',
            //     'visible'=>\app\models\Flight::isVisibleAttr('organization_id', $this->user),
            // ], 
            [
                "name" => 'name3',
                'label' => Yii::t('app', 'Информация по рейсу'),
                "type" => "text",
                'visible'=>\app\models\Flight::isVisibleAttr('name', $this->user) && $notClient,
            ], 
            [
                "name" => 'file',
                'label' => Yii::t('app', 'Файлы'),
                "type" => "text",
                // 'visible'=>\app\models\Flight::isVisibleAttr('name', $this->user) && $notClient,
            ], 
        ];
    
        array_filter($result['index'], function($model){
            return isset($model['visible']) ? $model['visible'] : true;
        });

        array_walk($result['index'], function(&$model){
            unset($model['visible']);
        });

        $clients = \yii\helpers\ArrayHelper::map(\app\models\Client::find()->all(), 'id', 'name');
        $requisites = \yii\helpers\ArrayHelper::map(\app\models\Requisite::find()->all(), 'id', 'name');
        $drivers = \yii\helpers\ArrayHelper::map(\app\models\Driver::find()->all(), 'id', 'data');
        $users = \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name');
        $banks = \yii\helpers\ArrayHelper::map(\app\models\Bank::find()->all(), 'id', 'name');

        $user = $this->user;

        array_walk($models, function(&$model) use($clients, $requisites, $drivers, $users, $banks, $user){
            $model['zakazchik_id'] = \yii\helpers\ArrayHelper::getValue($clients, $model['zakazchik_id']);
            $model['carrier_id'] = \yii\helpers\ArrayHelper::getValue($clients, $model['carrier_id']);
            $model['status'] = \yii\helpers\ArrayHelper::getValue(\app\models\Flight::statusLabels(), $model['status']);
            $model['organization_id'] = \yii\helpers\ArrayHelper::getValue($requisites, $model['organization_id']);
            $model['driver_id'] = \yii\helpers\ArrayHelper::getValue($drivers, $model['driver_id']);
            $model['user_id'] = \yii\helpers\ArrayHelper::getValue($users, $model['user_id']);
            $model['bank_id'] = \yii\helpers\ArrayHelper::getValue($banks, $model['bank_id']);
            $model['created_by'] = \yii\helpers\ArrayHelper::getValue($users, $model['created_by']);
            $model['file'] = json_decode($model['file'], true);
            $model['file_provider'] = json_decode($model['file_provider'], true);
            $model['is_register'] = $model['is_register'] === null ? true : $model['is_register'];
            $model['is_order'] = $model['is_order'] === null ? true : $model['is_order'];


            $actionLeft = [
                [
                    "url" => "/update",
                    "icon" => "fa fa-pencil",
                    "color" => "#4B78BC",
                    // 'visible' => $user->isManager() || $user->isSuperAdmin() || ($user->isClient() && $model['status'] != 'В работе поиск а/м'),
                    'visible' => $user->isManager() || $user->isSuperAdmin() || $user->isClient(),
                    // 'visible' => $model['created_by'] == $user->id && $model['status'] == 0 && $model['is_signature'] == false || $model['user_id'] == $user->id || $user->isSuperAdmin() || $user->getRoleName() != 'Менеджер',
                ],
                // [
                //     "url" => "/delete",
                //     "icon" => "fa fa-trash",
                //     "color" => "#BC4B4B",
                //     'visible' => ($model['is_signature'] == false && $model['status'] !== 1) || $user->isSuperAdmin(),
                // ],
                // [
                //     "url" => "/pay/create",
                //     "icon" => "fa fa-map",
                //     "color" => "#BC4B4B",
                // ],
                [
                    "url" => "/copy",
                    "icon" => "fa fa-copy",
                    "color" => "#BC4B4B",
                    // 'visible' => ($model['is_signature'] == false && $model['status'] !== 1) || $user->isSuperAdmin(),
                ],
            ];

            // $result["action_right"] = [
            $actionRight = [
                [
                    "url" => "/view",
                    "icon" => "",
                    "color" => "#54BC4B",
                ],
            ];

            $actionLeft = array_values(array_filter($actionLeft, function($model){
                return isset($model['visible']) ? $model['visible'] : true;
            }));
            $actionRight = array_values(array_filter($actionRight, function($model){
                return isset($model['visible']) ? $model['visible'] : true;
            }));

            array_walk($actionLeft, function(&$model){
                if(isset($model['visible'])){
                    unset($model['visible']);
                }
            });
            array_walk($actionRight, function(&$model){
                if(isset($model['visible'])){
                    unset($model['visible']);
                }
            });


            $model['action_left'] = $actionLeft;
            $model['action_right'] = $actionRight;
        });

        $user = $this->user;

        $visibleAttrs = ['id', 'upd', 'date_cr', 'status', 'action_left', 'action_right'];

        array_walk($models, function(&$model) use($user, $visibleAttrs){
        	$datesAttrs = ['date', 'date_out2', 'date_out3', 'date_out4', 'date_out5', 'date_out6', 'date_cr', 'date2', 'date3', 'date_cr_check', 'act_date', 'shipping_date'];
            foreach ($model as $attribute => $value) {
                if(\app\models\Flight::isVisibleAttr($attribute, $user) == false && $attribute != 'file'){
                    unset($model[$attribute]);
                }
                // if(in_array($attribute, $visibleAttrs) == false){
                //     unset($model[$attribute]);
                // }
                if(in_array($attribute, $datesAttrs) && isset($model[$attribute])){
                	if($model[$attribute]){
                		try {
                            $dateTime = new \DateTime($model[$attribute]);
                            $model[$attribute] = $dateTime->format('d-m-Y');
                        } catch (\Exception $e){
                            
                        }
                	}
                }
            }
        });


        $result["data"] = $models;
        return $result;
    }

    private function convertArray($array)
    {
        $output = [];

        foreach($array as $value => $label)
        {
            $output[] = ['label' => $label, 'value' => $value];
        }

        return $output;
    }

    public function actionDownload($path)
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
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $data = ['model' => $this->rawData];
        $model = new Flight();

        if($model->load($data, 'model') && $model->validate())
        {
            if($model->is_register == 0){
                $model->is_register = false;
            }

            if($model->is_order == 0){
                $model->is_order = false;
            }
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

    public function actionUpload($id)
    {
        $model = Flight::findOne($id);


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

            $newFile = [
                'name' => $file->name,
                'url' => '/'.$path,
                // 'url' => '/img/no-photo.png',
                'type' => null,
                'size' => $size,
                'preview_url' => $getRes['public_url'],
            ];

            $files = json_decode($model->file, true);
            $files[] = $newFile;
            $model->file = json_encode($files, JSON_UNESCAPED_UNICODE);
            $model->save(false);
        }
    }

    // public function actionUploadProvider($id)
    // {
    //     $model = Flight::findOne($id);


    //     Yii::$app->response->format = Response::FORMAT_JSON;
    //     $fileName = Yii::$app->security->generateRandomString();
    //     if (is_dir('uploads') == false) {
    //         mkdir('uploads');
    //     }
    //     $uploadPath = 'uploads';
    //     if (isset($_FILES['file'])) {
    //         $file = \yii\web\UploadedFile::getInstanceByName('file');
    //         $path = $uploadPath . '/' . $fileName . '.' . $file->extension;
            
    //         $file->saveAs($path);
    //         $base = log($file->size, 1024);
    //         $suffixes = array('Bytes', 'Kb', 'Mb', 'Gb', 'Tb');
    //         $size = round(pow(1024, $base - floor($base)), 2) .' '. $suffixes[floor($base)];

    //         $commonRes = \app\components\YandexDisk::upload($path);
    //         $res = $commonRes['res'];
    //         $getRes = $commonRes['getRes'];

    //         unlink($path);

    //         \Yii::warning($res, 'yd response');

    //         $newFile = [
    //             'name' => $file->name,
    //             'url' => '/'.$path,
    //             // 'url' => '/img/no-photo.png',
    //             'type' => null,
    //             'size' => $size,
    //             'preview_url' => $getRes['public_url'],
    //         ];

    //         $files = json_decode($model->file_provider, true);
    //         $files[] = $newFile;
    //         $model->file_provider = json_encode($files, JSON_UNESCAPED_UNICODE);
    //         $model->save(false);
    //     }
    // }

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

        if($action->id != 'login' && $action->id != 'download')
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

            $this->user = $user;

            Yii::$app->user->login($user, 0);
        }

        return parent::beforeAction($action);
    }
}
