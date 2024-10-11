<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "driver".
*
    * @property string $data Водитель ФИО
    * @property  $driver Водитель паспортные данные
    * @property string $phone Телефон
    * @property string $data_avto Данные автомобиля
*/
class Driver extends \app\base\ActiveRecord
{

    public $fileUploading;
    

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'driver';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['data','snils','car_number','car_truck_number'], 'unique'],
            [['data', 'driver', 'phone', 'data_avto', 'file', 'info', 'car_number', 'car_truck_number', 'snils'], 'string'],
            [['user_id'], 'integer'],
            //['data', 'dataValidator', 'skipOnError' => false, 'skipOnEmpty' => false],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'data' => Yii::t('app', 'Водитель ФИО'),
            'driver' => Yii::t('app', 'Водитель паспортные данные'),
            'phone' => Yii::t('app', 'Телефон'),
            'data_avto' => Yii::t('app', 'Модель автомобиля'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'info' => Yii::t('app', 'Информация о водителе'),
            'car_number' => Yii::t('app', 'Гос.номер автомобиля'),
            'car_truck_number' => Yii::t('app', 'Гос.номер прицепа'),
            'snils' => Yii::t('app', 'СНИЛС'),
        ];
    }
    /*
    public function dataValidator($attribute,$params){
        if(!$this->hasErrors()){
            
            
            $query = \app\models\Driver::find();
            
            $query->andFilterWhere(['=', 'data', $this->data]);
            $query->andFilterWhere(['=', 'snils', $this->snils]);
            $query->andFilterWhere(['=', 'car_number', $this->car_number]);
            $query->andFilterWhere(['=', 'car_truck_number', $this->car_truck_number]);
            
            $driver = $query->one();
            
            //print_r($driver);
            //$this->addError('data', 'Дельта (процент) должен быть больше 10');
            
            
            if(doubleval($this->payment_out)){
                $salary = \app\models\Salary::find()->one();

                $one = doubleval($this->we) - doubleval($this->recoil) - doubleval($this->additional_credit) - doubleval($this->ensurance_prime);
                if(is_numeric($one) && is_numeric($one) && doubleval($one) > 0 && doubleval($this->payment_out)){
                    $one = doubleval($this->payment_out) / doubleval($one);
                    $one = $one - 1;
                    $one = $one * 100;
                    $one = 100 - $one;
                    $one = $one - 100;
    
                    //$this->delta_percent = round($one, 2);

                    //if(round($this->delta_percent)<10){
                    //    $this->addError('delta_percent', 'Дельта (процент) должен быть больше 10');
                    //}
    
                    $salaryAttr = 'delta_percent';
                    if($this->pay_us == 'без НДС' || $this->otherwise2 == 'без НДС'){
                        
                        \Yii::warning('in delta_percent_no_nds', 'in delta_percent_no_nds');
                        $salaryAttr = 'delta_percent_no_nds';
                    }
    
                    \Yii::warning("{$one} < {$salary->$salaryAttr}", 'delta condition');
                    if($one < $salary->$salaryAttr){
                        $salary->$salaryAttr = $one;
                        $this->addError('delta_percent', 'Оплата водителя выше');
                    }
                }
            }
            
        }
    }    
    */
    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {

        if(isset($_POST['file_file_path'])){
            $this->file = json_decode($this->file, true);
            $newfile = json_decode($_POST['file_file_path'], true);
            $this->file = json_encode(\yii\helpers\ArrayHelper::merge($this->file ? $this->file : [], $newfile ? $newfile : []), JSON_UNESCAPED_UNICODE);
        }

        if($this->isNewRecord){
            $this->user_id = Yii::$app->user->getId();
        }
        
        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

    }




    
    
    
    


    public function tags($text)
    {
        $arr = [];
        $result = preg_match_all('/\{.*?}/',$text,$arr);
        foreach ($arr[0] as $value) {
            $value2 = str_replace('{', '', $value);
            $value2 = str_replace('}', '', $value2);
            $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2), $text);
        }

        return $text;
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
    public function getFlights()
    {
        return $this->hasMany(Flight::className(), ['driver_id' => 'id']);
    }

}