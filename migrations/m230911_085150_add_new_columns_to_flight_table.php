<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m230911_085150_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'type_weight', $this->string()->comment('Тип'));
        $this->addColumn('flight', 'loading_type', $this->string()->comment('Тип загрузки'));
        $this->addColumn('flight', 'uploading_type', $this->string()->comment('Тип разгрузки'));
        $this->addColumn('flight', 'width', $this->double()->comment('Ширина'));
        $this->addColumn('flight', 'height', $this->double()->comment('Высота'));
        $this->addColumn('flight', 'length', $this->double()->comment('Длинна'));
        $this->addColumn('flight', 'diameter', $this->double()->comment('Диаметр'));
        $this->addColumn('flight', 'belts_count', $this->double()->comment('Кол-во ремней'));
        $this->addColumn('flight', 'logging_truck', $this->boolean()->defaultValue(false)->comment('Коники'));
        $this->addColumn('flight', 'road_train', $this->boolean()->defaultValue(false)->comment('Сцепка'));
        $this->addColumn('flight', 'air_suspension', $this->boolean()->defaultValue(false)->comment('Пневмоход'));
        $this->addColumn('flight', 'body_type', $this->integer()->comment('Кузов'));
        $this->addColumn('flight', 'priority_rate', $this->double()->comment('Ставка за просмотр'));
        $this->addColumn('flight', 'priority_limit', $this->double()->comment('Лимит на заявку'));
        $this->addColumn('flight', 'priority_daily_limit', $this->double()->comment('Суточный лимит'));
        $this->addColumn('flight', 'only_for_paid_users', $this->boolean()->defaultValue(false)->comment('Показывать приоритетный груз только платным пользователям'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'type_weight');
        $this->dropColumn('flight', 'loading_type');
        $this->dropColumn('flight', 'uploading_type');
        $this->dropColumn('flight', 'width');
        $this->dropColumn('flight', 'height');
        $this->dropColumn('flight', 'length');
        $this->dropColumn('flight', 'diameter');
        $this->dropColumn('flight', 'belts_count');
        $this->dropColumn('flight', 'logging_truck');
        $this->dropColumn('flight', 'road_train');
        $this->dropColumn('flight', 'air_suspension');
        $this->dropColumn('flight', 'body_type');
        $this->dropColumn('flight', 'priority_rate');
        $this->dropColumn('flight', 'priority_limit');
        $this->dropColumn('flight', 'priority_daily_limit');
        $this->dropColumn('flight', 'only_for_paid_users');
    }
}
