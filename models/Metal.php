<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "flight".
*
    * @property int $organization_id Организация Наша
    * @property int $zakazchik_id Заказчик
    * @property int $carrier_id Перевозчик
    * @property int $driver_id Водитель
    * @property string $rout Маршрут
    * @property string $order Заявка
    * @property  $date Дата заявки
    * @property  $view_auto Вид автоперевозчика
    * @property string $address1 Адрес погрузки1
    * @property  $shipping_date Дата погрузки
    * @property string $telephone1 Телефон погрузки
    * @property  $type Тип загрузки/выгрузки
    * @property string $date_out2 Дата Загрузке2
    * @property string $address_out2 Адрес Загрузке2
    * @property string $contact_out2 Контактное лицо и Телефон2
    * @property  $name2 Упаковка
    * @property string $address_out3 Адрес Загрузке3
    * @property string $date_out3 Дата Загрузке3
    * @property string $contact Контактное лицо и Телефон3
    * @property string $name3 Наименование груза3
    * @property string $address_out4 Адрес разгрузки
    * @property string $date_out4 Дата разгрузки
    * @property string $telephone Телефон разгрузки
    * @property string $cargo_weight вес груза
    * @property string $name Наименование груза
    * @property string $address_out5 Адрес Разгрузке2
    * @property string $contact_out Контактное лицо и Телефон2
    * @property string $date_out5 Дата  Разгрузке2
    * @property string $volume ОбЪём М3	
    * @property string $address Адрес Разгрузке3
    * @property string $date_out6 Дата  Разгрузке3
    * @property string $contact_out3 Контактное лицо и Телефон3
    * @property string $dop_informaciya_o_gruze Доп Информация о грузе
    * @property string $we Оплата от Заказчика
    * @property  $pay_us Форма оплаты
    * @property  $payment1 Тип оплаты
    * @property string $col2 Кол-во дней
    * @property string $payment_out Оплата Водителя
    * @property  $otherwise2 Форма оплаты
    * @property  $otherwise3 Тип оплаты
    * @property string $col1 Кол-во дней
    * @property string $fio Тип счета
    * @property  $date_cr Дата
    * @property  $number Счёт
    * @property string $upd УПД
    * @property  $date2 Дата Письмо Заказчику
    * @property  $date3 Дата Письмо от  Водителя
    * @property string $recoil Откат
    * @property string $your_text Ваш текст
    * @property string $otherwise4 Иное условие  для Заказчика
    * @property string $otherwise Иное условие для Водителя	
    * @property  $file Файлы
*/
class Metal extends \app\base\ActiveRecord
{

    public $printClient;
    public $fileUploading;

    const INDIVIDUAL_MACHINE = 'Отдельной машиной';
    const DOGRUZ = 'Догруз';
    const ZADZAD = 'зад/зад';
    const ZADBOK = 'зад/бок';
    const ZADVERH = 'зад/верх';
    const BOKZAD = 'бок/зад';
    const BOKVERH = 'бок/верх';
    const BOKBOK = 'бок/бок';
    const VERHZAD = 'верх/зад';
    const VERHBOK = 'верх/бок';
    const VERHVERH = 'верх/верх';
    const FULL = 'Полная растентовка';
    const IN_BULK = 'навалом';
    const BOX = 'коробки';
    const PLACER = 'россыпью';
    const ZAPALLECHEN_V_PACHKAH_MESHKI = 'запаллечен в пачках мешки';
    const MESHKI = "Мешки";
    const PACHKI = "Пачки";
    const BIG_BAG = 'биг-бэг';
    const BOX8 = 'ящики';
    const SHEET = 'листы';
    const FLANK = 'бочки';
    const CANISTER = 'канистра';
    const ROLL = 'рулоны';
    const PYRAMID = 'пирамида';
    const PALLETS = 'Паллеты';
    const EUROCUBES = 'Еврокубы';
    const WITHOUT_VAT = 'без НДС';
    const WITH_VAT = 'с НДС';
    const AT_STAKE = 'на карту';
    const IN_CASH = 'Наличными';
    const ABOUT38 = 'по сканам ТТН  ТОРГ12 квитанция с почты РФ';
    const ABOUT19 = 'по оригиналам+сканы ТТН';
    const ONTO34 = 'на выгрузке';
    const ABOUT13 = 'по факту выгрузки';
    const AFTER7 = 'с момента доставки ОТТН и бух.док по адресу 445040 г.Тольятти а/я 3020';
    const SCANS = 'По сканам';
    const WITHOUT_VAT1 = 'без НДС';
    const WITH_VAT1 = 'с НДС';
    const AT_STAKE1 = 'на карту';
    const IN_CASH1 = 'Наличными';
    const ABOUT49 = 'по сканам ТТН  ТОРГ12 квитанция с почты РФ';
    const ABOUT11 = 'по оригиналам+сканы ТТН';
    const ONTO41 = 'на выгрузке';
    const ABOUT28 = 'по факту выгрузки';
    const AFTER32 = 'с момента доставки ОТТН и бух.док по адресу 445040 г.Тольятти а/я 3020';
    const OWN_TEXT = 'свой текст';
    
    const WEIGHT_TYPE_TONS = 'tons'; 
    const WEIGHT_TYPE_KILOS = 'kilos';

    const LOADING_TYPE_MANUAL = 'manual';
    const LOADING_TYPE_ORGANIZATION = 'organization';

    const STATUS_WORKING = 1;
    const STATUS_TO_LOADING = 2;
    const STATUS_LOADING = 3;
    const STATUS_TO_OUTPUT = 4;
    const STATUS_OUTPUT = 5;
    const STATUS_DONE = 6;
    const STATUS_SEARCHING = 7;




    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'metal';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
/*        
        return ArrayHelper::merge(parent::rules(), [
            [['flight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Flight::className(), 'targetAttribute' => ['flight_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['flight_id', 'sender_id'], 'integer'],
            [['sender_payment'], 'number'],
            [['sender_payment_form', 'sender_payment_type', 'sender_days'], 'string'],
        ]);
*/      
        //unset(parent::rules());

        return [
            [['organization_id', 'zakazchik_id', 'carrier_id', 'driver_id', 'bank', 'auto', 'rout', 'distance', 'date', 'shipping_date', 'address1', 'address_out4', 'date_out4', 'we', 'payment_out', 'pay_us', 'payment1', 'col2', 'otherwise2', 'otherwise3', 'col1', 'date_cr', 'name_price'], 'required'],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requisite::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['zakazchik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['zakazchik_id' => 'id']],
            [['carrier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['carrier_id' => 'id']],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
            [['payment_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['payment_bank_id' => 'id']],
            [['organization_id', 'zakazchik_id', 'carrier_id', 'driver_id', 'is_payed', 'user_id',  'is_driver_payed', 'is_ati_driver', 'is_ati_client', 'status', 'created_by', 'order_check', 'date_cr_check', 'number_check', 'is_register', 'is_order', 'is_signature', 'is_driver_signature', 'bank_id', 'place_count', 'is_insurance', 'logging_truck', 'road_train', 'air_suspension', 'body_type', 'only_for_paid_users', 'payment_bank_id', 'is_salary_payed'], 'integer'],
            [['rout', 'order', 'driver_order', 'date', 'view_auto', 'address1', 'shipping_date', 'telephone1', 'type', 'date_out2', 'address_out2', 'contact_out2', 'name2', 'address_out3', 'date_out3', 'contact', 'name3', 'address_out4', 'date_out4', 'telephone', 'cargo_weight', 'name', 'address_out5', 'contact_out', 'date_out5', 'volume', 'address', 'date_out6', 'contact_out3', 'dop_informaciya_o_gruze', 'pay_us', 'payment1', 'col2', 'payment_out', 'otherwise2', 'otherwise3', 'col1', 'fio', 'date_cr', 'upd', 'date2', 'date3', 'your_text', 'otherwise4', 'otherwise', 'file', 'number', 'file_provider', 'auto', 'created_at', 'car_number', 'act', 'act_date', 'track_number', 'track_number_driver', 'info_from_client', 'letter_info', 'letter_info_driver', 'contract_place', 'type_weight', 'loading_type', 'uploading_type', 'information', 'information_file_path', 'bank',
            ], 'string'],
            [['is_register_letter', 'is_register_letter_driver', 'archive_datetime', 'success_datetime', 'shipping_date_2', 'date_out4_2', 'date_out2_2', 'date_out5_2', 'date_out3_2', 'date_out6_2', 'we', 'ensurance_date', 'we_prepayment_datetime'], 'safe'],
            [['salary', 'delta', 'recoil', 'distance', 'sum', 'daks_balance', 'we_prepayment', 'payment_out_prepayment', 'name_price', 'width', 'height', 'length', 'diameter', 'belts_count', 'priority_rate', 'priority_limit', 'priority_daily_limit'], 'number'],
            ['upd', 'myUniqueValidator'],
            ['number', 'myUniqueValidatorNumber'],
            //['zakazchik', 'zakazchikValidator'],
            ['col2', 'col2Validator'],
            ['col1', 'col1Validator'],
            //['fileUploading', 'file'],
            [['date3'], 'dateValidator'],
            //['deltaPercentValidator', 'skipOnError' => false, 'skipOnEmpty' => false],
            ['recoil', 'recoilValidator'],

            [['flight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Flight::className(), 'targetAttribute' => ['flight_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['flight_id', 'sender_id'], 'integer'],
            [['sender_payment'], 'number'],
            [['sender_payment_form', 'sender_payment_type', 'sender_days'], 'string'],

            //[['additional_credit'], 'exist', 'skipOnError' => true]

        ];

    }


    public function col2Validator($attribute, $params)
    {
        $salary = \app\models\Salary::find()->one();

        if($salary && $salary->day_pays_min)
        {
            if($this->col2 < $salary->day_pays_min){
                if($this->payment1 != 'по оригиналам+сканы ТТН' && $this->payment1 != 'по сканам ТТН  ТОРГ12 квитанция с почты РФ' && $this->payment1 != 'По сканам'){
                    $this->addError('col2', "Не может быть меньше {$salary->day_pays_min}");
                }
            }
        }
    }

    public function col1Validator($attribute, $params)
    {
        $salary = \app\models\Salary::find()->one();

        if($salary && $salary->day_pays_min && $salary->day_pays_between)
        {
            if(mb_stripos($this->col1, '-') !== false){
              $col1 = explode('-', $this->col1);
            } elseif(mb_stripos($this->col1, '+') !== false){
              $col1 = explode('+', $this->col1);
            } else {
              $col1 = [$this->col1];
            }
            $col1 = $col1[count($col1)-1];
            if(is_numeric($col1)){
                $between = $this->col2 + $salary->day_pays_between;
                if($this->col1 < $between){
                    
                    if($this->otherwise3 != 'по оригиналам+сканы ТТН' && $this->otherwise3 != 'по сканам ТТН  ТОРГ12 квитанция с почты РФ' && $this->otherwise3 != 'По сканам'){
                        $this->addError('col2', "Не может быть меньше {$salary->day_pays_min}");
                    }
                }
            }
        }
    }

    public function myUniqueValidator($attribute,$params){
        if(!$this->hasErrors()){
            if($this->date){
                $year = explode('-', $this->date);
                $year = $year[0];
                $year = ['between', 'date', $year.'-01-01', $year.'-12-31'];
            } else {
                $year = ['>', 'date', date('Y').'-01-01'];
            }
            if(self::find()->where([$attribute => $this->$attribute])->andFilterWhere(['!=', 'id', $this->id])->andWhere($year)->one() && $this->$attribute !== null && is_numeric($this->$attribute)){
                $this->addError('upd', 'Данный УПД уже занят');
             }
        }
    }

    public function myUniqueValidatorNumber($attribute,$params){
        if(!$this->hasErrors()){
            if($this->date){
                $year = explode('-', $this->date);
                $year = $year[0];
                $year = ['between', 'date', $year.'-01-01', $year.'-12-31'];
            } else {
                $year = ['>', 'date', date('Y').'-01-01'];
            }
            if(self::find()->where([$attribute => $this->$attribute])->andFilterWhere(['!=', 'id', $this->id])->andWhere($year)->one() && $this->$attribute !== null && is_numeric($this->$attribute)){
                $this->addError('number', 'Данный счёт уже занят');
             }
        }
    }

    public function zakazchikValidator($attribute, $params)
    {
        $salary = \app\models\Salary::find()->one();
        $zakazchik = \app\models\Client::findOne($this->zakazchik_id);
        if($zakazchik){
            $sum = \app\models\Flight::find()->where(['zakazchik_id' => $this->zakazchik_id])->andWhere(['!=', 'is_payed', 1])->sum('we');
            if($zakazchik->limit){
                $limit = $zakazchik->limit;
            } else {
                $salary = \app\models\Salary::find()->one();
                if($salary){
                    $limit = $salary->limit;
                }
            }

            if($sum > $limit){
                $this->addError('zakazchik_id', 'Данный заказчик превысил лимит по неоплаченным рейсам');
            }
        }
    }

    public function deltaPercentValidator($attribute,$params){
        if(!$this->hasErrors()){
            if($this->payment_out){
                $salary = \app\models\Salary::find()->one();
                // $one = round((doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime)) / ($this->payment_out - 1) * 100);
                // $one = round((doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime)) / ($this->payment_out - 1) * 100);
                $one = doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);
                $one = $one / $this->payment_out;
                $one = $one - 1;
                $one = $one * 100;
                $this->delta_percent = round($one, 2);

                $salaryAttr = 'delta_percent';
                if($this->pay_us == 'без НДС' && $this->otherwise2 == 'без НДС' || $this->pay_us == 'с НДС' && $this->otherwise2 == 'с НДС'){
                    $salaryAttr = 'delta_percent_no_nds';
                }

                // $one = round(($one / 100 * $salary->delta_percent_additional));
                // \Yii::warning("{$one} > {$salary->delta_percent}", 'delta condition');
                if($one < $salary->$salaryAttr){
                    // \Yii::warning("$this->payment_out > $one", 'cond');
                    $this->addError('delta_percent', 'Оплата водителя выше');
                }
            }
            // $two = round(($this->we / 100 * $salary->delta_percent));
            // if($this->payment_out > $two){
            //     \Yii::warning("$this->payment_out > $one", 'cond');
            //     $this->addError('delta_percent', 'Оплата водителя выше');
            // }
        }
    }


    public function recoilValidator($attribute,$params){
        if(!$this->hasErrors()){
            $salary = \app\models\Salary::find()->one();
            $one = $this->we - $this->payment_out;
            $one = round(($one / 100 * $salary->delta_recoil));
            \Yii::warning("$this->recoil > $one", 'recoilValidator');
            if($this->recoil > $one){
                $this->addError('recoil', 'Не может быть больше '.$one);
            }
        }
    }

    public function dateValidator($attribute,$params){
        if(!$this->hasErrors()){
            $oldAttribute = "old_{$attribute}";
            if($this->$attribute < date('Y-m-d') && $this->$oldAttribute != $this->$attribute && \Yii::$app->user->identity->can('flight_date_validation') && $this->isNewRecord == false){
                $this->addError($attribute, 'Некорректная дата');
            }
        }
    }


    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'organization_id' => Yii::t('app', 'Организация Наша'),
            'zakazchik_id' => Yii::t('app', 'Заказчик'),
            'carrier_id' => Yii::t('app', 'Перевозчик'),
            'driver_id' => Yii::t('app', 'Водитель'),
            'rout' => Yii::t('app', 'Маршрут'),
            'distance' => Yii::t('app', 'Километраж'),
            'order' => Yii::t('app', 'Заявка заказчика'),
            'driver_order' => Yii::t('app', 'Заявка перевозчика'),
            'date' => Yii::t('app', 'Дата заявки'),
            'view_auto' => Yii::t('app', 'Вид автоперевозчика'),
            'address1' => Yii::t('app', 'Адрес загрузки'),
            'shipping_date' => Yii::t('app', 'Дата загрузки'),
            'telephone1' => Yii::t('app', 'Телефон погрузки'),
            'type' => Yii::t('app', 'Тип загрузки/выгрузки'),
            'status' => Yii::t('app', 'Статус рейса'),
            'date_out2' => Yii::t('app', 'Дата Загрузки2'),
            'address_out2' => Yii::t('app', 'Адрес Загрузки2'),
            'contact_out2' => Yii::t('app', 'Контактное лицо и Телефон2'),
            'name2' => Yii::t('app', 'Упаковка'),
            'address_out3' => Yii::t('app', 'Адрес Загрузки3'),
            'date_out3' => Yii::t('app', 'Дата Загрузки3'),
            'contact' => Yii::t('app', 'Контактное лицо и Телефон3'),
            'name3' => Yii::t('app', 'Информация по рейсу'),
            'address_out4' => Yii::t('app', 'Адрес разгрузки'),
            'date_out4' => Yii::t('app', 'Дата разгрузки'),
            'telephone' => Yii::t('app', 'Телефон разгрузки'),
            'cargo_weight' => Yii::t('app', 'Вес'),
            'name' => Yii::t('app', 'Наименование груза'),
            'name_price' => Yii::t('app', 'Стоимость груза'),
            'address_out5' => Yii::t('app', 'Адрес Разгрузки2'),
            'contact_out' => Yii::t('app', 'Контактное лицо и Телефон2'),
            'date_out5' => Yii::t('app', 'Дата  Разгрузки2'),
            'volume' => Yii::t('app', 'ОбЪём М3 '),
            'address' => Yii::t('app', 'Адрес Разгрузки3'),
            'date_out6' => Yii::t('app', 'Дата  Разгрузки3'),
            'contact_out3' => Yii::t('app', 'Контактное лицо и Телефон3'),
            'dop_informaciya_o_gruze' => Yii::t('app', 'Доп Информация о грузе'),
            'we' => Yii::t('app', 'Оплата от Заказчика'),
            'pay_us' => Yii::t('app', 'Форма оплаты'),
            'payment1' => Yii::t('app', 'Тип оплаты'),
            'col2' => Yii::t('app', 'Кол-во дней'),
            'payment_out' => Yii::t('app', 'Оплата Водителя'),
            'otherwise2' => Yii::t('app', 'Форма оплаты'),
            'otherwise3' => Yii::t('app', 'Тип оплаты'),
            'col1' => Yii::t('app', 'Кол-во дней'),
            'fio' => Yii::t('app', 'Тип счета'),
            'date_cr' => Yii::t('app', 'Дата создания'),
            'number' => Yii::t('app', 'Счёт'),
            'upd' => Yii::t('app', 'УПД'),
            'date2' => Yii::t('app', 'Дата Письмо Заказчику'),
            'date3' => Yii::t('app', 'Дата Письмо от  Водителя'),
            'recoil' => Yii::t('app', 'Баллы'),
            'your_text' => Yii::t('app', 'Ваш текст'),
            'otherwise4' => Yii::t('app', 'Иное условие  для Заказчика'),
            'otherwise' => Yii::t('app', 'Иное условие для Водителя '),
            'file' => Yii::t('app', 'Файлы'),
            'file_provider' => Yii::t('app', 'Файлы поставщика'),
            'is_payed' => Yii::t('app', 'Регистрация рейса'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'is_driver_payed' => Yii::t('app', 'Оплата водителя'),
            'is_ati_driver' => Yii::t('app', 'АТИ водителя'),
            'is_ati_client' => Yii::t('app', 'АТИ клиента'),
            'created_by' => Yii::t('app', 'Создал'),
            'created_at' => Yii::t('app', 'Создано'),
            'salary' => Yii::t('app', 'Зарплата'),
            'delta' => Yii::t('app', 'Дельта'),
            'order_check' => Yii::t('app', 'Активна'),
            'date_cr_check' => Yii::t('app', 'Активна'),
            'number_check' => Yii::t('app', 'Активна'),                                                                                                                                                                                                                                                                                                                                                                                          
            'place_count' => Yii::t('app', 'Количество мест'),                                                                                                                                                                                                                                                                                                                                                                                          
            'contract_place' => Yii::t('app', 'Место заключения заявки'),                                                                                                                                                                                                                                                                                                                                                                                          
            'car_number' => Yii::t('app', 'Номер автомобиля'),                                                                                                                                                                                                                                                                                                                                                                                          
            'bank' => Yii::t('app', 'Банк для оплаты'),                                                                                                                                                                                                                                                                                                                                                                                          
            'bank_id' => Yii::t('app', 'Банк'),                                                                                                                                                                                                                                                                                                                                                                                          
            'auto' => Yii::t('app', 'Авто'),                                                                                                                                                                                                                                                                                                                                                                                          
            'is_register' => Yii::t('app', 'Регистрация рейса'),                                                                                                        'is_order' => Yii::t('app', 'Заявка'),
                'is_signature' => Yii::t('app', 'Подпись заказчика'),
                'is_driver_signature' => Yii::t('app', 'Подпись перевозчика'),

            'act' => Yii::t('app', 'Акт'),
            'act_date' => Yii::t('app', 'Дата акта'),
            'track_number' => Yii::t('app', 'Трек номер'),
            'track_number_driver' => Yii::t('app', 'Трек номер'),
            'info_from_client' => Yii::t('app', 'Информация от заказчика'),

            'letter_info' => \Yii::t('app', 'Информация письма'),
            'letter_info_driver' => \Yii::t('app', 'Информация письма'),
            'is_register_letter' => \Yii::t('app', 'Регистрация почты Заказчик'),
            'is_register_letter_driver' => \Yii::t('app', 'Регистрация почты Перевозчик'),
            'archive_datetime' => \Yii::t('app', 'Дата и время архивации'),
            'success_datetime' => \Yii::t('app', 'Дата и время успеха'),
            'sum' => \Yii::t('app', 'Сумма рейса'),
            'daks_balance' => \Yii::t('app', 'Даксы'),
            'we_prepayment' => \Yii::t('app', 'Предоплата от заказчика'),
            'payment_out_prepayment' => \Yii::t('app', 'Предоплата от водителя'),
            'we_prepayment_datetime' => \Yii::t('app', 'Дата предоплаты заказчика'),
            'payment_out_prepayment_datetime' => \Yii::t('app', 'Дата предоплаты водителя'),
            'is_insurance' => \Yii::t('app', 'Страховка'),

            'type_weight' => \Yii::t('app', 'Тип'),
            'loading_type' => \Yii::t('app', 'Тип загрузки'),
            'uploading_type' => \Yii::t('app', 'Тип разгрузки'),
            'width' => \Yii::t('app', 'Ширина'),
            'height' => \Yii::t('app', 'Высота'),
            'length' => \Yii::t('app', 'Длинна'),
            'diameter' => \Yii::t('app', 'Диаметр'),
            'belts_count' => \Yii::t('app', 'Кол-во ремней'),
            'logging_truck' => \Yii::t('app', 'Коники'),
            'road_train' => \Yii::t('app', 'Сцепка'),
            'air_suspension' => \Yii::t('app', 'Пневмоход'),
            'body_type' => \Yii::t('app', 'Кузов'),
            'priority_rate' => \Yii::t('app', 'Ставка за просмотр'),
            'priority_limit' => \Yii::t('app', 'Лимит на заявку'),
            'priority_daily_limit' => \Yii::t('app', 'Суточный лимит'),
            'only_for_paid_users' => \Yii::t('app', 'Показывать приоритетный груз только платным пользователям'),
            'information_file_path' => \Yii::t('app', 'Информация файл'),
            'information' => \Yii::t('app', 'Информация'),
            'payment_bank_id' => \Yii::t('app', 'Банк для оплаты'),
            'is_salary_payed' => \Yii::t('app', 'Зарплата оплачена'),
            'is_salary_payed_datetime' => \Yii::t('app', 'Дата и время'),
            'fileUploading' => 'Загрузка файла',

            'ensurance_number' => Yii::t('app', 'Номер'),
            'ensurance_date' => Yii::t('app', 'Дата'),
            'ensurance_organization' => Yii::t('app', 'Организация/Страхователь'),
            'ensurance_contract' => Yii::t('app', 'Ген. договор страхования грузов'),
            'ensurance_contract_where' => Yii::t('app', 'Страховая компания'),
            'ensurance_currency' => Yii::t('app', 'Валюта'),
            'ensurance_client' => Yii::t('app', 'Контрагент'),
            'ensurance_profit_subject' => Yii::t('app', 'Выгодоприобретатель'),
            'ensurance_sum' => Yii::t('app', 'Страховая сумма'),
            'ensurance_sum2' => Yii::t('app', 'Страховая сумма'),
            'ensurance_prime' => Yii::t('app', 'Страховая премия'),
            'ensurance_transport_type' => Yii::t('app', 'Способ перевозки'),
            'ensurance_subject_from' => Yii::t('app', 'Регион отправки'),
            'ensurance_subject_to' => Yii::t('app', 'Регион прибытия'),
            'ensurance_country_from' => Yii::t('app', 'Страна отправки'),
            'ensurance_country_to' => Yii::t('app', 'Страна прибытия'),
            'ensurance_condition' => Yii::t('app', 'Условия страхования'),
            'ensurance_percent' => Yii::t('app', 'Процент'),
            'ensurance_ref' => Yii::t('app', 'Реф. риски'),
            'ensurance_security' => Yii::t('app', 'Охрана'),
            'ensurance_additional' => Yii::t('app', 'Доп. свед.'),
            'ensurance_order' => Yii::t('app', 'Заявка'),
            'ensurance_comment' => Yii::t('app', 'Комментарий'),
            'additional_credit' => Yii::t('app', 'Доп. Расходы'),
            'checks' => Yii::t('app', 'Сдача док-во'),
            'checks1' => Yii::t('app', 'Сдача док-во 1'),
            'checks2' => Yii::t('app', 'Сдача док-во 2'),
            'checks3' => Yii::t('app', 'Сдача док-во 3'),
            'flights_count' => 'Кол-во рейсов',
            'bill_type_prepayed' => 'Авансовый',
            'index' => '#',
            'delta_percent' => 'Дельта (процент)',
            'we_prepayment_form' => 'Аванс',
            'payment_out_prepayment_form' => 'Аванс',
            'transit' => 'Перевозчик 2',
            
            
            'flight_id' => 'Рейс',
            'sender_id' => 'Отправитель',
            'sender_payment' => 'Оплата отправителю',
            'sender_payment_form' => 'Форма оплаты',
            'sender_payment_type' => 'Тип оплаты',
            'sender_days' => 'Кол-во дней',
        ];
    }

    public static function typeWeightLabels()
    {
        return [
            self::WEIGHT_TYPE_TONS => 'Т',
            self::WEIGHT_TYPE_KILOS => 'КГ',
        ];
    }

    public static function loadingTypeLabels()
    {
        return [
            self::LOADING_TYPE_MANUAL => 'Ручная',
            self::LOADING_TYPE_ORGANIZATION => 'Из организации',
        ];
    }

    public static function view_autoLabels() {
        return [
            self::INDIVIDUAL_MACHINE => "Отдельной машиной",
            self::DOGRUZ => "Догруз",
        ];
    }public static function typeLabels() {
        return [
            self::ZADZAD => "зад/зад",
            self::ZADBOK => "зад/бок",
            self::ZADVERH => "зад/верх",
            self::BOKZAD => "бок/зад",
            self::BOKVERH => "бок/верх",
            self::BOKBOK => "бок/бок",
            self::VERHZAD => "верх/зад",
            self::VERHBOK => "верх/бок",
            self::VERHVERH => "верх/верх",
            self::FULL => "Полная растентовка",
        ];
    }public static function name2Labels() {
        return [
            self::IN_BULK => "навалом",
            self::BOX => "коробки",
            self::PLACER => "россыпью",
            self::ZAPALLECHEN_V_PACHKAH_MESHKI => "Запаллечен",
            self::MESHKI => "Мешки",
            self::PACHKI => "Пачки",
            self::BIG_BAG => "биг-бэг",
            self::BOX8 => "ящики",
            self::SHEET => "листы",
            self::FLANK => "бочки",
            self::CANISTER => "канистра",
            self::ROLL => "рулоны",
            self::PYRAMID => "пирамида",
            self::PALLETS => 'паллеты',
            self::EUROCUBES => 'еврокубы',
        ];
    }public static function pay_usLabels() {
        return [
            self::WITHOUT_VAT => "без НДС",
            self::WITH_VAT => "с НДС",
            self::AT_STAKE => "на карту",
            self::IN_CASH => "Наличными",
        ];
    }public static function payment1Labels() {
        return [
            self::ABOUT38 => "по сканам ТТН  ТОРГ12 квитанция с почты РФ",
            self::ABOUT19 => "по оригиналам+сканы ТТН",
            self::ONTO34 => "на выгрузке",
            self::ABOUT13 => "по факту выгрузки",
            self::AFTER7 => "с момента доставки ОТТН и бух.док по адресу",
            self::SCANS => "по сканам",
        ];
    }public static function otherwise2Labels() {
        return [
            self::WITHOUT_VAT1 => "без НДС",
            self::WITH_VAT1 => "с НДС",
            self::AT_STAKE1 => "на карту",
            self::IN_CASH1 => "Наличными",
        ];
    }public static function otherwise3Labels() {
        return [
            self::ABOUT49 => "по сканам ТТН  ТОРГ12 квитанция с почты РФ",
            self::ABOUT11 => "по оригиналам+сканы ТТН",
            self::ONTO41 => "на выгрузке",
            self::ABOUT28 => "по факту выгрузки",
            self::AFTER32 => "с момента доставки ОТТН и бух.док по адресу",
        ];
    }

    public static function statusLabels()
    {
        return [
            self::STATUS_SEARCHING => 'Поиск а/м',
            self::STATUS_WORKING => 'а/м назначен',
            // self::STATUS_TO_LOADING => 'В пути к загрузке',
            self::STATUS_LOADING => 'Загрузка',
            // self::STATUS_TO_OUTPUT => 'В пути к выгрузке',
            self::STATUS_OUTPUT => 'Выгрузка',
            self::STATUS_DONE => 'Выполнен',
        ];
    }


    /**
    * {@inheritdoc}
    */
    public function afterFind()
    {
        parent::afterFind();
    }


    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }

    /**
    * {@inheritdoc}
    */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
    * {@inheritdoc}
    */
    public function afterDelete()
    {
        parent::afterDelete();
    }



    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFlight()
    {
        return $this->hasOne(Flight::className(), ['id' => 'flight_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSender()
    {
        return $this->hasOne(Client::className(), ['id' => 'sender_id']);
    }

           
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrganization()
    {
        return $this->hasOne(Requisite::className(), ['id' => 'organization_id']);
    }

    

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
            
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getZakazchik()
    {
        if($this->printClient){
            return $this->printClient;
        }
        return $this->hasOne(Client::className(), ['id' => 'zakazchik_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getLimit()
    {
        return $this->hasOne(Client::className(), ['id' => 'limit']);
    }
    
            
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCarrier()
    {
        return $this->hasOne(Client::className(), ['id' => 'carrier_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCarrierModel()
    {
        if($this->transit){
            return $this->hasOne(Client::className(), ['id' => 'transit']);
        } else {
            return $this->hasOne(Client::className(), ['id' => 'carrier_id']);
        }
    }

    
            
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getDriver()
    {
        return $this->hasOne(Driver::className(), ['id' => 'driver_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }


    public function printValues()
    {
        $attributes = $this->attributeLabels();

        $tags = [];

        foreach ($attributes as $attribute => $label)
        {
            $label = str_replace(' ', '-', $label);

            if($attribute == 'organization_id') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue($this, 'organization.name');
            }
            else if($attribute == 'zakazchik_id') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue($this, 'zakazchik.name');
            }
            else if($attribute == 'carrier_id') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue($this, 'carrier.name');
            }
            else if($attribute == 'driver_id') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue($this, 'driver.data');
            }
            else if($attribute == 'date') {
                $tags['{'.mb_strtolower($label).'}'] = Yii::$app->formatter->asDate($this->$attribute, 'php:d.m.Y');
            }
            else if($attribute == 'view_auto') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue(self::view_autoLabels(), $this->view_auto);
            }
            else if($attribute == 'shipping_date') {
                $tags['{'.mb_strtolower($label).'}'] = Yii::$app->formatter->asDate($this->$attribute, 'php:d.m.Y');
            }
            else if($attribute == 'shipping_date_2') {
                $tags['{'.mb_strtolower($label).'}'] = Yii::$app->formatter->asDate($this->$attribute, 'php:d.m.Y');
            }
            else if($attribute == 'date_out4_2') {
                $tags['{'.mb_strtolower($label).'}'] = Yii::$app->formatter->asDate($this->$attribute, 'php:d.m.Y');
            }
            else if($attribute == 'type') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue(self::typeLabels(), $this->type);
            }
            else if($attribute == 'name2') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue(self::name2Labels(), $this->name2);
            }
            else if($attribute == 'pay_us') {
                $tags['{'.mb_strtolower($label).'}'] = number_format(ArrayHelper::getValue(self::pay_usLabels(), $this->pay_us), 2, ",", "");
            }
            else if($attribute == 'payment1') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue(self::payment1Labels(), $this->payment1);
            }
            else if($attribute == 'otherwise2') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue(self::otherwise2Labels(), $this->otherwise2);
            }
            else if($attribute == 'otherwise3') {
                $tags['{'.mb_strtolower($label).'}'] = ArrayHelper::getValue(self::otherwise3Labels(), $this->otherwise3);
            }
            else if($attribute == 'date_cr') {
                $tags['{'.mb_strtolower($label).'}'] = Yii::$app->formatter->asDate($this->$attribute, 'php:d.m.Y');
            }
            else if($attribute == 'date2') {
                $tags['{'.mb_strtolower($label).'}'] = Yii::$app->formatter->asDate($this->$attribute, 'php:d.m.Y');
            }
            else if($attribute == 'date3') {
                $tags['{'.mb_strtolower($label).'}'] = Yii::$app->formatter->asDate($this->$attribute, 'php:d.m.Y');
            }
            else {
                $tags['{'.mb_strtolower($label).'}'] = is_double(ArrayHelper::getValue($this, $attribute)) ? number_format(ArrayHelper::getValue($this, $attribute), 2, ",", "") : ArrayHelper::getValue($this, $attribute);
            }

        }

        // \Yii::warning($tags, 'TAGS');


        return $tags;
    }

  
    public function tags($text)
    {
        $arr = [];

        $datesArr = ['date_cr', 'date', 'shipping_date', 'date_out4', 'date_out2', 'date_out5', 'date_out3', 'date_out6', 'shipping_date_2', 'date_out4_2'];

        $result = preg_match_all('/\{.*?}/',$text,$arr);
        \Yii::warning($arr, 'TAGS');
        foreach ($arr[0] as $value) {
            $value2 = str_replace('{', '', $value);
            $value2 = str_replace('}', '', $value2);


            try {
                if(in_array($value2, $datesArr)){
                    $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2) ? Yii::$app->formatter->asDate(yii\helpers\ArrayHelper::getValue($this, $value2), 'php:d.m.Y') : null, $text);
                } else {
                    $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2), $text);
                }
            } catch(\yii\base\UnknownPropertyException $e){

            }

        }


        $rusMonth = [
          'января',
          'февраля',
          'марта',
          'апреля',
          'мая',
          'июня',
          'июля',
          'августа',
          'сентября',
          'октября',
          'ноября',
          'декабря'
        ];


        $I29 = $this->we;
        if(is_numeric($I29) == false){
            $I29 = 0;
        }
        $I28 = round($I29 * 20 / 120, 2);
        $I27 = round($I29 - $I28, 2);


        $month = date('n', strtotime($this->date_cr))-1;

        $tableName = "Транспортные услуги по Договору-Заявке №{$this->order} от ".\Yii::$app->formatter->asDate($this->date, 'php:d.m.Y').", маршрут {$this->rout}, Авто ".ArrayHelper::getValue($this, 'autoString').", Водитель ".ArrayHelper::getValue($this, 'driver.data');

        if($this->fio == 'Срыв погрузки'){
             $tableName = "Срыв погрузки по заявке №{$this->order} от ".\Yii::$app->formatter->asDate($this->date, 'php:d.m.Y').", маршрут {$this->rout}, Авто ".ArrayHelper::getValue($this, 'autoString').", Водитель ".ArrayHelper::getValue($this, 'driver.data');
        }

        if($this->fio == 'Предоплата'){
             $tableName = "Предоплата за транспортные услуги по заявке №{$this->order} от ".\Yii::$app->formatter->asDate($this->date, 'php:d.m.Y').", маршрут {$this->rout}, Авто ".ArrayHelper::getValue($this, 'autoString').", Водитель ".ArrayHelper::getValue($this, 'driver.data');
        }

        if($this->fio == 'Ваш текст'){
            $tableName = $this->your_text;
        }

        $cardString = null;

        if($this->pay_us == 'на карту' || $this->pay_us == 'Наличными'){
            $cardString = ", Номер карты: ".ArrayHelper::getValue($this, 'organization.card');            
        }

        $loadingTypes = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../data/loading-types.php'), 'Id', 'Name');
        $bodyTypes = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../data/cars.php'), 'TypeId', 'Name');

        $this->we = trim($this->we);

        if(is_numeric($this->we) == false){
            $this->we = 0;
        }

        if($this->fio == 'Международные'){
            $ndsText = "В т.ч. НДС 0%: 0";
        } elseif(ArrayHelper::getValue($this, 'organization.nds') && $this->fio != 'Срыв погрузки') {
            $ndsText = "В т.ч. НДС 20%: {$I28}";
        } else {
            $ndsText = 'Без НДС';
        }

        $additionals = [
            '{tableName}' => $tableName,
            '{price}' => number_format($this->we, 2, ",", ""),
            '{priceNoNds}' => number_format($I27, 2, ",", ""),
            '{nds}' => ArrayHelper::getValue($this, 'organization.nds') ? $I28 : 'Без НДС',
            '{ndsText}' => $ndsText,
            '{priceString}' => \php_rutils\RUtils::numeral()->getRubles($I29),
            '{numberNds}' => ArrayHelper::getValue($this, 'organization.nds') ? '1' : '2',
            '{flightNds}' => ArrayHelper::getValue($this, 'organization.nds') ? $I27 : $I29,
            '{flightNdsText}' =>ArrayHelper::getValue($this, 'organization.nds') ? '20%' : 'Без НДС',
            '{flightNdsText2}' =>ArrayHelper::getValue($this, 'organization.nds') ? $I28 : '-',
            '{requisite.name_case}' => ArrayHelper::getValue($this, 'organization.name_case'),
            '{client.name_case}' => ArrayHelper::getValue($this, 'zakazchik.name_case'),
            '{m}' => $rusMonth[$month],
            '{m2}' => date('d '.$rusMonth[$month].' Y г.', strtotime($this->date_cr)),
            '{y}' => date('Y', strtotime($this->date_cr)),
            '{cardString}' => $cardString,
            '{loading}' => ArrayHelper::getValue($loadingTypes, $this->loading_type).'/'.ArrayHelper::getValue($loadingTypes, $this->uploading_type),
            '{bodyType}' => ArrayHelper::getValue($bodyTypes, $this->body_type),
            '{cargo}' => $this->cargo_weight.' '.ArrayHelper::getValue(self::typeWeightLabels(), $this->type_weight),
            '{ensurance_yesno}' => $this->is_insurance ? "ДА" : "",
           //'{flightsCount}' => $this->flights_count ? $this->flights_count : 1
        ];

        foreach ($additionals as $attr => $value) {
            $text = str_replace($attr, $value, $text);
        }

        return $text;
    }  

    public function getAutoString()
    {
        if($this->auto){
            $driver = \app\models\Driver::findOne($this->auto);

            if($driver){
                return "{$driver->data_avto} {$driver->car_number} {$driver->car_truck_number}";
            }
        }
    }






}

