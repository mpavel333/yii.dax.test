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
class StructureUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'structure_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['structure_id','user_id'], 'integer'],
            //[['percent'], 'number'],
            [['login', 'name', 'email', 'phone', 'tabel'], 'string', 'max' => 255],
            //[['structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structure::className(), 'targetAttribute' => ['structure_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'structure_id' => Yii::t('app', 'Отдел'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'login' => Yii::t('app', 'Логин'),
            'name' => Yii::t('app', 'ФИО'),
            'email' => Yii::t('app', 'Почта'),
            'phone' => Yii::t('app', 'Телефон'),
            'tabel' => Yii::t('app', 'Табель'),
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
