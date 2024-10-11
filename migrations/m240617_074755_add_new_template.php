<?php

use yii\db\Migration;

/**
 * Class m240617_074755_add_new_template
 */
class m240617_074755_add_new_template extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $template = new \app\models\Template([
            'name' => 'ТН',
            'type' => 432,
        ]);
        $template->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \app\models\Template::deleteAll([
            'name' => 'ТН',
        ]);
    }
}
