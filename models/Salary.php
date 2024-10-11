<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "salary".
 *
 * @property int $id
 * @property double $percent Процент без НДС
 * @property double $percent_with_nds Процент с НДС
 * @property double $withdraw Снятие
 * @property int $user_id Пользователь
 *
 * @property User $user
 */
class Salary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percent', 'percent_with_nds', 'withdraw', 'delta_percent', 'delta_percent_no_nds', 'delta_percent_additional', 'delta_recoil', 'limit'], 'number'],
            [['user_id', 'day_pays_min', 'day_pays_between'], 'integer'],
            [['carrier_payment_type', 'customer_payment_type'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percent' => 'Процент без НДС',
            'percent_with_nds' => 'Процент с НДС',
            'withdraw' => 'Снятие',
            'user_id' => 'Пользователь',
            'delta_percent' => 'Процент дельты',
            'delta_percent_no_nds' => 'Процент дельты без НДС',
            'delta_percent_additional' => 'Процент дельты (Доп. расходы)',
            'delta_recoil' => 'Процент балла',
            'limit' => 'Лимит',
            'day_pays_min' => 'Дни оплаты минимальные',
            'day_pays_between' => 'Дни оплаты между заказчиком и перевозчиком',
            'carrier_payment_type' => 'Тип оплаты перевозчика',
            'customer_payment_type' => 'Тип оплаты заказчика',            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
