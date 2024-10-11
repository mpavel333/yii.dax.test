<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%security}}`.
 */
class m230819_150454_add_api_token_column_to_security_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('security', 'api_token', $this->string()->comment('API Токен'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('security', 'api_token');
    }
}
