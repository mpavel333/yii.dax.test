<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m230408_112748_add_client_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'client_id', $this->integer()->comment('Клиент'));

        $this->createIndex(
            "idx-user-client_id",
            "user",
            "client_id"
        );

        $this->addForeignKey(
            "fk-user-client_id",
            "user",
            "client_id",
            "client",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            "fk-user-client_id",
            "user"
        );

        $this->dropIndex(
            "idx-user-client_id",
            "user"
        );

        $this->dropColumn('user', 'client_id');
    }
}
