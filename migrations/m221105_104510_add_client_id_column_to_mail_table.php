<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mail}}`.
 */
class m221105_104510_add_client_id_column_to_mail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mail', 'client_id', $this->integer()->comment('Клиент'));

        $this->createIndex(
            "idx-mail-client_id",
            "mail",
            "client_id"
        );

        $this->addForeignKey(
            "fk-mail-client_id",
            "mail",
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
            "fk-mail-client_id",
            "mail"
        );

        $this->dropIndex(
            "idx-mail-client_id",
            "mail"
        );

        $this->dropColumn('mail', 'client_id');
    }
}
