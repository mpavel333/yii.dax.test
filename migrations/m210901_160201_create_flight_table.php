<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210901_160201_create_flight_table`.
 */
class m210901_160201_create_flight_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('flight', [
            'id' => $this->primaryKey(),
            'rout' => $this->string()->comment('Маршрут'),
            'date_cr' => $this->date()->comment('Дата'),
            'zakazchik_id' => $this->integer()->comment('Заказчик'),
            'carrier_id' => $this->integer()->comment('Перевозчик'),
            'shipping_date' => $this->date()->comment('Дата погрузки'),
            'address1' => $this->string()->comment('Адрес погрузки1'),
            'telephone1' => $this->string()->comment('Телефон погрузки'),
            'date_out4' => $this->string()->comment('Дата разгрузки'),
            'address_out4' => $this->string()->comment('Адрес разгрузки'),
            'telephone' => $this->string()->comment('Телефон разгрузки'),
            'name' => $this->string()->comment('Наименование груза'),
            'cargo_weight' => $this->string()->comment('вес груза'),
            'type' => $this->string()->comment('Тип загрузки/выгрузки'),
            'otherwise2' => $this->string()->comment('Форма оплаты'),
            'otherwise3' => $this->string()->comment('Тип оплаты'),
            'payment1' => $this->string()->comment('Тип оплаты'),
            'col1' => $this->string()->comment('Кол-во дней'),
            'payment_out' => $this->string()->comment('Оплата Водителя'),
            'pay_us' => $this->string()->comment('Форма оплаты'),
            'col2' => $this->string()->comment('Кол-во дней'),
            'we' => $this->string()->comment('Оплата от Заказчика'),
            'fio' => $this->string()->comment('Тип счета'),
            'number' => $this->double()->comment('Счёт'),
            'file' => $this->text()->comment('Файлы'),
            'organization_id' => $this->integer()->comment('Организация Наша'),
            'driver_id' => $this->integer()->comment('Водитель'),
            'order' => $this->string()->comment('Заявка'),
            'view_auto' => $this->string()->comment('Вид автоперевозчика'),
            'address_out2' => $this->string()->comment('Адрес Загрузке2'),
            'date_out2' => $this->string()->comment('Дата Загрузке2'),
            'contact_out' => $this->string()->comment('Контактное лицо и Телефон2'),
            'name2' => $this->string()->comment('Упаковка'),
            'address_out3' => $this->string()->comment('Адрес Загрузке3'),
            'date_out3' => $this->string()->comment('Дата Загрузке3'),
            'contact_out3' => $this->string()->comment('Контактное лицо и Телефон3'),
            'name3' => $this->string()->comment('Наименование груза3'),
            'address_out5' => $this->string()->comment('Адрес Разгрузке2'),
            'date_out5' => $this->string()->comment('Дата  Разгрузке2'),
            'contact_out2' => $this->string()->comment('Контактное лицо и Телефон2'),
            'volume' => $this->string()->comment('ОбЪём М3	'),
            'address' => $this->string()->comment('Адрес Разгрузке3'),
            'date_out6' => $this->string()->comment('Дата  Разгрузке3'),
            'contact' => $this->string()->comment('Контактное лицо и Телефон3'),
            'dop_informaciya_o_gruze' => $this->string()->comment('Доп Информация о грузе'),
            'upd' => $this->string()->comment('УПД'),
            'date2' => $this->date()->comment('Дата Письмо Заказчику'),
            'date3' => $this->date()->comment('Дата Письмо от  Водителя'),
            'recoil' => $this->string()->comment('Откат'),
            'your_text' => $this->string()->comment('Ваш текст'),
            'otherwise4' => $this->string()->comment('Иное условие  для Заказчика'),
            'otherwise' => $this->string()->comment('Иное условие для Водителя	'),
            'date' => $this->date()->comment('Дата заявки'),
        ]);

        $this->createIndex(
            'idx-flight-zakazchik_id',
            'flight',
            'zakazchik_id'
        );
                        
        $this->addForeignKey(
            'fk-flight-zakazchik_id',
            'flight',
            'zakazchik_id',
            'client',
            'id',
            'SET NULL'
        );
        $this->createIndex(
            'idx-flight-carrier_id',
            'flight',
            'carrier_id'
        );
                        
        $this->addForeignKey(
            'fk-flight-carrier_id',
            'flight',
            'carrier_id',
            'client',
            'id',
            'SET NULL'
        );
        $this->createIndex(
            'idx-flight-organization_id',
            'flight',
            'organization_id'
        );
                        
        $this->addForeignKey(
            'fk-flight-organization_id',
            'flight',
            'organization_id',
            'requisite',
            'id',
            'SET NULL'
        );
        $this->createIndex(
            'idx-flight-driver_id',
            'flight',
            'driver_id'
        );
                        
        $this->addForeignKey(
            'fk-flight-driver_id',
            'flight',
            'driver_id',
            'driver',
            'id',
            'SET NULL'
        );
                        

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        $this->dropForeignKey(
            'fk-flight-zakazchik_id',
            'flight'
        );
                        
        $this->dropIndex(
            'idx-flight-zakazchik_id',
            'flight'
        );
                        
                        $this->dropForeignKey(
            'fk-flight-carrier_id',
            'flight'
        );
                        
        $this->dropIndex(
            'idx-flight-carrier_id',
            'flight'
        );
                        
                        $this->dropForeignKey(
            'fk-flight-organization_id',
            'flight'
        );
                        
        $this->dropIndex(
            'idx-flight-organization_id',
            'flight'
        );
                        
                        $this->dropForeignKey(
            'fk-flight-driver_id',
            'flight'
        );
                        
        $this->dropIndex(
            'idx-flight-driver_id',
            'flight'
        );
                        
                        
        $this->dropTable('flight');
    }
}
