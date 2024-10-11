<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%requisite}}`.
 */
class m220429_110757_add_user_id_column_to_requisite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('requisite', 'user_id', $this->integer()->comment('Пользователь'));

        $this->createIndex(
            "idx-requisite-user_id",
            "requisite",
            "user_id"
        );

        $this->addForeignKey(
            "fk-requisite-user_id",
            "requisite",
            "user_id",
            "user",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey(
            "fk-requisite-user_id",
            "requisite"
        );

        $this->dropIndex(
            "idx-requisite-user_id",
            "requisite"
        );

        $this->dropColumn('requisite', 'user_id');
    }
}
