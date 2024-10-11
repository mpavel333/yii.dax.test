<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


class PlanReport extends \app\base\ActiveRecord
{

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'flight';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {

        return [
             
            [['sum'], 'integer'],

        ];
        /*
        return [
            [['driver_id', 'user_id', 'summ'], 'required'],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            
            [['driver_id', 'user_id'], 'integer'],
            [['number', 'name'], 'string'],
            [['date'], 'safe'],
            [['summ'], 'number'],

        ];
        */
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'sum' => Yii::t('app', 'Общая сумма в мес., руб. Мин. сумма'),
        ];       
        
        /*
        return [
            'user_id' => Yii::t('app', 'Менеджер'),
            'driver_id' => Yii::t('app', 'Водитель'),
            'number' => Yii::t('app', 'Гос. номер'),
            'name' => Yii::t('app', 'Название'),
            'summ' => Yii::t('app', 'Общая сумма в месяц руб.'),
            'date' => Yii::t('app', 'Дата')
        ];
        */
    }

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {

        return parent::beforeSave($insert);
    }


    public function afterSave($insert, $changedAttributes)
    {

        //parent::afterSave($insert, $changedAttributes);
    }
   
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
            
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getDriver()
    {
        return $this->hasOne(Driver::className(), ['id' => 'driver_id']);
    }


}
