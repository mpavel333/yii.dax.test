<?php
use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m210901_155501_create_role_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Наименование'),
            'requisite_create' => $this->boolean()->defaultValue(false)->comment('Реквизиты Добавление'),
            'requisite_update' => $this->boolean()->defaultValue(false)->comment('Реквизиты Изменение'),
            'requisite_view' => $this->boolean()->defaultValue(false)->comment('Реквизиты Просмотр'),
            'requisite_view_all' => $this->boolean()->defaultValue(false)->comment('Реквизиты Просмотр всех'),
            'requisite_delete' => $this->boolean()->defaultValue(false)->comment('Реквизиты Удалить'),
            'requisite_disallow_fields' => $this->text()->comment('Реквизиты Исключить поля'),
            'client_create' => $this->boolean()->defaultValue(false)->comment('Организации Добавление'),
            'client_update' => $this->boolean()->defaultValue(false)->comment('Организации Изменение'),
            'client_view' => $this->boolean()->defaultValue(false)->comment('Организации Просмотр'),
            'client_view_all' => $this->boolean()->defaultValue(false)->comment('Организации Просмотр всех'),
            'client_delete' => $this->boolean()->defaultValue(false)->comment('Организации Удалить'),
            'client_disallow_fields' => $this->text()->comment('Организации Исключить поля'),
            'driver_create' => $this->boolean()->defaultValue(false)->comment('Водители Добавление'),
            'driver_update' => $this->boolean()->defaultValue(false)->comment('Водители Изменение'),
            'driver_view' => $this->boolean()->defaultValue(false)->comment('Водители Просмотр'),
            'driver_view_all' => $this->boolean()->defaultValue(false)->comment('Водители Просмотр всех'),
            'driver_delete' => $this->boolean()->defaultValue(false)->comment('Водители Удалить'),
            'driver_disallow_fields' => $this->text()->comment('Водители Исключить поля'),
            'flight_create' => $this->boolean()->defaultValue(false)->comment('Рейсы Добавление'),
            'flight_update' => $this->boolean()->defaultValue(false)->comment('Рейсы Изменение'),
            'flight_view' => $this->boolean()->defaultValue(false)->comment('Рейсы Просмотр'),
            'flight_view_all' => $this->boolean()->defaultValue(false)->comment('Рейсы Просмотр всех'),
            'flight_delete' => $this->boolean()->defaultValue(false)->comment('Рейсы Удалить'),
            'flight_disallow_fields' => $this->text()->comment('Рейсы Исключить поля'),
            'books' => $this->boolean()->defaultValue(false)->comment('Справочники'),
            'settingsGlob' => $this->boolean()->defaultValue(false)->comment('Настройки'),
        ]);

        $this->insert('role', [
            'name' => 'Администратор',
            'requisite_create' => 1,
            'requisite_update' => 1,
            'requisite_view' => 1,
            'requisite_view_all' => 1,
            'requisite_delete' => 1,
            'client_create' => 1,
            'client_update' => 1,
            'client_view' => 1,
            'client_view_all' => 1,
            'client_delete' => 1,
            'driver_create' => 1,
            'driver_update' => 1,
            'driver_view' => 1,
            'driver_view_all' => 1,
            'driver_delete' => 1,
            'flight_create' => 1,
            'flight_update' => 1,
            'flight_view' => 1,
            'flight_view_all' => 1,
            'flight_delete' => 1,
            'books' => 1,
            'settingsGlob' => 1,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('role');
    }
}
