<?php

use yii\db\Migration;

/**
 * Class m240128_091818_add_organization_id_to_client_table
 */
class m240128_091818_add_organization_id_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'organization_id', $this->integer()->comment('Наша организация'));

        $this->createIndex(
            "idx-client-organization_id",
            "client",
            "organization_id"
        );

        $this->addForeignKey(
            "fk-client-organization_id",
            "client",
            "organization_id",
            "requisite",
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
            "fk-client-organization_id",
            "client"
        );

        $this->dropIndex(
            "idx-client-organization_id",
            "client"
        );

        $this->dropColumn('client', 'organization_id');
    }
}
