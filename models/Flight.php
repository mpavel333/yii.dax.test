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
 * @property string $ensurance_number Номер
 * @property string $ensurance_date Дата
 * @property string $ensurance_organization Организация/Страхователь
 * @property string $ensurance_contract Ген. договор страхования грузов
 * @property string $ensurance_contract_where Где?
 * @property string $ensurance_currency Валюта
 * @property string $ensurance_client Контрагент
 * @property string $ensurance_profit_subject Выгодоприобретатель
 * @property double $ensurance_sum Страховая сумма
 * @property double $ensurance_sum2 Страховая сумма
 * @property double $ensurance_prime Страховая премия
 * @property string $ensurance_transport_type Способ перевозки
 * @property string $ensurance_subject_from Регион отправки
 * @property string $ensurance_subject_to Регион прибытия
 * @property string $ensurance_country_from Страна отправки
 * @property string $ensurance_country_to Страна прибытия
 * @property string $ensurance_condition Условия страхования
 * @property double $ensurance_percent Процент
 * @property int $ensurance_ref Реф. риски
 * @property int $ensurance_security Охрана
 * @property string $ensurance_additional Доп. свед.
 * @property string $ensurance_order Заявка
 * @property string $ensurance_comment Комментарий
*/
class Flight extends \app\base\ActiveRecord
{
    public $countSalary = true;

    public $fileUploading;

    public $old_driver_id;
    public $is_register_old;

    public $old_act;

    public $old_act_date;

    public $old_carrier_id;
    public $old_date3;
    public $old_track_number_driver;    

    public $old_zakazchik_id;
    public $old_date2;
    public $old_track_number;

    public $old_is_register_letter;
    public $old_is_register_letter_driver;

    public $old_status;

    public $old_information_file_path;

    public $old_date;

    public $printClient;

    public $fl_we_sum;

    private $dataAttributes = [];


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
    const STATUS_WORKING_LAWYER = 8;

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'flight';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['organization_id', 'zakazchik_id', 'carrier_id', 'driver_id', 'bank', 'auto', 'rout', 'distance', 'date', 'flights_count', 'shipping_date', 'address1', 'address_out4', 'date_out4', 'we', 'payment_out', 'pay_us', 'payment1', 'col2', 'otherwise2', 'otherwise3', 'col1', 'date_cr', 'name_price'], 'required'],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requisite::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['zakazchik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['zakazchik_id' => 'id']],
            [['carrier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['carrier_id' => 'id']],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
            [['payment_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['payment_bank_id' => 'id']],
            [['organization_id', 'zakazchik_id', 'carrier_id', 'driver_id', 'is_payed', 'user_id',  'is_driver_payed', 'is_ati_driver', 'is_ati_client', 'status', 'created_by', 'order_check', 'date_cr_check', 'number_check', 'is_register', 'is_order', 'is_signature', 'is_driver_signature', 'bank_id', 'place_count', 'is_insurance', 'logging_truck', 'road_train', 'air_suspension', 'body_type', 'only_for_paid_users', 'payment_bank_id', 'is_salary_payed', 'ensurance_ref', 'ensurance_security', 'is_recoil_payment', 'is_ensurance_payment', 'is_additional_credit_payment', 'index', 'flights_count', 'is_metal', 'client_id', 'client_payment_days', 'is_delivery_document'], 'integer'],
            [['rout', 'order', 'driver_order', 'date', 'view_auto', 'address1', 'shipping_date', 'telephone1', 'type', 'date_out2', 'address_out2', 'contact_out2', 'name2', 'address_out3', 'date_out3', 'contact', 'name3', 'address_out4', 'date_out4', 'telephone', 'cargo_weight', 'name', 'address_out5', 'contact_out', 'date_out5', 'volume', 'address', 'date_out6', 'contact_out3', 'dop_informaciya_o_gruze', 'pay_us', 'payment1', 'col2', 'payment_out', 'otherwise2', 'otherwise3', 'col1', 'fio', 'date_cr','date_cr_prepayed', 'upd','upd_prepayed', 'date2', 'date3', 'your_text', 'otherwise4', 'otherwise', 'file', 'number', 'number_prepayed', 'file_provider', 'auto', 'created_at', 'car_number', 'act', 'act_date', 'track_number', 'track_number_driver', 'info_from_client', 'letter_info', 'letter_info_driver', 'contract_place', 'type_weight', 'loading_type', 'uploading_type', 'information', 'information_file_path', 'bank', 'ensurance_transport_type', 'ensurance_additional', 'ensurance_order', 'ensurance_comment', 'ensurance_number', 'ensurance_organization', 'ensurance_contract', 'ensurance_contract_where', 'ensurance_currency', 'ensurance_client', 'ensurance_profit_subject', 'ensurance_subject_from', 'ensurance_subject_to', 'ensurance_country_from', 'ensurance_country_to', 'ensurance_condition', 'bill_type_prepayed',
              'client_comment', 'client_payment_form', 'client_payment_type'], 'string'],
            [['is_register_letter', 'is_register_letter_driver', 'archive_datetime', 'success_datetime', 'shipping_date_2', 'date_out4_2', 'date_out2_2', 'date_out5_2', 'date_out3_2', 'date_out6_2', 'we', 'ensurance_date', 'we_prepayment_datetime', 'payment_out_prepayment_datetime', 'checks', 'checks1', 'checks2', 'checks3', 'is_salary_payed_datetime', 'transit', 'date2_next', 'date3_next', 'file_client'], 'safe'],
            [['salary', 'delta', 'delta_percent', 'recoil', 'distance', 'sum', 'daks_balance', 'we_prepayment', 'payment_out_prepayment', 'name_price', 'width', 'height', 'length', 'diameter', 'belts_count', 'priority_rate', 'priority_limit', 'priority_daily_limit', 'ensurance_sum', 'ensurance_sum2', 'ensurance_prime', 'ensurance_percent', 'additional_credit', 'we_prepayment_form', 'payment_out_prepayment_form', 'client_payment', 'client_payment_prepayment'], 'number'],
            ['upd', 'myUniqueValidator'],
            ['number', 'myUniqueValidatorNumber'],
            ['zakazchik', 'zakazchikValidator'],
            ['col2', 'col2Validator'],
            ['col1', 'col1Validator'],
            ['fileUploading', 'file'],
            [['date3'], 'dateValidator'],
            ['delta_percent', 'deltaPercentValidator', 'skipOnError' => false, 'skipOnEmpty' => false],
            ['recoil', 'recoilValidator'],
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



            if(doubleval($this->payment_out)){
                $salary = \app\models\Salary::find()->one();

                $one = doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);
                if(is_numeric($one) && is_numeric($one) && doubleval($one) > 0 && doubleval($this->payment_out)){
                    $one = doubleval($this->payment_out) / doubleval($one);
                    $one = $one - 1;
                    $one = $one * 100;
                    $one = 100 - $one;
                    $one = $one - 100;
    
                    //$this->delta_percent = round($one, 2);

                    //if(round($this->delta_percent)<10){
                    //    $this->addError('delta_percent', 'Дельта (процент) должен быть больше 10');
                    //}
    
                    $salaryAttr = 'delta_percent';
                    if($this->pay_us == 'без НДС' || $this->otherwise2 == 'без НДС'){
                        
                        \Yii::warning('in delta_percent_no_nds', 'in delta_percent_no_nds');
                        $salaryAttr = 'delta_percent_no_nds';
                    }
    
                    \Yii::warning("{$one} < {$salary->$salaryAttr}", 'delta condition');
                    if($one < $salary->$salaryAttr){
                        $salary->$salaryAttr = $one;
                        $this->addError('delta_percent', 'Оплата водителя выше');
                    }
                }
            }
        }
    }
/* OLD
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
*/

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
            'volume' => Yii::t('app', 'ОбЪём М3	'),
            'address' => Yii::t('app', 'Адрес Разгрузки3'),
            'date_out6' => Yii::t('app', 'Дата  Разгрузки3'),
            'contact_out3' => Yii::t('app', 'Контактное лицо и Телефон3'),
            'dop_informaciya_o_gruze' => Yii::t('app', 'Доп Информация о грузе'),
            'we' => Yii::t('app', 'Оплата от Заказчика'),
            'pay_us' => Yii::t('app', 'Форма оплаты'),
            'payment1' => Yii::t('app', 'Тип оплаты заказчика'),
            'col2' => Yii::t('app', 'Кол-во дней'),
            'payment_out' => Yii::t('app', 'Оплата Водителя'),
            'otherwise2' => Yii::t('app', 'Форма оплаты'),
            'otherwise3' => Yii::t('app', 'Тип оплаты перевозчика'),
            'col1' => Yii::t('app', 'Кол-во дней'),
            'fio' => Yii::t('app', 'Тип счета'),
            'date_cr' => Yii::t('app', 'Дата УПД/Счёт'),
            'date_cr_prepayed' => Yii::t('app', 'Дата счета предоплаты'),
            'number' => Yii::t('app', 'Счёт'),
            'number_prepayed' => Yii::t('app', 'Счёт аванс(предоплата)'),
            'upd' => Yii::t('app', 'УПД'),
            'upd_prepayed' => Yii::t('app', 'УПД аванс(предоплата)'),
            'date2' => Yii::t('app', 'Дата Письмо Заказчику'),
            'date3' => Yii::t('app', 'Дата Письмо от  Водителя'),
            'date3_' => Yii::t('app', 'Дата Письмо Перевозчика'),
            'recoil' => Yii::t('app', 'Баллы'),
            'your_text' => Yii::t('app', 'Ваш текст'),
            'otherwise4' => Yii::t('app', 'Иное условие  для Заказчика'),
            'otherwise' => Yii::t('app', 'Иное условие для Водителя	'),
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

            'client_comment' => Yii::t('app', 'Комментарий из таблицы Клиенты'),

            'letter_info' => \Yii::t('app', 'Информация письма Заказчику'),
            'letter_info_driver' => \Yii::t('app', 'Информация письма Водителю'),
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
            'bill_type_prepayed' => 'Предоплата',
            'index' => '#',
            'delta_percent' => 'Дельта (процент)',
            'we_prepayment_form' => 'Аванс',
            'payment_out_prepayment_form' => 'Аванс',
            'transit' => 'Перевозчик 2',
            'client_id' => 'Клиент',
            'is_delivery_document' => 'Документ об отгрузке',
            'date_from' => 'Дата ОТ',
            'date_to' => 'Дата ДО'
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
            $Salary = \app\models\Salary::find()->where(['id' => 1])->one();
            if($Salary && !empty($Salary->carrier_payment_type)):
                return explode(';', $Salary->carrier_payment_type);
            else:
                return [];
            endif;
        /*
        return [
            self::ABOUT38 => "по сканам ТТН  ТОРГ12 квитанция с почты РФ",
            self::ABOUT19 => "по оригиналам+сканы ТТН",
            self::ONTO34 => "на выгрузке",
            self::ABOUT13 => "по факту выгрузки",
            self::AFTER7 => "с момента доставки ОТТН и бух.док по адресу",
            self::SCANS => "по сканам",
        ];
         */
    }public static function otherwise2Labels() {
        return [
            self::WITHOUT_VAT1 => "без НДС",
            self::WITH_VAT1 => "с НДС",
            self::AT_STAKE1 => "на карту",
            self::IN_CASH1 => "Наличными",
        ];
    }public static function otherwise3Labels() {
            $Salary = \app\models\Salary::find()->where(['id' => 1])->one();
            if($Salary && !empty($Salary->customer_payment_type)):
                return explode(';', $Salary->customer_payment_type);
            else:
                return [];
            endif;        
        /*
        return [
            self::ABOUT49 => "по сканам ТТН  ТОРГ12 квитанция с почты РФ",
            self::ABOUT11 => "по оригиналам+сканы ТТН",
            self::ONTO41 => "на выгрузке",
            self::ABOUT28 => "по факту выгрузки",
            self::AFTER32 => "с момента доставки ОТТН и бух.док по адресу",
        ];
        */
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
            self::STATUS_WORKING_LAWYER => 'В работе у юриста',
        ];
    }

    public static function namesList()
    {
        return [
            'Автомобиль',
            'Автошины',
            'Алкогольные напитки',
            'Арматура',
            'Балки надрессорные',
            'Безалкогольные напитки',
            'Баковая рама',
            'Бумага',
            'Бытовая техника',
            'Бытовая химия',
            'Вагонка',
            'Газосиликатные блоки', 
            'Гипс',
            'Гофрокартон', 
            'Грибы',
            'ДВП',
            'ДСП',
            'Двери',
            'Домашний переезд',
            'Доски',
            'Древесина', 
            'Древесный уголь',
            'Ж/д запчасти (прочие)',
            'ЖБИ',
            'Животные',
            'Зерно и семена (в упаковке)',
            'Зерно и семена (насыпью)',
            'Игрушки',
            'Изделия из кожи',
            'Изделия из металла',
            'Изделия из резины',
            'Инструмент',
            'Кабель',
            'Конц. Товары',
            'Кирпич',
            'Ковры',
            'Колесная пара',
            'Компьютер',
            'Кондитерские изделия', 
            'Консервы',
            'Кормовые/пищевые добавки',
            'Крупа',
            'ЛДСП',
            'Люди',
            'Макулатура',
            'Мебель',
            'Медикаменты', 
            'Мел',
            'Металл',
            'Металлолом', 
            'Металлопрокат', 
            'Минвата',
            'Молоко сухое', 
            'Мороженое',
            'Мука',
            'Мясо',
            'Напитки', 
            'Нефтепродукты', 
            'Оборудование и запчасти',
            'Оборудование медицинское',
            'Обувь',
            'Овощи',
            'Огнеупорная продукция', 
            'Одежда',
            'Парфюмерия и косметика', 
            'Пенопласт',
            'Песок',
            'Пиво',
            'Пиломатериалы', 
            'Пластик',
            'Поглощающий аппарат', 
            'Поддоны',
            'Продукты питания', 
            'Профлист',
            'Птица',
            'Рыба (неживая)',
            'СОНК (КП)',
            'Сантехника',
            'Сахар',
            'Сборный груз',
            'Соки',
            'Соль',
            'Стекло и фарфор',
            'Стеклотара (бутылки и др.)',
            'Стройматериалы',
            'Сэндвич панели',
            'ТНП',
            'Табачные изделия', 
            'Тара и упаковка',
            'Текстиль',
            'Торф',
            'Транспортное средство',
            'Трубы',
            'Удобрения',
            'Утеплитель', 
            'Фанера',
            'Ферросплавы', 
            'Фрукты',
            'Хим. Продукты неопасные',
            'Хим. Продукты опасные',
            'Хозтовары',
            'Холодильное оборудование', 
            'Цветы',
            'Цемент',
            'Чипсы',
            'Шкуры мокросоленые', 
            'Шпалы',
            'Щебень',
            'Электроника', 
            'Ягоды',
            '20 контейнер', 
            '20 контейнер НС',
            '20 Реф. контейнер', 
            '20 Танк. контейнер',
            '40 контейнер',
            '40 контейнер НС',
            '40 Реф. контейнер',
            '40 Реф. контейнер НС',
            '40 Танк. контейнер', 
            '45 контейнер (нов.)',
            '45 контейнер (стар.)',
            '40 Реф. контейнер',
        ];
    }

    public function afterFind()
    {
        $this->old_driver_id = $this->driver_id;
        $this->is_register_old = $this->is_register;



        $this->old_act = $this->act;
        $this->old_act_date = $this->act_date;
        $this->old_carrier_id = $this->carrier_id;
        $this->old_date3 = $this->date3;
        $this->old_track_number_driver = $this->track_number_driver;
        $this->old_zakazchik_id = $this->zakazchik_id;
        $this->old_date2 = $this->date2;
        $this->old_track_number = $this->track_number;
        $this->old_is_register_letter = $this->is_register_letter;
        $this->old_is_register_letter_driver = $this->is_register_letter_driver;
        $this->old_information_file_path = $this->information_file_path;
    
        $this->old_status = $this->status;

        $this->old_date = $this->date;

        $this->dataAttributes = $this->attributes;
    
        // \Yii::warning($this->dataAttributes, '$this->dataAttributes');
    }


    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        if(isset($_POST['file_file_path'])){
            $this->file = json_decode($this->file, true);
            $newfile = json_decode($_POST['file_file_path'], true);
            $this->file = json_encode(\yii\helpers\ArrayHelper::merge($this->file ? $this->file : [], $newfile ? $newfile : []), JSON_UNESCAPED_UNICODE);
        }
        if(isset($_POST['file_provider_file_path'])){
            $this->file_provider = json_decode($this->file_provider, true);
            $newfile = json_decode($_POST['file_provider_file_path'], true);
            $this->file_provider = json_encode(\yii\helpers\ArrayHelper::merge($this->file_provider ? $this->file_provider : [], $newfile ? $newfile : []), JSON_UNESCAPED_UNICODE);
        }
        if(isset($_POST['file_file_client_path'])){
            $this->file_client = json_decode($this->file_client, true);
            $newfile = json_decode($_POST['file_file_client_path'], true);
            $this->file_client = json_encode(\yii\helpers\ArrayHelper::merge($this->file_client ? $this->file_client : [], $newfile ? $newfile : []), JSON_UNESCAPED_UNICODE);
        }

        $payment1Data = self::payment1Labels();
        if(in_array($this->payment1, $payment1Data) == false){
            $paymentTypeClient = PaymentTypeClient::find()->where(['name' => $this->payment1])->one();
            if($paymentTypeClient == null){
                $paymentTypeClient = new PaymentTypeClient(['name' => $this->payment1]);
                $paymentTypeClient->save(false);
            }
        }

        $payment1Data = self::otherwise3Labels();
        if(in_array($this->otherwise3, $payment1Data) == false){
            $paymentTypeClient = PaymentTypeDriver::find()->where(['name' => $this->otherwise3])->one();
            if($paymentTypeClient == null){
                $paymentTypeClient = new PaymentTypeDriver(['name' => $this->otherwise3]);
                $paymentTypeClient->save(false);
            }
        }


        if($this->isNewRecord){
            // $this->user_id = Yii::$app->user->getId();
            $this->created_by = Yii::$app->user->getId();
            // $this->index = Flight::find()->count() + 1;
        }

        // if($this->act && $this->act_date && $this->old_act == null && $this->old_act_date == null){
        // if($this->carrier_id && $this->date3 && $this->track_number_driver && ($this->old_carrier_id == null || $this->old_date3 == null && $this->old_track_number_driver == null)){
        if($this->carrier_id && $this->date3 && $this->track_number_driver && ($this->is_register_letter_driver && $this->old_is_register_letter_driver == false)){
            $this->date3 = date('Y-m-d');
            $mail = new Mail([
                'status' => Mail::STATUS_RECEIVED,
                'track' => $this->track_number_driver,
                'information' => $this->letter_info_driver,
                'when_send' => date('Y-m-d'),
                'when_receive' => date('Y-m-d'),
                'client_id' => $this->carrier_id,
            ]);
            $mail->save(false);
        }

        if($this->zakazchik_id && $this->date2 && $this->track_number && ($this->is_register_letter && $this->old_is_register_letter == false)){
            $this->date3 = date('Y-m-d');
            $cl = \app\models\Client::findOne($this->zakazchik_id);
            $mail = new Mail([
                'status' => Mail::STATUS_SENT,
                'organization_name' => $cl ? $cl->name : null,
                'information' => $this->letter_info,
                'track' => $this->track_number,
                'when_send' => date('Y-m-d'),
                'when_receive' => date('Y-m-d'),
                'client_id' => $this->zakazchik_id,
            ]);
            $mail->save(false);
        }

        if($this->auto){
            $driver = \app\models\Driver::find()->where(['data_avto' => $this->auto])->one();
            if($driver){
                $this->car_number = $driver->car_number;
            }
        }

        $salaryModel = \app\models\Salary::find()->one();
        $createdBy = \app\models\User::findOne($this->created_by);
        
        Yii::warning("{$this->countSalary} && ".((boolean) $salaryModel)." && ".is_numeric($this->we)." && ".is_numeric($salaryModel->percent_with_nds)." && ".is_numeric($salaryModel->percent_with_nds)." && ".is_numeric($this->recoil)." && ".is_numeric($this->recoil), 'in before save');
        
        if($this->countSalary && $salaryModel && is_numeric($this->we) && is_numeric($salaryModel->percent_with_nds) && is_numeric($salaryModel->percent_with_nds) && is_numeric($this->recoil) && is_numeric($this->recoil) && $createdBy){
            Yii::warning('inSalary', 'inSalary');
            $delta = 0;
            $salary = 0;
            $a1 = (100 - $salaryModel->percent)/100;
            $a2 = $salaryModel->percent_with_nds/100 + 1;
            $a3 = (100-$salaryModel->withdraw)/100;
            $percentSalary = 0;
            $we = $this->we;
            if($createdBy){
                $percentSalary = $createdBy->percent_salary;
                $percentSalary = explode('/', $percentSalary);
                // $percentSalary = (double) $percentSalary[0];
                // $percentSalaryNoNds = isset($percentSalary[1]) ? (double) $percentSalary[1] : null;
            }
            Yii::warning($this->pay_us, 'pay_us');
            Yii::warning($this->otherwise2, 'otherwise2');
            
            if($this->is_insurance){
                $result = ($this->name_price / 100) * 0.1;
                $we = $we - $result;
            }

            if($createdBy->percent == \app\models\User::PERCENT_CLASSIC || $createdBy->percent == \app\models\User::PERCENT_DYNAMIC || $createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'без НДС'){
                    // $percentSalary = isset($percentSalary[0]) ? (double) $percentSalary[0] : (double) $percentSalary[0];
                    $we = doubleval($we);
                    $a1 = doubleval($a1);
                    $a3 = doubleval($a3);
                    $delta = ($we * $a1 - ($this->payment_out + $this->payment_out * $salaryModel->percent_with_nds / (100 - $salaryModel->percent_with_nds))) * $a3 - $this->recoil;
                } elseif($this->pay_us == 'без НДС' && $this->otherwise2 == 'без НДС') {
                    // $percentSalary = (double) $percentSalary[0];
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'на карту' && $this->otherwise2 == 'на карту') {
                    // $percentSalary = 50;
                    $delta = ($we - $this->payment_out) - $this->recoil;
                } elseif($this->pay_us == 'без НДС' && $this->otherwise2 == 'на карту') {
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'с НДС' && $this->otherwise2 == 'с НДС') {
                    // $percentSalary = (double) $percentSalary[0];
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'с НДС' && $this->otherwise2 == 'на карту') {
                    $delta = ($we * $a1) * $a3 - $this->payment_out - $this->recoil;
                } elseif($this->pay_us == 'Наличными' && $this->otherwise2 == 'Наличными') {
                    // $percentSalary = 50;
                    $delta = $we - $this->payment_out;
                }
            } elseif($createdBy->percent == \app\models\User::PERCENT_PERCENT) {
                $delta = ($we - $this->payment_out) - $this->recoil;
            }
            

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if($this->pay_us == 'без НДС' && $this->otherwise2 == 'без НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'без НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($this->pay_us == 'без НДС' && $this->otherwise2 == 'с НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'c НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($this->pay_us == 'Наличными' && $this->otherwise2 == 'Наличными'){
                    $percentSalary = 50;
                }
                if($this->pay_us == 'на карту' && $this->otherwise2 == 'на карту'){
                    $percentSalary = 50;
                }
                $delta = ((double) $we - (double) $this->payment_out) - (double) $this->recoil;
            }

            // $delta = doubleval($this->we) - doubleval($this->payment_out) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);
            $delta = doubleval($this->we) - doubleval($this->payment_out) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);

            // \Yii::warning(doubleval($this->we)." - ".doubleval($this->payment_out)." - ".doubleval($this->recoil)." - ".doubleval($this->additional_credit)." - ".doubleval($this->ensurance_prime), 'delta salary');

            if($createdBy->percent == \app\models\User::PERCENT_DYNAMIC){
            	$start = date('Y-m-01');
            	$end = date('Y-m-t');
            	$flights = self::find()->where(['created_by' => $this->created_by])->andWhere(['between', 'date', $start, $end])->all();
                $delta2 = array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'we')); - array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'payment_out'));
                if($delta2 < 100000){
                    $percentSalary = 45; 
                } elseif($delta2 > 100000 && $delta2 <= 200000) {
                    $percentSalary = 50; 
                } elseif($delta2 > 200000 && $delta2 <= 300000) {
                    $percentSalary = 55;
                } elseif($delta2 > 300000) {
                    $percentSalary = 60;
                }
            }

            if(is_array($percentSalary)){
                $percentSalary = $percentSalary[0];
            }

            // if($this->otherwise2 == 'с НДС'){
            // } else {
                // $salary = ($delta / 100) * $percentSalaryNoNds; 
            // }

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                // $salary = ($this->we - $this->payment_out) / 100 * $percentSalary;
                if(is_numeric($percentSalary)){
                    // $salary = ($this->we - $this->payment_out - $this->recoil - $this->additional_credit) / 100 * $percentSalary;
                    \Yii::warning("{$percentSalary}", 'percentSalary');
                    \Yii::warning("{$delta} / 100 * {$percentSalary}", 'salary formula');
                    // $salary = round($delta - ($delta / 100 * $percentSalary)); 
                    $salary = round($delta / 100 * $percentSalary); 
                } else {
                    $salary = 0;
                }
            } else {
                $percentSalary = $createdBy->percent_salary; //
                if($percentSalary == null){
                	$percentSalary = 50;
                }
                \Yii::warning("({$delta} / 100) * {$percentSalary}", 'calc');
                if(is_numeric($delta) && is_numeric($percentSalary)){
                    $salary = ($delta / 100) * $percentSalary; 
                    // $salary = round($delta - ($delta / 100 * $percentSalary)); 
                }
            }



            Yii::warning($delta, 'delta');
            Yii::warning($salary, 'salary');
            $this->delta = $delta;
            $this->salary = $salary;
        }

        if($this->created_at == null && $this->is_register){
        	$this->created_at = date('Y-m-d H:i:s');
        }

        if(\Yii::$app->controller->module->id == 'mobile'){
            $datesAttrs = ['date', 'date_out2', 'date_out3', 'date_out4', 'date_out5', 'date_out6', 'date_cr', 'date2', 'date3', 'date_cr_check', 'act_date', 'shipping_date'];
            foreach($datesAttrs as $attr)
            {
                if($this->$attr)
                {
                    $dateValue = $this->$attr;
                    if($this->validateDate($dateValue) == false){
                        $dateValueArr = array_reverse(explode('-', $dateValue));
                        if(strlen($dateValueArr[2]) == 1){
                            $dateValueArr[2] = '0'.$dateValueArr[2];
                        }
                        $this->$attr = implode('-', $dateValueArr);
                    }
                }
            }
        }


        // if($this->isNewRecord){
            if($this->created_by && $this->isNewRecord){
                $createdBy = \app\models\User::findOne($this->created_by);
                if($createdBy)
                {
                    if($this->daks_balance){
                        $createdBy->daks_balance = doubleval($createdBy->daks_balance) - $this->daks_balance;
                        $this->we = $this->we - $this->daks_balance;
                        // $this->recoil = $createdBy->daks_balance;
                    } else {
                        $flightDaks = doubleval($this->we) * 0.01;
                        $createdBy->daks_balance = doubleval($createdBy->daks_balance) + $flightDaks;
                        // $this->recoil = $flightDaks;
                    }
                    $createdBy->save(false);
                }
            }
        // }

        if($this->old_information_file_path != $this->information_file_path && $this->old_information_file_path == null){

            $url = \yii\helpers\Url::toRoute($this->information_file_path, true);
            \Yii::warning($url, 'information url');
            $jsonResponse = json_decode(file_get_contents('http://188.225.38.17:9011/link/https://bl.teo-app.ru/test.ogg'), true);

            \Yii::warning($jsonResponse, '$jsonResponse');

            if(isset($jsonResponse['text'])){
                $this->information = $jsonResponse['text'];
            }
        }

        if($this->is_register && $this->is_register_old != $this->is_register){
            $this->status = 1;
            $this->user_id = \Yii::$app->user->getId();


            if($this->upd == null && $this->number == null){
                $setting = \app\models\Setting::find()->one();
                $this->upd = $setting->flight_index;
                $this->number = $setting->flight_index;
                $setting->flight_index = doubleval($setting->flight_index) + 1;
                $setting->save(false);
            }
        }

        /*
        if($this->payment_out){
            $salary = \app\models\Salary::find()->one();
            // $one = round((doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime)) / ($this->payment_out - 1) * 100);
            // $one = round((doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime)) / ($this->payment_out - 1) * 100);
            $one = doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);
            $one = $one / $this->payment_out;
            $one = $one - 1;
            $one = $one * 100;
            $this->delta_percent = round($one, 2);
            // $one = round(($one / 100 * $salary->delta_percent_additional));
        }
        */

        //if(round($this->delta_percent)<10){
        //    \Yii::warning($this->delta_percent, 'delta_percent');
        //}

        if($this->payment_out){
            $salary = \app\models\Salary::find()->one();
            // $one = round((doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime)) / ($this->payment_out - 1) * 100);
            // $one = round((doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime)) / ($this->payment_out - 1) * 100);

            // $one = doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);
            // $one = $one / $this->payment_out;
            // $one = $one - 1;
            // $one = $one * 100;

            $one = doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);

            $one = doubleval($this->payment_out) / doubleval($one);
            $one = $one - 1;
            $one = $one * 100;
            $one = 100 - $one;
            $one = $one - 100;

            $this->delta_percent = round($one, 2);
            // $one = round(($one / 100 * $salary->delta_percent_additional));
        }

        //echo round($this->delta_percent); //die;

        //print_r($_POST);

        //if(isset($_POST['']) && round($this->delta_percent)<10){
        //    echo 111;
        //    \Yii::warning($this->delta_percent, 'delta_percent');
        //}

        //die;


        // // if($this->driver_id != $this->old_driver_id){
        // if($this->driver_id != $this->old_driver_id){
        //     if($this->driver_id){
        //         $driver = \app\models\Driver::findOne($this->driver_id);
        //         if($driver){
        //             $this->auto = $driver->data_avto;
        //         }
        //     }
        // }

        if($this->date2 && $this->col2) {
            $date2 = new \DateTime($this->date2);

            if(mb_stripos($this->col2, '-') !== false){
              $col2 = explode('-', $this->col2);
            } elseif(mb_stripos($this->col2, '+') !== false){
              $col2 = explode('+', $this->col2);
            } else {
              $col2 = [$this->col2];
            }
            $col2 = $col2[count($col2)-1];


            try {
              // $date2->modify("+{$col2} days");
              while($col2 > 0){
                $date2->modify("+1 days");
                $w = $date2->format('w');
                if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
                  $col2--;
                }
              }
            } catch(\Exception $e){
              \Yii::warning($this->upd, 'upd');                  
            }



            $date2 = $date2->format('Y-m-d');
        
            $this->date2_next = $date2;
        
        }

        if($this->date3 && $this->col1) {

            $date2 = new \DateTime($this->date3);

            if(mb_stripos($this->col1, '-') !== false){
              $col1 = explode('-', $this->col1);
            } elseif(mb_stripos($this->col1, '+') !== false){
              $col1 = explode('+', $this->col1);
            } else {
              $col1 = [$this->col1];
            }
            $col1 = $col1[count($col1)-1];


            while($col1 > 0){
              $date2->modify("+1 days");
              $w = $date2->format('w');
              if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
                $col1--;
              }
            }

            // $date2->modify("+{$col1} days");

            // $w = $date2->format('w');

            // if($w == 6){
            //     $date2->modify("+2 days");
            // }

            // if($w == 0){
            //     $date2->modify("+1 days");
            // }

            // // if(\app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one()){
            // //   $date2->modify("+1 days"); 
            // // }
            // \app\models\Holiday::nextDate($date2);


            $date2 = $date2->format('Y-m-d');

            $this->date3_next = $date2;


          }
          
          
        //print_r($this['_attributes']); die;  
          
          

        return parent::beforeSave($insert);
    }

    public function calculateDelta($user)
    {
        $salaryModel = \app\models\Salary::find()->one();
        if($this->countSalary && $salaryModel && is_numeric($this->we) && is_numeric($salaryModel->percent_with_nds) && is_numeric($salaryModel->percent_with_nds) && is_numeric($this->recoil) && is_numeric($this->recoil) && $user){
            Yii::warning('inSalary', 'inSalary');
            
            $createdBy = $user;

            $delta = 0;
            $salary = 0;
            $a1 = (100 - $salaryModel->percent)/100;
            $a2 = $salaryModel->percent_with_nds/100 + 1;
            $a3 = (100-$salaryModel->withdraw)/100;
            $percentSalary = 0;
            $we = $this->we;
            if($createdBy){
                $percentSalary = $createdBy->percent_salary;
                $percentSalary = explode('/', $percentSalary);
                // $percentSalary = (double) $percentSalary[0];
                // $percentSalaryNoNds = isset($percentSalary[1]) ? (double) $percentSalary[1] : null;
            }
            Yii::warning($this->pay_us, 'pay_us');
            Yii::warning($this->otherwise2, 'otherwise2');
            
            if($this->is_insurance){
                $result = ($this->name_price / 100) * 0.1;
                $we = $we - $result;
            }

            if($createdBy->percent == \app\models\User::PERCENT_CLASSIC || $createdBy->percent == \app\models\User::PERCENT_DYNAMIC || $createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF || $createdBy->percent == \app\models\User::PERCENT_PERCENT_DISPATCH){
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'без НДС'){
                    // $percentSalary = isset($percentSalary[0]) ? (double) $percentSalary[0] : (double) $percentSalary[0];
                    $delta = ($we * $a1 - ($this->payment_out + $this->payment_out * $salaryModel->percent_with_nds / (100 - $salaryModel->percent_with_nds))) * $a3 - $this->recoil;
                } elseif($this->pay_us == 'без НДС' && $this->otherwise2 == 'без НДС') {
                    // $percentSalary = (double) $percentSalary[0];
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'на карту' && $this->otherwise2 == 'на карту') {
                    // $percentSalary = 50;
                    $delta = ($we - $this->payment_out) - $this->recoil;
                } elseif($this->pay_us == 'без НДС' && $this->otherwise2 == 'на карту') {
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'с НДС' && $this->otherwise2 == 'с НДС') {
                    // $percentSalary = (double) $percentSalary[0];
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'с НДС' && $this->otherwise2 == 'на карту') {
                    $delta = ($we * $a1) * $a3 - $this->payment_out - $this->recoil;
                } elseif($this->pay_us == 'Наличными' && $this->otherwise2 == 'Наличными') {
                    // $percentSalary = 50;
                    $delta = $we - $this->payment_out;
                }
            } elseif($createdBy->percent == \app\models\User::PERCENT_PERCENT) {
                $delta = ($we - $this->payment_out) - $this->recoil;
            }
            

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if($this->pay_us == 'без НДС' && $this->otherwise2 == 'без НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'без НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($this->pay_us == 'без НДС' && $this->otherwise2 == 'с НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'c НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($this->pay_us == 'Наличными' && $this->otherwise2 == 'Наличными'){
                    $percentSalary = 50;
                }
                if($this->pay_us == 'на карту' && $this->otherwise2 == 'на карту'){
                    $percentSalary = 50;
                }
                $delta = ($we - $this->payment_out) - $this->recoil;
            }

            if($createdBy->percent == \app\models\User::PERCENT_DYNAMIC){
                $start = date('Y-m-01');
                $end = date('Y-m-t');
                $flights = self::find()->where(['created_by' => $this->created_by])->andWhere(['between', 'date', $start, $end])->all();
                $delta2 = array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'we')); - array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'payment_out'));
                if($delta2 < 100000){
                    $percentSalary = 45; 
                } elseif($delta2 > 100000 && $delta2 <= 200000) {
                    $percentSalary = 50; 
                } elseif($delta2 > 200000 && $delta2 <= 300000) {
                    $percentSalary = 55;
                } elseif($delta2 > 300000) {
                    $percentSalary = 60;
                }
            }

            if(is_array($percentSalary)){
                $percentSalary = $percentSalary[0];
            }

            // if($this->otherwise2 == 'с НДС'){
            // } else {
                // $salary = ($delta / 100) * $percentSalaryNoNds; 
            // }

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                $salary = ($this->we - $this->payment_out) / 100 * $percentSalary;
            } else {
                $percentSalary = $createdBy->percent_salary; //
                if($percentSalary == null){
                    $percentSalary = 50;
                }
                \Yii::warning("({$delta} / 100) * {$percentSalary}", 'calc');
                $salary = ($delta / 100) * $percentSalary; 
            }



            Yii::warning($delta, 'delta');
            Yii::warning($salary, 'salary');
            $this->delta = $delta;
            $this->salary = $salary;

            return $delta;
        }
    }

    public function calculateSalary($user)
    {
        $salaryModel = \app\models\Salary::find()->one();
        if($this->countSalary && $salaryModel && is_numeric($this->we) && is_numeric($salaryModel->percent_with_nds) && is_numeric($salaryModel->percent_with_nds) && is_numeric($this->recoil) && is_numeric($this->recoil) && $user){
            Yii::warning('inSalary', 'inSalary');
            
            $createdBy = $user;

            $delta = 0;
            $salary = 0;
            $a1 = (100 - $salaryModel->percent)/100;
            $a2 = $salaryModel->percent_with_nds/100 + 1;
            $a3 = (100-$salaryModel->withdraw)/100;
            $percentSalary = 0;
            $we = $this->we;
            if($createdBy){
                $percentSalary = $createdBy->percent_salary;
                $percentSalary = explode('/', $percentSalary);
                // $percentSalary = (double) $percentSalary[0];
                // $percentSalaryNoNds = isset($percentSalary[1]) ? (double) $percentSalary[1] : null;
            }
            Yii::warning($this->pay_us, 'pay_us');
            Yii::warning($this->otherwise2, 'otherwise2');
            
            if($this->is_insurance){
                $result = ($this->name_price / 100) * 0.1;
                $we = $we - $result;
            }

            if($createdBy->percent == \app\models\User::PERCENT_CLASSIC || $createdBy->percent == \app\models\User::PERCENT_DYNAMIC || $createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'без НДС'){
                    // $percentSalary = isset($percentSalary[0]) ? (double) $percentSalary[0] : (double) $percentSalary[0];
                    $delta = ($we * $a1 - ($this->payment_out + $this->payment_out * $salaryModel->percent_with_nds / (100 - $salaryModel->percent_with_nds))) * $a3 - $this->recoil;
                } elseif($this->pay_us == 'без НДС' && $this->otherwise2 == 'без НДС') {
                    // $percentSalary = (double) $percentSalary[0];
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'на карту' && $this->otherwise2 == 'на карту') {
                    // $percentSalary = 50;
                    $delta = ($we - $this->payment_out) - $this->recoil;
                } elseif($this->pay_us == 'без НДС' && $this->otherwise2 == 'на карту') {
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'с НДС' && $this->otherwise2 == 'с НДС') {
                    // $percentSalary = (double) $percentSalary[0];
                    $delta = ((($we * $a1) - $this->payment_out) * $a3) - $this->recoil;
                } elseif($this->pay_us == 'с НДС' && $this->otherwise2 == 'на карту') {
                    $delta = ($we * $a1) * $a3 - $this->payment_out - $this->recoil;
                } elseif($this->pay_us == 'Наличными' && $this->otherwise2 == 'Наличными') {
                    // $percentSalary = 50;
                    $delta = $we - $this->payment_out;
                }
            } elseif($createdBy->percent == \app\models\User::PERCENT_PERCENT) {
                $delta = ($we - $this->payment_out) - $this->recoil;
            }
            

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                if($this->pay_us == 'без НДС' && $this->otherwise2 == 'без НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'без НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($this->pay_us == 'без НДС' && $this->otherwise2 == 'с НДС'){
                    $percentSalary = isset($percentSalary[1]) ? (double) $percentSalary[1] : (double) $percentSalary[0];
                }
                if($this->pay_us == 'с НДС' && $this->otherwise2 == 'c НДС'){
                    $percentSalary = (double) $percentSalary[0];
                }
                if($this->pay_us == 'Наличными' && $this->otherwise2 == 'Наличными'){
                    $percentSalary = 50;
                }
                if($this->pay_us == 'на карту' && $this->otherwise2 == 'на карту'){
                    $percentSalary = 50;
                }
                $delta = ($we - $this->payment_out) - $this->recoil;
            }

            if($createdBy->percent == \app\models\User::PERCENT_DYNAMIC){
                $start = date('Y-m-01');
                $end = date('Y-m-t');
                $flights = self::find()->where(['created_by' => $this->created_by])->andWhere(['between', 'date', $start, $end])->all();
                $delta2 = array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'we')); - array_sum(\yii\helpers\ArrayHelper::getColumn($flights, 'payment_out'));
                if($delta2 < 100000){
                    $percentSalary = 45; 
                } elseif($delta2 > 100000 && $delta2 <= 200000) {
                    $percentSalary = 50; 
                } elseif($delta2 > 200000 && $delta2 <= 300000) {
                    $percentSalary = 55;
                } elseif($delta2 > 300000) {
                    $percentSalary = 60;
                }
            }

            if(is_array($percentSalary)){
                $percentSalary = $percentSalary[0];
            }

            // if($this->otherwise2 == 'с НДС'){
            // } else {
                // $salary = ($delta / 100) * $percentSalaryNoNds; 
            // }

            if($createdBy->percent == \app\models\User::PERCENT_PERCENT_HALF){
                $salary = ($this->we - $this->payment_out) / 100 * $percentSalary;
            } else {
                $percentSalary = $createdBy->percent_salary; //
                if($percentSalary == null){
                    $percentSalary = 50;
                }
                \Yii::warning("({$delta} / 100) * {$percentSalary}", 'calc');
                $salary = (doubleval($delta) / 100) * doubleval($percentSalary);
            }



            Yii::warning($delta, 'delta');
            Yii::warning($salary, 'salary');
            $this->delta = $delta;
            $this->salary = $salary;

            return $salary;
        }
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function afterSave($insert, $changedAttributes)
    {
        // <Telegram-уведомление>
        if($insert){
            $security = \app\models\Security::find()->one();
            $text = "\xF0\x9F\x93\x84 Новая заявка *№{$this->id}*\n";
            $text .= "*От:* ".ArrayHelper::getValue($this, 'zakazchik.name')."\n";
            $text .= "*Телефон:* ".ArrayHelper::getValue($this, 'zakazchik.tel')."\n";
            $text .= "*Маршрут:* ".ArrayHelper::getValue($this, 'rout')."\n";
            $text .= "*Дата загрузки:* ".ArrayHelper::getValue($this, 'shipping_date')."\n";
            $text .= "*Контакт:* ".ArrayHelper::getValue($this, 'contact_out2')."\n";
            $text .= "*Дата выгрузки:* ".ArrayHelper::getValue($this, 'date_out4')."\n";
            $text .= "*Адрес выгрузки:* ".ArrayHelper::getValue($this, 'address_out4')."\n";
            $text .= "*Груз:* ".ArrayHelper::getValue($this, 'name')."\n";
            $text .= "*Вес:* ".ArrayHelper::getValue($this, 'cargo_weight')."\n";
            $text .= "*Объем:* ".ArrayHelper::getValue($this, 'volume')."\n";
            $text .= "*Тип загрузки/выгрузки:* ".ArrayHelper::getValue($this, 'type')."\n";
            $text .= "*Вид перевозки:* ".ArrayHelper::getValue($this, 'view_auto')."\n";
            $text .= "*Упаковка:* ".ArrayHelper::getValue($this, 'name2')."\n";
            $text .= "*Доп.Информация:* ".ArrayHelper::getValue($this, 'dop_informaciya_o_gruze')."\n";
            $text .= "*Сумма:* ".ArrayHelper::getValue($this, 'we')."\n";
            $this->getReq($security->token, 'sendMessage', [
                'chat_id' => $security->admin_id,
                // 'chat_id' => "300640816",
                'text' => $text,
                'parse_mode' => 'markdown'
            ]);
        }
        if($this->status != $this->old_status && $this->status == self::STATUS_WORKING){
            $security = \app\models\Security::find()->one();
            $text = "\xF0\x9F\x93\x84 Заявка *№{$this->id}* в работе\n";
            $text .= "*От:* ".ArrayHelper::getValue($this, 'user.name')."\n";
            $text .= "*Телефон:* ".ArrayHelper::getValue($this, 'user.phone')."\n";
            $text .= "*Статус:* ".ArrayHelper::getValue(self::statusLabels(), 'status')."\n";
            $text .= "*Маршрут:* ".ArrayHelper::getValue($this, 'rout')."\n";
            $text .= "*Водитель:* ".ArrayHelper::getValue($this, 'driver.data')."\n";
            $text .= "*Авто:* ".ArrayHelper::getValue($this, 'driver.data_avto')." ".ArrayHelper::getValue($this, 'driver.car_number')."\n";
            $text .= "*Телефон водителя:* ".ArrayHelper::getValue($this, 'driver.phone')."\n";

            $text .= "$this->rout\n";
            $this->getReq($security->token, 'sendMessage', [
                'chat_id' => $security->admin_id,
                // 'chat_id' => "300640816",
                'text' => $text,
                'parse_mode' => 'markdown'
            ]);
        }
        // if($this->is_register != $this->is_register_old && $this->is_register == 1){
        //     $security = \app\models\Security::find()->one();
        //     $text = "\xF0\x9F\x93\x84 Заявка *№{$this->id}* зарегистрирована\n";
        //     $text .= "*Телефон:* ".ArrayHelper::getValue($this, 'user.phone')."\n";
        //     $text .= "$this->rout\n";
        //     $text .= "*Водитель:* ".ArrayHelper::getValue($this, 'driver.data')."\n";
        //     $text .= "*Авто:* ".ArrayHelper::getValue($this, 'driver.data_avto')." ".ArrayHelper::getValue($this, 'driver.car_number')."\n";
        //     $text .= "*Телефон:* ".ArrayHelper::getValue($this, 'driver.phone')."\n";
        //     $this->getReq($security->token, 'sendMessage', [
        //         'chat_id' => $security->admin_id,
        //         'text' => $text,
        //         'parse_mode' => 'markdown'
        //     ]);
        // }
        

        //
        // \Yii::warning($this->dataAttributes, '$this->dataAttributes');
        
        $data = [];
        foreach($this->attributes as $attribute => $value)
        {
            foreach($this->dataAttributes as $oldAttr => $oldValue){
                if($attribute == $oldAttr && $oldValue != $value){
                    $data[$attribute] = $value;
                }
            }
        }
        if(count($data) > 0){
            $history = new \app\models\FlightHistory();
            $history->create_at = date('Y-m-d H:i:s');
            $history->user_id = \Yii::$app->user->getId();
            $history->flight_id = $this->id;
            $history->data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $history->save(false);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function getReq($token, $method, $params = [], $decoded = 0)
    { //параметр 1 это метод, 2 - это массив параметров к методу, 3 - декодированный ли будет результат будет или нет.

        $url = "https://api.telegram.org/bot{$token}/{$method}"; //основная строка и метод
        if (count($params)) {
            $url = $url . '?' . http_build_query($params);//к нему мы прибавляем парметры, в виде GET-параметров
        }

        $curl = curl_init($url);    //инициализируем curl по нашему урлу
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,
            1);   //здесь мы говорим, чтобы запром вернул нам ответ сервера телеграмма в виде строки, нежели напрямую.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   //Не проверяем сертификат сервера телеграмма.
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $result = curl_exec($curl);   // исполняем сессию curl
        curl_close($curl); // завершаем сессию

        // if (strpos('"message_id":', $result) !== false) {
        $str1=strpos($result, '{');
        $result2=substr($result, $str1);
        // }
        //        var_dump($result);
        $result2 = json_decode($result2, true);


        if (isset($result2['result'])){
            \Yii::warning($result2);
            if (isset($result2['result']['message_id'])) {
                return $result2['result']['message_id'];
            }
        } else {
            \Yii::warning($result);
        }


        // if (isset($result["message"])) {
        //     $text = isset($result["message"]["text"]) ? $result["message"]["text"] : ""; //Текст сообщения
        //     $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
        //     $username = $result["message"]["chat"]["username"]; //Уникальный идентификатор пользователя
        //     $name = $result["message"]["from"]["first_name"]; //Юзернейм пользователя
        // }

        return false; //Или просто возращаем ответ в виде строки
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->created_by){
            $createdBy = \app\models\User::findOne($this->created_by);
            if($createdBy)
            {
                if($this->daks_balance){
                    $createdBy->daks_balance = doubleval($createdBy->daks_balance) + $this->daks_balance;
                } else {
                    $flightDaks = doubleval($this->we) * 0.01;
                    $createdBy->daks_balance = doubleval($createdBy->daks_balance) - $flightDaks;
                }
                $createdBy->save(false);
            }
        }
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
        
        if($this->bill_type_prepayed == 'Предоплата'){
             $tableName = "Предоплата за транспортные услуги по заявке №{$this->order} от ".\Yii::$app->formatter->asDate($this->date, 'php:d.m.Y').", маршрут {$this->rout}, Авто ".ArrayHelper::getValue($this, 'autoString').", Водитель ".ArrayHelper::getValue($this, 'driver.data');
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
            '{flightsCount}' => $this->flights_count ? $this->flights_count : 1
        ];

        foreach ($additionals as $attr => $value) {
            $text = str_replace($attr, $value, $text);
        }
        
        
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
