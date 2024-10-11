<?php 
namespace app\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property string $name Название
 * @property int $type Тип
 * @property string $text Текст
 */
class Template extends \yii\db\ActiveRecord
{
    const TYPE_FLIGHT = 432;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['text'], 'string'],
            [['name', 'modifier'], 'string', 'max' => 255],
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
            'type' => 'Тип',
            'text' => 'Текст',
            'modifier' => 'Модификация',
        ];
    }

    /**
    * @return array
    */
    public static function typeLabels()
    {
        return [
            self::TYPE_FLIGHT => "Рейсы",
        ];
    }

    /**
    * @return array
    */
    public function getListTag()
    {
        return [
          '{user.login}' => 'Логин',
          '{user.role_id}' => 'Роль',
          '{user.role}' => 'Должность',
          '{user.name}' => 'ФИО',
          '{user.phone}' => 'Телефон',
          '{user.access}' => 'Доступ',
          '{user.password_hash}' => 'Зашифрованный пароль',
          '{user.created_at}' => 'Дата создания',
          '{user.is_deletable}' => 'Можно удалить или нельзя',
          '{requisite.name}' => 'Наименование',
          '{requisite.name_case}' => 'Наименование (Род. п.)',
          '{requisite.doljnost_rukovoditelya}' => 'Должность руководителя',
          '{requisite.fio_polnostyu}' => 'ФИО полностью',
          '{requisite.official_address}' => 'Юридический адрес',
          '{requisite.bank_name}' => 'Наименование банка',
          '{requisite.inn}' => 'ИНН',
          '{requisite.kpp}' => 'КПП',
          '{requisite.ogrn}' => 'ОГРН',
          '{requisite.bic}' => 'Бик',
          '{requisite.kr}' => 'КР',
          '{requisite.nomer_rascheta}' => 'Номер расчета',
          '{requisite.tel}' => 'тел.',
          '{requisite.fio_buhgaltera}' => 'ФИО бухгалтера',
          '{requisite.nds}' => 'НДС',
          '{requisite.pechat}' => 'Печать',
          '{client.name}' => 'Наименование',
          '{client.name_case}' => 'Наименование (Род. п.)',
          '{client.doljnost_rukovoditelya}' => 'Должность руководителя',
          '{client.fio_polnostyu}' => 'ФИО полностью',
          '{client.official_address}' => 'Юридический адрес',
          '{client.bank_name}' => 'Наименование банка',
          '{client.inn}' => 'ИНН',
          '{client.kpp}' => 'КПП',
          '{client.ogrn}' => 'ОГРН',
          '{client.bic}' => 'Бик',
          '{client.kr}' => 'КР',
          '{client.nomer_rascheta}' => 'Номер расчета',
          '{client.tel}' => 'тел.',
          '{client.email}' => 'email',
          '{client.nds}' => 'НДС',
          '{client.doc}' => 'Договор',
          '{client.mailing_address}' => 'Почтовый адрес',
          '{client.code}' => 'Код АТИ',
          '{flight.rout}' => 'Маршрут',
          '{flight.date_cr}' => 'Дата',
          '{flight.zakazchik_id}' => 'Заказчик',
          '{flight.carrier_id}' => 'Перевозчик',
          '{flight.shipping_date}' => 'Дата погрузки',
          '{flight.address1}' => 'Адрес погрузки1',
          '{flight.telephone1}' => 'Телефон погрузки',
          '{flight.date_out4}' => 'Дата разгрузки',
          '{flight.address_out4}' => 'Адрес разгрузки',
          '{flight.telephone}' => 'Телефон разгрузки',
          '{flight.name}' => 'Наименование груза',
          '{flight.cargo_weight}' => 'вес груза',
          '{flight.type}' => 'Тип загрузки/выгрузки',
          '{flight.otherwise2}' => 'Форма оплаты',
          '{flight.otherwise3}' => 'Тип оплаты',
          '{flight.payment1}' => 'Тип оплаты',
          '{flight.col1}' => 'Кол-во дней',
          '{flight.payment_out}' => 'Оплата Водителя',
          '{flight.pay_us}' => 'Форма оплаты',
          '{flight.col2}' => 'Кол-во дней',
          '{flight.we}' => 'Оплата от Заказчика',
          '{flight.fio}' => 'Тип счета',
          '{flight.number}' => 'Счёт',
          '{flight.file}' => 'Файлы',
          '{flight.organization_id}' => 'Организация Наша',
          '{flight.driver_id}' => 'Водитель',
          '{flight.order}' => 'Заявка',
          '{flight.view_auto}' => 'Вид автоперевозчика',
          '{flight.address_out2}' => 'Адрес Загрузке2',
          '{flight.date_out2}' => 'Дата Загрузке2',
          '{flight.contact_out}' => 'Контактное лицо и Телефон2',
          '{flight.name2}' => 'Упаковка',
          '{flight.address_out3}' => 'Адрес Загрузке3',
          '{flight.date_out3}' => 'Дата Загрузке3',
          '{flight.contact_out3}' => 'Контактное лицо и Телефон3',
          '{flight.name3}' => 'Наименование груза3',
          '{flight.address_out5}' => 'Адрес Разгрузке2',
          '{flight.date_out5}' => 'Дата  Разгрузке2',
          '{flight.contact_out2}' => 'Контактное лицо и Телефон2',
          '{flight.volume}' => 'ОбЪём М3	',
          '{flight.address}' => 'Адрес Разгрузке3',
          '{flight.date_out6}' => 'Дата  Разгрузке3',
          '{flight.contact}' => 'Контактное лицо и Телефон3',
          '{flight.dop_informaciya_o_gruze}' => 'Доп Информация о грузе',
          '{flight.upd}' => 'УПД',
          '{flight.date2}' => 'Дата Письмо Заказчику',
          '{flight.date3}' => 'Дата Письмо от  Водителя',
          '{flight.recoil}' => 'Откат',
          '{flight.your_text}' => 'Ваш текст',
          '{flight.otherwise4}' => 'Иное условие  для Заказчика',
          '{flight.otherwise}' => 'Иное условие для Водителя	',
          '{flight.date}' => 'Дата заявки',
          '{flight.contract_place}' => 'Дата заявки',
          '{driver.data}' => 'Водитель ФИО',
          '{driver.data_avto}' => 'Данные автомобиля',
          '{driver.phone}' => 'Телефон',
          '{driver.driver}' => 'Водитель паспортные данные',

        ];
    }

}
