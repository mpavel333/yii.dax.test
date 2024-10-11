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
class TemplateMetal extends \yii\db\ActiveRecord
{
    const TYPE_METAL = 432;

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
            self::TYPE_METAL => "Металлика",
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
          '{metal.rout}' => 'Маршрут',
          '{metal.date_cr}' => 'Дата',
          '{metal.zakazchik_id}' => 'Заказчик',
          '{metal.carrier_id}' => 'Перевозчик',
          '{metal.shipping_date}' => 'Дата погрузки',
          '{metal.address1}' => 'Адрес погрузки1',
          '{metal.telephone1}' => 'Телефон погрузки',
          '{metal.date_out4}' => 'Дата разгрузки',
          '{metal.address_out4}' => 'Адрес разгрузки',
          '{metal.telephone}' => 'Телефон разгрузки',
          '{metal.name}' => 'Наименование груза',
          '{metal.cargo_weight}' => 'вес груза',
          '{metal.type}' => 'Тип загрузки/выгрузки',
          '{metal.otherwise2}' => 'Форма оплаты',
          '{metal.otherwise3}' => 'Тип оплаты',
          '{metal.payment1}' => 'Тип оплаты',
          '{metal.col1}' => 'Кол-во дней',
          '{metal.payment_out}' => 'Оплата Водителя',
          '{metal.pay_us}' => 'Форма оплаты',
          '{metal.col2}' => 'Кол-во дней',
          '{metal.we}' => 'Оплата от Заказчика',
          '{metal.fio}' => 'Тип счета',
          '{metal.number}' => 'Счёт',
          '{metal.file}' => 'Файлы',
          '{metal.organization_id}' => 'Организация Наша',
          '{metal.driver_id}' => 'Водитель',
          '{metal.order}' => 'Заявка',
          '{metal.view_auto}' => 'Вид автоперевозчика',
          '{metal.address_out2}' => 'Адрес Загрузке2',
          '{metal.date_out2}' => 'Дата Загрузке2',
          '{metal.contact_out}' => 'Контактное лицо и Телефон2',
          '{metal.name2}' => 'Упаковка',
          '{metal.address_out3}' => 'Адрес Загрузке3',
          '{metal.date_out3}' => 'Дата Загрузке3',
          '{metal.contact_out3}' => 'Контактное лицо и Телефон3',
          '{metal.name3}' => 'Наименование груза3',
          '{metal.address_out5}' => 'Адрес Разгрузке2',
          '{metal.date_out5}' => 'Дата  Разгрузке2',
          '{metal.contact_out2}' => 'Контактное лицо и Телефон2',
          '{metal.volume}' => 'ОбЪём М3	',
          '{metal.address}' => 'Адрес Разгрузке3',
          '{metal.date_out6}' => 'Дата  Разгрузке3',
          '{metal.contact}' => 'Контактное лицо и Телефон3',
          '{metal.dop_informaciya_o_gruze}' => 'Доп Информация о грузе',
          '{metal.upd}' => 'УПД',
          '{metal.date2}' => 'Дата Письмо Заказчику',
          '{metal.date3}' => 'Дата Письмо от  Водителя',
          '{metal.recoil}' => 'Откат',
          '{metal.your_text}' => 'Ваш текст',
          '{metal.otherwise4}' => 'Иное условие  для Заказчика',
          '{metal.otherwise}' => 'Иное условие для Водителя	',
          '{metal.date}' => 'Дата заявки',
          '{metal.contract_place}' => 'Дата заявки',
          '{driver.data}' => 'Водитель ФИО',
          '{driver.data_avto}' => 'Данные автомобиля',
          '{driver.phone}' => 'Телефон',
          '{driver.driver}' => 'Водитель паспортные данные',

        ];
    }

}
