<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "structure".
*
    * @property string $data Водитель ФИО
    * @property  $structure Водитель паспортные данные
    * @property string $phone Телефон
    * @property string $data_avto Данные автомобиля
*/
class Structure extends \app\base\ActiveRecord
{

    public $fileUploading;
    public $rows;
    

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'structure';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            //[['data'], 'unique'],
            [['name'], 'string'],
            [['rows'], 'safe'],
            //[['user_id'], 'integer'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Название отдела'),
            /*
            'structure' => Yii::t('app', 'Водитель паспортные данные'),
            'phone' => Yii::t('app', 'Телефон'),
            'data_avto' => Yii::t('app', 'Модель автомобиля'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'info' => Yii::t('app', 'Информация о водителе'),
            'car_number' => Yii::t('app', 'Гос.номер автомобиля'),
            'car_truck_number' => Yii::t('app', 'Гос.номер прицепа'),
            */
        ];
    }

    

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {

        if($this->isNewRecord){
            //$this->user_id = Yii::$app->user->getId();
        }
        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

        

        
        if ($this->rows != null){
            
            
            $structureUserAll = StructureUsers::find()->where(['structure_id' => $this->id])->all();
            foreach ($structureUserAll as $item1) {
                $a = false;
                foreach ($this->rows as $item3) {
                    if ($item3['id'] == $item1->id) {
                        $a = true;
                    }
                }
                if (!$a) {
                    $item1->delete();
                }
            }
            
            $counter = 0;

            


            foreach ($this->rows as $item) {
                if ($item['id'] == '') $item['id'] = null;
                $structureUser = StructureUsers::find()->where(['id' => $item['id']])->one();

                //$selection = isset($item['selection']) ? implode(',', $item['selection']) : null;

                if (!$structureUser) {
                    $itemNew = new StructureUsers($item);
                    $itemNew->structure_id = $this->id;
                    $itemNew->user_id = isset($item['user_id']) ? $item['user_id'] : null;
                    $itemNew->login = isset($item['login']) ? $item['login'] : null;
                    $itemNew->name = isset($item['name']) ? $item['name'] : null;
                    $itemNew->email = isset($item['email']) ? $item['email'] : null;
                    $itemNew->phone = isset($item['phone']) ? $item['phone'] : null;
                    $itemNew->tabel = isset($item['tabel']) ? $item['tabel'] : null;

                    $itemNew->save(false);
                } else {

                    $structureUser->user_id = isset($item['user_id']) ? $item['user_id'] : null;
                    $structureUser->login = isset($item['login']) ? $item['login'] : null;
                    $structureUser->name = isset($item['name']) ? $item['name'] : null;
                    $structureUser->email = isset($item['email']) ? $item['email'] : null;
                    $structureUser->phone = isset($item['phone']) ? $item['phone'] : null;
                    $structureUser->tabel = isset($item['tabel']) ? $item['tabel'] : null;

                    $structureUser->save(false);
                }

                $counter++;
            }

        } else {
            if ($this->rows) {
                StructureUsers::deleteAll(['structure_id' => $this->id]);
            }
        }      
        
        
        //die;

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
    public function getStructureUsers()
    {
        return $this->hasMany(StructureUsers::className(), ['structure_id'=>'id']);
    }

}