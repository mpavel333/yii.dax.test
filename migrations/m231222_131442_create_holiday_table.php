<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m231222_131442_create_holiday_table`.
 */
class m231222_131442_create_holiday_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('holiday', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->comment('Дата'),
        ]);

        $this->addColumn('role', 'holiday_create', $this->boolean()->defaultValue(false)->comment('Праздники Добавление'));
        $this->addColumn('role', 'holiday_update', $this->boolean()->defaultValue(false)->comment('Праздники Изменение'));
        $this->addColumn('role', 'holiday_view', $this->boolean()->defaultValue(false)->comment('Праздники Просмотр'));
        $this->addColumn('role', 'holiday_view_all', $this->boolean()->defaultValue(false)->comment('Праздники Просмотр всех'));
        $this->addColumn('role', 'holiday_index', $this->boolean()->defaultValue(false)->comment('Праздники Просмотр таблицы'));
        $this->addColumn('role', 'holiday_delete', $this->boolean()->defaultValue(false)->comment('Праздники Удалить'));
        $this->addColumn('role', 'holiday_disallow_fields', $this->text()->comment('Праздники Исключить поля'));

        \app\models\Role::updateAll([
            'holiday_create' => true,
            'holiday_update' => true,
            'holiday_view' => true,
            'holiday_view_all' => true,
            'holiday_index' => true,
            'holiday_delete' => true,
            'holiday_disallow_fields' => true,
        ], ['id' => 1]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('role', 'holiday_create');
        $this->dropColumn('role', 'holiday_update');
        $this->dropColumn('role', 'holiday_view');
        $this->dropColumn('role', 'holiday_view_all');
        $this->dropColumn('role', 'holiday_index');
        $this->dropColumn('role', 'holiday_delete');
        $this->dropColumn('role', 'holiday_disallow_fields');
        
        $this->dropTable('holiday');
    }
}
