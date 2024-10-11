<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240918_162718_add_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'structure_create', $this->boolean()->comment('Структура компании Добавление'));
        //$this->addColumn('role', 'structure_update', $this->boolean()->comment('Структура компании Изменение'));
        $this->addColumn('role', 'structure_view', $this->boolean()->comment('Структура компании Просмотр'));
        $this->addColumn('role', 'structure_view_all', $this->boolean()->comment('Структура компании Просмотр всех'));
        $this->addColumn('role', 'structure_delete', $this->boolean()->comment('Структура компании Удалить'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
