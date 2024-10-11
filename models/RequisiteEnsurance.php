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
class RequisiteEnsurance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    
    public $condition_select;
    
    public static function tableName()
    {
        return 'requisite_ensurance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requisite_id'], 'integer'],
            [['percent'], 'number'],
            [['name', 'contract', 'condition'], 'string', 'max' => 255],
            [['requisite_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requisite::className(), 'targetAttribute' => ['requisite_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'requisite_id' => Yii::t('app', 'Реквизиты'),
            'name' => Yii::t('app', 'Название'),
            'contract' => Yii::t('app', 'Договор'),
            'condition' => Yii::t('app', 'Условие'),
            'percent' => Yii::t('app', 'Процент'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequisite()
    {
        return $this->hasOne(Requisite::className(), ['id' => 'requisite_id']);
    }
}
