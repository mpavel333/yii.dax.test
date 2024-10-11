<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "car".
 *
 * @property int $id
 * @property string $name Название
 * @property string $number Гос. номер
 * @property int $status Статус
 * @property int $driver_id Водитель
 * @property double $mileage Общий километраж
 *
 * @property Client $driver
 * @property User $user
 * @property Driver $driver
 */
class Car extends \yii\db\ActiveRecord
{
    const STATUS_WORKING = 1;
    const STATUS_REPAIR = 2;
    const STATUS_EMPTY = 3;

    public $items;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'driver_id', 'client_id'], 'integer'],
            [['mileage'], 'number'],
            [['name', 'number','truck_number','phone','driver', 'osago_number', 'kasko_number', 'license_number'], 'string', 'max' => 255],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['items', 'osago_date_start', 'osago_date_end', 'kasko_date_start', 'kasko_date_end', 'license_date_start', 'license_date_end', 'is_rent', 'files', 'total_summ'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'number' => 'Гос. номер',
            'truck_number' => 'Гос. номер прицепа',
            'phone' => 'Телефон водителя',
            'driver' => 'Паспортные данные водителя',
            'status' => 'Статус',
            'driver_id' => 'Водитель',
            'mileage' => 'Общий километраж',
            'items' => 'ТО',
            'osago_number' => 'Номер ОСАГО',
            'osago_date_start' => 'Дата начала',
            'osago_date_end' => 'Дата окончания',
            'kasko_number' => 'Номер КАСКО',
            'kasko_date_start' => 'Дата начала',
            'kasko_date_end' => 'Дата окончания',
            'client_id' => 'Организация',
            'license_number' => 'Номер прав',
            'license_date_start' => 'Дата начала',
            'license_date_end' => 'Дата окончания',
            'is_rent' => 'Арендованная',
            'user_id' => 'Диспетчер',
            'files' => 'Файлы',
        ];
    }

    public static function statusLabels()
    {
        return [
            self::STATUS_WORKING => "В рейсе",
            self::STATUS_REPAIR => "Ремонт",
            self::STATUS_EMPTY => "Пустой",
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {

        if(isset($_POST['file_files_path'])){
            $this->files = json_decode($this->files, true);
            $newfile = json_decode($_POST['file_files_path'], true);
            $this->files = json_encode(\yii\helpers\ArrayHelper::merge($this->files ? $this->files : [], $newfile ? $newfile : []), JSON_UNESCAPED_UNICODE);
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->items = isset($_POST['Car']['items']) ? $_POST['Car']['items'] : [];

        \Yii::warning($this->items, 'items');


        if ($this->items != null){
            $bankAll = CarTo::find()->where(['car_id' => $this->id])->all();
            foreach ($bankAll as $item1) {
                $a = false;
                foreach ($this->items as $item3) {
                    if ($item3['id'] == $item1->id) {
                        $a = true;
                    }
                }
                if (!$a) {
                    $item1->delete();
                }
            }
            foreach ($this->items as $item) {
                $bank = CarTo::find()->where(['id' => $item['id']])->one();

                \Yii::warning($bank, '$bank');

                if (!$bank) {
                    $carToItem = $item;
                    unset($carToItem['close']);
                    $itemNew = new CarTo($carToItem);
                    $itemNew->car_id = $this->id;
                    $itemNew->name = isset($item['name']) ? $item['name'] : null;
                    $itemNew->date = isset($item['date']) ? $item['date'] : null;
                    $itemNew->mileage = isset($item['mileage']) ? $item['mileage'] : null;
                    $itemNew->info = isset($item['info']) ? $item['info'] : null;
                    $itemNew->response_user = isset($item['response_user']) ? $item['response_user'] : null;
                    $itemNew->driver_id = isset($item['driver_id']) ? $item['driver_id'] : null;
                    // $itemNew->close = isset($item['close']) ? $item['close'] : null;

                    if(isset($item['close']))
                       $itemNew->close_user_id = ($item['close']==1) ? \Yii::$app->user->getId() : null;
                    
                    $itemNew->save(false);
                } else {
                    $bank->name = isset($item['name']) ? $item['name'] : null;
                    $bank->date = isset($item['date']) ? $item['date'] : null;
                    $bank->mileage = isset($item['mileage']) ? $item['mileage'] : null;
                    $bank->info = isset($item['info']) ? $item['info'] : null;
                    $bank->response_user = isset($item['response_user']) ? $item['response_user'] : null;
                    $bank->driver_id = isset($item['driver_id']) ? $item['driver_id'] : null;
                    // $bank->close = isset($item['close']) ? $item['close'] : null;
                    
                    if(isset($item['close']))
                       $bank->close_user_id = ($item['close']==1) ? \Yii::$app->user->getId() : null;
                    
                    $bank->save(false);
                }
            }

        } else {
            if (count($this->items) == 0) {
                CarTo::deleteAll(['car_id' => $this->id]);
            }
        }
    }


    public function tags($text)
    {
        $arr = [];

        $datesArr = ['date_cr', 'date', 'shipping_date', 'date_out4', 'date_out2', 'date_out5', 'date_out3', 'date_out6'];

        $result = preg_match_all('/\{.*?}/',$text,$arr);
        \Yii::warning($arr, 'TAGS');
        foreach ($arr[0] as $value) {
            $value2 = str_replace('{', '', $value);
            $value2 = str_replace('}', '', $value2);


            try {
                if(in_array($value2, $datesArr)){
                    $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2) ? Yii::$app->formatter->asDate(yii\helpers\ArrayHelper::getValue($this, $value2), 'php:d.m.Y') : null, $text);
                } else {
                    $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2), $text);
                }
            } catch(\yii\base\UnknownPropertyException $e){

            }

        }





        $additionals = [

        ];

        foreach ($additionals as $attr => $value) {
        }

        return $text;
    }  



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(Driver::className(), ['id' => 'driver_id']);
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
    public function getFlight()
    {
        return $this->hasMany(Flight::className(), ['driver_id' => 'driver_id']);
    }

}
