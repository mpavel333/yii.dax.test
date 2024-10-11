<?php

use yii\db\Migration;

/**
 * Class m231130_093734_copy_flight_table_as_metal_table
 */
class m231130_093734_copy_flight_table_as_metal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$sql = <<< EOT

CREATE TABLE `metal` (
  `id` int(11) NOT NULL,
  `rout` varchar(255) DEFAULT NULL COMMENT 'Маршрут',
  `distance` double DEFAULT NULL COMMENT 'Километраж',
  `date_cr` date DEFAULT NULL COMMENT 'Дата',
  `date_cr_check` tinyint(1) DEFAULT '0',
  `zakazchik_id` int(11) DEFAULT NULL COMMENT 'Заказчик',
  `carrier_id` int(11) DEFAULT NULL COMMENT 'Перевозчик',
  `shipping_date` date DEFAULT NULL COMMENT 'Дата погрузки',
  `address1` varchar(255) DEFAULT NULL COMMENT 'Адрес погрузки1',
  `telephone1` varchar(255) DEFAULT NULL COMMENT 'Телефон погрузки',
  `date_out4` varchar(255) DEFAULT NULL COMMENT 'Дата разгрузки',
  `address_out4` varchar(255) DEFAULT NULL COMMENT 'Адрес разгрузки',
  `telephone` varchar(255) DEFAULT NULL COMMENT 'Телефон разгрузки',
  `name` varchar(255) DEFAULT NULL COMMENT 'Наименование груза',
  `cargo_weight` varchar(255) DEFAULT NULL COMMENT 'вес груза',
  `type` varchar(255) DEFAULT NULL COMMENT 'Тип загрузки/выгрузки',
  `status` int(11) DEFAULT NULL COMMENT 'Статус',
  `otherwise2` varchar(255) DEFAULT NULL COMMENT 'Форма оплаты',
  `otherwise3` varchar(255) DEFAULT NULL COMMENT 'Тип оплаты',
  `payment1` varchar(255) DEFAULT NULL COMMENT 'Тип оплаты',
  `col1` varchar(255) DEFAULT NULL COMMENT 'Кол-во дней',
  `payment_out` varchar(255) DEFAULT NULL COMMENT 'Оплата Водителя',
  `pay_us` varchar(255) DEFAULT NULL COMMENT 'Форма оплаты',
  `col2` varchar(255) DEFAULT NULL COMMENT 'Кол-во дней',
  `we` varchar(255) DEFAULT NULL COMMENT 'Оплата от Заказчика',
  `fio` varchar(255) DEFAULT NULL COMMENT 'Тип счета',
  `number` varchar(255) DEFAULT NULL COMMENT 'Счёт',
  `number_check` tinyint(1) DEFAULT '0',
  `file` text COMMENT 'Файлы',
  `file_provider` text,
  `organization_id` int(11) DEFAULT NULL COMMENT 'Организация Наша',
  `driver_id` int(11) DEFAULT NULL COMMENT 'Водитель',
  `order` varchar(255) DEFAULT NULL COMMENT 'Заявка',
  `driver_order` varchar(255) DEFAULT NULL COMMENT 'Заявка перевозчик',
  `order_check` tinyint(1) DEFAULT '0',
  `view_auto` varchar(255) DEFAULT NULL COMMENT 'Вид автоперевозчика',
  `address_out2` varchar(255) DEFAULT NULL COMMENT 'Адрес Загрузке2',
  `date_out2` varchar(255) DEFAULT NULL COMMENT 'Дата Загрузке2',
  `contact_out` varchar(255) DEFAULT NULL COMMENT 'Контактное лицо и Телефон2',
  `name2` varchar(255) DEFAULT NULL COMMENT 'Упаковка',
  `address_out3` varchar(255) DEFAULT NULL COMMENT 'Адрес Загрузке3',
  `date_out3` varchar(255) DEFAULT NULL COMMENT 'Дата Загрузке3',
  `contact_out3` varchar(255) DEFAULT NULL COMMENT 'Контактное лицо и Телефон3',
  `name3` varchar(255) DEFAULT NULL COMMENT 'Наименование груза3',
  `address_out5` varchar(255) DEFAULT NULL COMMENT 'Адрес Разгрузке2',
  `date_out5` varchar(255) DEFAULT NULL COMMENT 'Дата  Разгрузке2',
  `contact_out2` varchar(255) DEFAULT NULL COMMENT 'Контактное лицо и Телефон2',
  `volume` varchar(255) DEFAULT NULL COMMENT 'ОбЪём М3  ',
  `address` varchar(255) DEFAULT NULL COMMENT 'Адрес Разгрузке3',
  `date_out6` varchar(255) DEFAULT NULL COMMENT 'Дата  Разгрузке3',
  `contact` varchar(255) DEFAULT NULL COMMENT 'Контактное лицо и Телефон3',
  `dop_informaciya_o_gruze` varchar(255) DEFAULT NULL COMMENT 'Доп Информация о грузе',
  `upd` varchar(255) DEFAULT NULL COMMENT 'УПД',
  `date2` date DEFAULT NULL COMMENT 'Дата Письмо Заказчику',
  `date3` date DEFAULT NULL COMMENT 'Дата Письмо от  Водителя',
  `recoil` varchar(255) DEFAULT NULL COMMENT 'Откат',
  `your_text` varchar(255) DEFAULT NULL COMMENT 'Ваш текст',
  `otherwise4` varchar(255) DEFAULT NULL COMMENT 'Иное условие  для Заказчика',
  `otherwise` varchar(255) DEFAULT NULL COMMENT 'Иное условие для Водителя  ',
  `date` date DEFAULT NULL COMMENT 'Дата заявки',
  `is_payed` tinyint(1) DEFAULT '0' COMMENT 'Оплачено',
  `user_id` int(11) DEFAULT NULL COMMENT 'Пользователь',
  `is_driver_payed` tinyint(1) DEFAULT '0' COMMENT 'Оплата водителя',
  `is_ati_driver` tinyint(1) DEFAULT '0' COMMENT 'АТИ водителя',
  `is_ati_client` tinyint(1) DEFAULT '0' COMMENT 'АТИ клиента',
  `created_by` int(11) DEFAULT NULL COMMENT 'Создал',
  `created_at` datetime DEFAULT NULL COMMENT 'Дата и время создания',
  `salary` double DEFAULT NULL COMMENT 'Зарплата',
  `delta` double DEFAULT NULL COMMENT 'Дельта',
  `is_register` tinyint(1) DEFAULT '0' COMMENT 'Регистрация',
  `auto` varchar(255) DEFAULT NULL COMMENT 'Авто',
  `shipping_date_2` date DEFAULT NULL,
  `date_out4_2` date DEFAULT NULL,
  `date_out2_2` date DEFAULT NULL,
  `date_out5_2` date DEFAULT NULL,
  `date_out3_2` date DEFAULT NULL,
  `date_out6_2` date DEFAULT NULL,
  `is_order` tinyint(1) DEFAULT '0' COMMENT 'Заявка',
  `is_signature` tinyint(1) DEFAULT '0' COMMENT 'Подпись',
  `is_driver_signature` tinyint(1) DEFAULT '0' COMMENT 'Подпись перевозчика',
  `bank_id` int(11) DEFAULT NULL COMMENT 'Банк',
  `car_number` varchar(255) DEFAULT NULL COMMENT 'Номер автомобиля',
  `act` varchar(255) DEFAULT NULL COMMENT 'Акт',
  `act_date` date DEFAULT NULL COMMENT 'Дата',
  `track_number` varchar(255) DEFAULT NULL COMMENT 'Трек номер',
  `track_number_driver` varchar(255) DEFAULT NULL COMMENT 'Трек номер водителя',
  `info_from_client` varchar(255) DEFAULT NULL COMMENT 'Информация от Заказчика',
  `letter_info` varchar(255) DEFAULT NULL COMMENT 'Информация письма',
  `letter_info_driver` varchar(255) DEFAULT NULL COMMENT 'Информация письма водителя',
  `is_register_letter` tinyint(1) DEFAULT '0' COMMENT 'Регистрация почты',
  `is_register_letter_driver` tinyint(1) DEFAULT '0' COMMENT 'Регистрация почты',
  `success_datetime` datetime DEFAULT NULL COMMENT 'Дата и время успеха',
  `archive_datetime` datetime DEFAULT NULL COMMENT 'Дата и время архивации',
  `contract_place` varchar(255) DEFAULT NULL COMMENT 'Место заключения заявки',
  `sum` double DEFAULT NULL COMMENT 'Сумма рейса',
  `daks_balance` double DEFAULT '0' COMMENT 'Даксы',
  `we_prepayment` double DEFAULT NULL COMMENT 'Предоплата от заказчика',
  `payment_out_prepayment` double DEFAULT NULL COMMENT 'Предоплата от водителя',
  `name_price` double DEFAULT NULL COMMENT 'Стоимость груза',
  `place_count` int(11) DEFAULT NULL COMMENT 'Количество мест',
  `is_insurance` tinyint(1) DEFAULT '0' COMMENT 'Страховка',
  `type_weight` varchar(255) DEFAULT NULL COMMENT 'Тип',
  `loading_type` varchar(255) DEFAULT NULL COMMENT 'Тип загрузки',
  `uploading_type` varchar(255) DEFAULT NULL COMMENT 'Тип разгрузки',
  `width` double DEFAULT NULL COMMENT 'Ширина',
  `height` double DEFAULT NULL COMMENT 'Высота',
  `length` double DEFAULT NULL COMMENT 'Длинна',
  `diameter` double DEFAULT NULL COMMENT 'Диаметр',
  `belts_count` double DEFAULT NULL COMMENT 'Кол-во ремней',
  `logging_truck` tinyint(1) DEFAULT '0' COMMENT 'Коники',
  `road_train` tinyint(1) DEFAULT '0' COMMENT 'Сцепка',
  `air_suspension` tinyint(1) DEFAULT '0' COMMENT 'Пневмоход',
  `body_type` int(11) DEFAULT NULL COMMENT 'Кузов',
  `priority_rate` double DEFAULT NULL COMMENT 'Ставка за просмотр',
  `priority_limit` double DEFAULT NULL COMMENT 'Лимит на заявку',
  `priority_daily_limit` double DEFAULT NULL COMMENT 'Суточный лимит',
  `only_for_paid_users` tinyint(1) DEFAULT '0' COMMENT 'Показывать приоритетный груз только платным пользователям',
  `information_file_path` varchar(255) DEFAULT NULL COMMENT 'Файл информации',
  `information` blob COMMENT 'Информация',
  `payment_bank_id` int(11) DEFAULT NULL COMMENT 'Банк для оплаты',
  `bank` varchar(255) DEFAULT NULL COMMENT 'Банк для оплаты',
  `is_salary_payed` tinyint(1) DEFAULT '0' COMMENT 'Зарплата оплачена'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `metal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-metal-zakazchik_id` (`zakazchik_id`),
  ADD KEY `idx-metal-carrier_id` (`carrier_id`),
  ADD KEY `idx-metal-organization_id` (`organization_id`),
  ADD KEY `idx-metal-driver_id` (`driver_id`),
  ADD KEY `idx-metal-user_id` (`user_id`),
  ADD KEY `idx-metal-created_by` (`created_by`),
  ADD KEY `idx-metal-bank_id` (`bank_id`),
  ADD KEY `idx-metal-payment_bank_id` (`payment_bank_id`);

ALTER TABLE `metal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `metal`
  ADD CONSTRAINT `fk-metal-bank_id` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-metal-carrier_id` FOREIGN KEY (`carrier_id`) REFERENCES `client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-metal-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-metal-driver_id` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-metal-organization_id` FOREIGN KEY (`organization_id`) REFERENCES `requisite` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-metal-payment_bank_id` FOREIGN KEY (`payment_bank_id`) REFERENCES `bank` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-metal-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-metal-zakazchik_id` FOREIGN KEY (`zakazchik_id`) REFERENCES `client` (`id`) ON DELETE SET NULL;

EOT;



        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('metal');
    }
}
