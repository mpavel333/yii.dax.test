<?php 
namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name Название
 * @property integer $requisite_name Реквизиты Наименование
 * @property integer $requisite_doljnost_rukovoditelya Реквизиты Должность руководителя
 * @property integer $requisite_fio_polnostyu Реквизиты ФИО полностью
 * @property integer $requisite_official_address Реквизиты Юридический адрес
 * @property integer $requisite_bank_name Реквизиты Наименование банка
 * @property integer $requisite_inn Реквизиты ИНН
 * @property integer $requisite_kpp Реквизиты КПП
 * @property integer $requisite_ogrn Реквизиты ОГРН
 * @property integer $requisite_bic Реквизиты Бик
 * @property integer $requisite_kr Реквизиты КР
 * @property integer $requisite_nomer_rascheta Реквизиты Номер расчета
 * @property integer $requisite_tel Реквизиты тел.
 * @property integer $requisite_fio_buhgaltera Реквизиты ФИО бухгалтера
 * @property integer $requisite_nds Реквизиты НДС
 * @property integer $requisite_pechat Реквизиты Печать
 * @property integer $client_name Организации Наименование
 * @property integer $client_doljnost_rukovoditelya Организации Должность руководителя
 * @property integer $client_fio_polnostyu Организации ФИО полностью
 * @property integer $client_official_address Организации Юридический адрес
 * @property integer $client_bank_name Организации Наименование банка
 * @property integer $client_inn Организации ИНН
 * @property integer $client_kpp Организации КПП
 * @property integer $client_ogrn Организации ОГРН
 * @property integer $client_bic Организации Бик
 * @property integer $client_kr Организации КР
 * @property integer $client_nomer_rascheta Организации Номер расчета
 * @property integer $client_tel Организации тел.
 * @property integer $client_email Организации email
 * @property integer $client_nds Организации НДС
 * @property integer $client_doc Организации Договор
 * @property integer $client_mailing_address Организации Почтовый адрес
 * @property integer $client_code Организации Код АТИ
 * @property integer $driver_data Водители Водитель ФИО
 * @property integer $driver_driver Водители Водитель паспортные данные
 * @property integer $driver_phone Водители Телефон
 * @property integer $driver_data_avto Водители Данные автомобиля
 * @property integer $flight_organization_id Рейсы Организация Наша
 * @property integer $flight_zakazchik_id Рейсы Заказчик
 * @property integer $flight_carrier_id Рейсы Перевозчик
 * @property integer $flight_driver_id Рейсы Водитель
 * @property integer $flight_rout Рейсы Маршрут
 * @property integer $flight_order Рейсы Заявка
 * @property integer $flight_date Рейсы Дата заявки
 * @property integer $flight_view_auto Рейсы Вид автоперевозчика
 * @property integer $flight_address1 Рейсы Адрес погрузки1
 * @property integer $flight_shipping_date Рейсы Дата погрузки
 * @property integer $flight_telephone1 Рейсы Телефон погрузки
 * @property integer $flight_type Рейсы Тип загрузки/выгрузки
 * @property integer $flight_date_out2 Рейсы Дата Загрузке2
 * @property integer $flight_address_out2 Рейсы Адрес Загрузке2
 * @property integer $flight_contact_out2 Рейсы Контактное лицо и Телефон2
 * @property integer $flight_name2 Рейсы Упаковка
 * @property integer $flight_address_out3 Рейсы Адрес Загрузке3
 * @property integer $flight_date_out3 Рейсы Дата Загрузке3
 * @property integer $flight_contact Рейсы Контактное лицо и Телефон3
 * @property integer $flight_name3 Рейсы Наименование груза3
 * @property integer $flight_address_out4 Рейсы Адрес разгрузки
 * @property integer $flight_date_out4 Рейсы Дата разгрузки
 * @property integer $flight_telephone Рейсы Телефон разгрузки
 * @property integer $flight_cargo_weight Рейсы вес груза
 * @property integer $flight_name Рейсы Наименование груза
 * @property integer $flight_address_out5 Рейсы Адрес Разгрузке2
 * @property integer $flight_contact_out Рейсы Контактное лицо и Телефон2
 * @property integer $flight_date_out5 Рейсы Дата  Разгрузке2
 * @property integer $flight_volume Рейсы ОбЪём М3	
 * @property integer $flight_address Рейсы Адрес Разгрузке3
 * @property integer $flight_date_out6 Рейсы Дата  Разгрузке3
 * @property integer $flight_contact_out3 Рейсы Контактное лицо и Телефон3
 * @property integer $flight_dop_informaciya_o_gruze Рейсы Доп Информация о грузе
 * @property integer $flight_we Рейсы Оплата от Заказчика
 * @property integer $flight_pay_us Рейсы Форма оплаты
 * @property integer $flight_payment1 Рейсы Тип оплаты
 * @property integer $flight_col2 Рейсы Кол-во дней
 * @property integer $flight_payment_out Рейсы Оплата Водителя
 * @property integer $flight_otherwise2 Рейсы Форма оплаты
 * @property integer $flight_otherwise3 Рейсы Тип оплаты
 * @property integer $flight_col1 Рейсы Кол-во дней
 * @property integer $flight_fio Рейсы Тип счета
 * @property integer $flight_date_cr Рейсы Дата
 * @property integer $flight_number Рейсы Счёт
 * @property integer $flight_upd Рейсы УПД
 * @property integer $flight_date2 Рейсы Дата Письмо Заказчику
 * @property integer $flight_date3 Рейсы Дата Письмо от  Водителя
 * @property integer $flight_recoil Рейсы Откат
 * @property integer $flight_your_text Рейсы Ваш текст
 * @property integer $flight_otherwise4 Рейсы Иное условие  для Заказчика
 * @property integer $flight_otherwise Рейсы Иное условие для Водителя	
 * @property integer $flight_file Рейсы Файлы
 * @property integer $books Справочники
 *
 * @property User[] $users
 */
class ReportColumn extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_column';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requisite_name', 'requisite_doljnost_rukovoditelya', 'requisite_fio_polnostyu', 'requisite_official_address', 'requisite_bank_name', 'requisite_inn', 'requisite_kpp', 'requisite_ogrn', 'requisite_bic', 'requisite_kr', 'requisite_nomer_rascheta', 'requisite_tel', 'requisite_fio_buhgaltera', 'requisite_nds', 'requisite_pechat', 'client_name', 'client_doljnost_rukovoditelya', 'client_fio_polnostyu', 'client_official_address', 'client_bank_name', 'client_inn', 'client_kpp', 'client_ogrn', 'client_bic', 'client_kr', 'client_nomer_rascheta', 'client_tel', 'client_email', 'client_nds', 'client_doc', 'client_mailing_address', 'client_code', 'driver_data', 'driver_driver', 'driver_phone', 'driver_data_avto', 'flight_organization_id', 'flight_zakazchik_id', 'flight_carrier_id', 'flight_driver_id', 'flight_rout', 'flight_order', 'flight_date', 'flight_view_auto', 'flight_address1', 'flight_shipping_date', 'flight_telephone1', 'flight_type', 'flight_date_out2', 'flight_address_out2', 'flight_contact_out2', 'flight_name2', 'flight_address_out3', 'flight_date_out3', 'flight_contact', 'flight_name3', 'flight_address_out4', 'flight_date_out4', 'flight_telephone', 'flight_cargo_weight', 'flight_name', 'flight_address_out5', 'flight_contact_out', 'flight_date_out5', 'flight_volume', 'flight_address', 'flight_date_out6', 'flight_contact_out3', 'flight_dop_informaciya_o_gruze', 'flight_we', 'flight_pay_us', 'flight_payment1', 'flight_col2', 'flight_payment_out', 'flight_otherwise2', 'flight_otherwise3', 'flight_col1', 'flight_fio', 'flight_date_cr', 'flight_number', 'flight_upd', 'flight_date2', 'flight_date3', 'flight_recoil', 'flight_your_text', 'flight_otherwise4', 'flight_otherwise', 'flight_file'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'requisite_name' => 'Наименование',
            'requisite_doljnost_rukovoditelya' => 'Должность руководителя',
            'requisite_fio_polnostyu' => 'ФИО полностью',
            'requisite_official_address' => 'Юридический адрес',
            'requisite_bank_name' => 'Наименование банка',
            'requisite_inn' => 'ИНН',
            'requisite_kpp' => 'КПП',
            'requisite_ogrn' => 'ОГРН',
            'requisite_bic' => 'Бик',
            'requisite_kr' => 'КР',
            'requisite_nomer_rascheta' => 'Номер расчета',
            'requisite_tel' => 'тел.',
            'requisite_fio_buhgaltera' => 'ФИО бухгалтера',
            'requisite_nds' => 'НДС',
            'requisite_pechat' => 'Печать',
            'client_name' => 'Наименование',
            'client_doljnost_rukovoditelya' => 'Должность руководителя',
            'client_fio_polnostyu' => 'ФИО полностью',
            'client_official_address' => 'Юридический адрес',
            'client_bank_name' => 'Наименование банка',
            'client_inn' => 'ИНН',
            'client_kpp' => 'КПП',
            'client_ogrn' => 'ОГРН',
            'client_bic' => 'Бик',
            'client_kr' => 'КР',
            'client_nomer_rascheta' => 'Номер расчета',
            'client_tel' => 'тел.',
            'client_email' => 'email',
            'client_nds' => 'НДС',
            'client_doc' => 'Договор',
            'client_mailing_address' => 'Почтовый адрес',
            'client_code' => 'Код АТИ',
            'driver_data' => 'Водитель ФИО',
            'driver_driver' => 'Водитель паспортные данные',
            'driver_phone' => 'Телефон',
            'driver_data_avto' => 'Данные автомобиля',
            'flight_organization_id' => 'Организация Наша',
            'flight_zakazchik_id' => 'Заказчик',
            'flight_carrier_id' => 'Перевозчик',
            'flight_driver_id' => 'Водитель',
            'flight_rout' => 'Маршрут',
            'flight_order' => 'Заявка',
            'flight_date' => 'Дата заявки',
            'flight_view_auto' => 'Вид автоперевозчика',
            'flight_address1' => 'Адрес погрузки1',
            'flight_shipping_date' => 'Дата погрузки',
            'flight_telephone1' => 'Телефон погрузки',
            'flight_type' => 'Тип загрузки/выгрузки',
            'flight_date_out2' => 'Дата Загрузке2',
            'flight_address_out2' => 'Адрес Загрузке2',
            'flight_contact_out2' => 'Контактное лицо и Телефон2',
            'flight_name2' => 'Упаковка',
            'flight_address_out3' => 'Адрес Загрузке3',
            'flight_date_out3' => 'Дата Загрузке3',
            'flight_contact' => 'Контактное лицо и Телефон3',
            'flight_name3' => 'Наименование груза3',
            'flight_address_out4' => 'Адрес разгрузки',
            'flight_date_out4' => 'Дата разгрузки',
            'flight_telephone' => 'Телефон разгрузки',
            'flight_cargo_weight' => 'вес груза',
            'flight_name' => 'Наименование груза',
            'flight_address_out5' => 'Адрес Разгрузке2',
            'flight_contact_out' => 'Контактное лицо и Телефон2',
            'flight_date_out5' => 'Дата  Разгрузке2',
            'flight_volume' => 'ОбЪём М3	',
            'flight_address' => 'Адрес Разгрузке3',
            'flight_date_out6' => 'Дата  Разгрузке3',
            'flight_contact_out3' => 'Контактное лицо и Телефон3',
            'flight_dop_informaciya_o_gruze' => 'Доп Информация о грузе',
            'flight_we' => 'Оплата от Заказчика',
            'flight_pay_us' => 'Форма оплаты',
            'flight_payment1' => 'Тип оплаты',
            'flight_col2' => 'Кол-во дней',
            'flight_payment_out' => 'Оплата Водителя',
            'flight_otherwise2' => 'Форма оплаты',
            'flight_otherwise3' => 'Тип оплаты',
            'flight_col1' => 'Кол-во дней',
            'flight_fio' => 'Тип счета',
            'flight_date_cr' => 'Дата',
            'flight_number' => 'Счёт',
            'flight_upd' => 'УПД',
            'flight_date2' => 'Дата Письмо Заказчику',
            'flight_date3' => 'Дата Письмо от  Водителя',
            'flight_recoil' => 'Откат',
            'flight_your_text' => 'Ваш текст',
            'flight_otherwise4' => 'Иное условие  для Заказчика',
            'flight_otherwise' => 'Иное условие для Водителя	',
            'flight_file' => 'Файлы',
            
        ];
    }


    /**
     * @return array
     */
    public function getColumnsAttributes(){
        return [
     'requisite_name',
     'requisite_doljnost_rukovoditelya',
     'requisite_fio_polnostyu',
     'requisite_official_address',
     'requisite_bank_name',
     'requisite_inn',
     'requisite_kpp',
     'requisite_ogrn',
     'requisite_bic',
     'requisite_kr',
     'requisite_nomer_rascheta',
     'requisite_tel',
     'requisite_fio_buhgaltera',
     'requisite_nds',
     'requisite_pechat',
     'client_name',
     'client_doljnost_rukovoditelya',
     'client_fio_polnostyu',
     'client_official_address',
     'client_bank_name',
     'client_inn',
     'client_kpp',
     'client_ogrn',
     'client_bic',
     'client_kr',
     'client_nomer_rascheta',
     'client_tel',
     'client_email',
     'client_nds',
     'client_doc',
     'client_mailing_address',
     'client_code',
     'driver_data',
     'driver_driver',
     'driver_phone',
     'driver_data_avto',
     'flight_organization_id',
     'flight_zakazchik_id',
     'flight_carrier_id',
     'flight_driver_id',
     'flight_rout',
     'flight_order',
     'flight_date',
     'flight_view_auto',
     'flight_address1',
     'flight_shipping_date',
     'flight_telephone1',
     'flight_type',
     'flight_date_out2',
     'flight_address_out2',
     'flight_contact_out2',
     'flight_name2',
     'flight_address_out3',
     'flight_date_out3',
     'flight_contact',
     'flight_name3',
     'flight_address_out4',
     'flight_date_out4',
     'flight_telephone',
     'flight_cargo_weight',
     'flight_name',
     'flight_address_out5',
     'flight_contact_out',
     'flight_date_out5',
     'flight_volume',
     'flight_address',
     'flight_date_out6',
     'flight_contact_out3',
     'flight_dop_informaciya_o_gruze',
     'flight_we',
     'flight_pay_us',
     'flight_payment1',
     'flight_col2',
     'flight_payment_out',
     'flight_otherwise2',
     'flight_otherwise3',
     'flight_col1',
     'flight_fio',
     'flight_date_cr',
     'flight_number',
     'flight_upd',
     'flight_date2',
     'flight_date3',
     'flight_recoil',
     'flight_your_text',
     'flight_otherwise4',
     'flight_otherwise',
     'flight_file',
    
        ];
    }


    /**
     * @inheritdoc
     */
    public function getColumns()
    {
        $columns = [];


        foreach ($this->getColumnsAttributes() as $attr){
            if($this->$attr != null){
                $arr = explode('_', $attr);
  
                if($arr[0] == 'requisite'){
                    $str = 'requisite_'.implode('_', array_slice($arr, 1));
                    $header = '<span class="label label-primary">Реквизиты</span>';
                }
  
                if($arr[0] == 'client'){
                    $str = 'client_'.implode('_', array_slice($arr, 1));
                    $header = '<span class="label label-primary">Организации</span>';
                }
  
                if($arr[0] == 'driver'){
                    $str = 'driver_'.implode('_', array_slice($arr, 1));
                    $header = '<span class="label label-primary">Водители</span>';
                }
  
                if($arr[0] == 'flight'){
                    $str = 'flight_'.implode('_', array_slice($arr, 1));
                    $header = '<span class="label label-primary">Рейсы</span>';
                }
  
                $columns[] = [
                    'attribute' => $str,
                    'header' => "<div style='width: 100%; text-align: center;'>{$header}</div>".$this->getAttributeLabel($attr),
                ];
            }
        }

        return $columns;
    }

}
