<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240619_174435_add_new_columns_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('role', 'car_to_create', $this->boolean()->comment('Автопарк ТО Добавление'));
        $this->addColumn('role', 'car_to_update', $this->boolean()->comment('Автопарк ТО Изменение'));
        $this->addColumn('role', 'car_to_view', $this->boolean()->comment('Автопарк ТО Просмотр'));
        $this->addColumn('role', 'car_to_view_all', $this->boolean()->comment('Автопарк ТО Просмотр всех'));
        $this->addColumn('role', 'car_to_delete', $this->boolean()->comment('Автопарк ТО Удалить'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
