<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m220314_154924_add_security_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'security', $this->boolean()->defaultValue(false)->comment('Безопасноть'));

        $columns = [
            'mail_create' => $this->boolean()->defaultValue(false)->comment('Почта Добавление'),
            'mail_update' => $this->boolean()->defaultValue(false)->comment('Почта Изменение'),
            'mail_view' => $this->boolean()->defaultValue(false)->comment('Почта Просмотр'),
            'mail_view_all' => $this->boolean()->defaultValue(false)->comment('Почта Просмотр всех'),
            'mail_delete' => $this->boolean()->defaultValue(false)->comment('Почта Удалить'),
            'mail_disallow_fields' => $this->text()->comment('Почта Исключить поля'),
        ];

        foreach ($columns as $name => $value) {
            $this->addColumn('role', $name, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'security');

        $columns = [
            'mail_create' => $this->boolean()->defaultValue(false)->comment('Почта Добавление'),
            'mail_update' => $this->boolean()->defaultValue(false)->comment('Почта Изменение'),
            'mail_view' => $this->boolean()->defaultValue(false)->comment('Почта Просмотр'),
            'mail_view_all' => $this->boolean()->defaultValue(false)->comment('Почта Просмотр всех'),
            'mail_delete' => $this->boolean()->defaultValue(false)->comment('Почта Удалить'),
            'mail_disallow_fields' => $this->text()->comment('Почта Исключить поля'),
        ];


        foreach ($columns as $name => $value) {
            $this->dropColumn('role', $name);
        }
    }
}
