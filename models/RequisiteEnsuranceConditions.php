<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requisite_ensurance".
 *
 * @property int $id
 * @property int $requisite_id Реквизиты
 * @property string $name Название
 * @property string $contract Договор
 * @property string $condition Условие
 * @property double $percent Процент
 *
 * @property Requisite $requisite
 */
class RequisiteEnsuranceConditions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requisite_ensurance_conditions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requisite_ensurance_id'], 'integer'],
            [['percent'], 'number'],
            [['condition'], 'string', 'max' => 255],
            //[['requisite_ensurance_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequisiteEnsurance::className(), 'targetAttribute' => ['requisite_ensurance_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'requisite_ensurance_id' => Yii::t('app', 'Страховка'),
            //'name' => Yii::t('app', 'Название'),
            //'contract' => Yii::t('app', 'Договор'),
            'condition' => Yii::t('app', 'Условие'),
            'percent' => Yii::t('app', 'Процент'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequisiteEnsurance()
    {
        return $this->hasOne(RequisiteEnsurance::className(), ['id' => 'requisite_ensurance_id']);
    }
}
