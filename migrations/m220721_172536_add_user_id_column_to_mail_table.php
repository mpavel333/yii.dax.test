<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mail}}`.
 */
class m220721_172536_add_user_id_column_to_mail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mail', 'user_id', $this->integer()->comment('Пользователь'));

        $this->createIndex(
            "idx-mail-user_id",
            "mail",
            "user_id"
        );

        $this->addForeignKey(
            "fk-mail-user_id",
            "mail",
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
            "fk-mail-user_id",
            "mail"
        );

        $this->dropIndex(
            "idx-mail-user_id",
            "mail"
        );


        $this->dropColumn('mail', 'user_id');
    }

}
