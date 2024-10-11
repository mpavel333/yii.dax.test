<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "client".
*
    * @property string $name Наименование
    * @property int $type Тип
    * @property string $doljnost_rukovoditelya Должность руководителя
    * @property string $fio_polnostyu ФИО полностью
    * @property string $official_address Юридический адрес
    * @property string $bank_name Наименование банка
    * @property string $inn ИНН
    * @property string $kpp КПП
    * @property string $ogrn ОГРН
    * @property string $bic Бик
    * @property string $kr КР
    * @property string $nomer_rascheta Номер расчета
    * @property string $tel тел.
    * @property string $email email
    * @property  $nds НДС
    * @property string $doc Договор
    * @property string $mailing_address Почтовый адрес
    * @property string $code Код АТИ
    * @property string $comment Комментарий
*/
class Client extends \app\base\ActiveRecord
{

    const TYPE_CLIENT = 1;
    const TYPE_DRIVER = 2;

    public $fileUploading;
    

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'client';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['name', 'doljnost_rukovoditelya', 'fio_polnostyu', 'official_address', 'bank_name', 'inn', 'kpp', 'ogrn', 'bic', 'kr', 'nomer_rascheta', 'tel', 'email', 'doc', 'mailing_address', 'code', 'file', 'name_case', 'users', 'contact', 'limit', 'comment', 'doc_date', 'payment_terms','payment_type', 'tabel'], 'string'],
            [['nds', 'user_id', 'type', 'organization_id', 'contract', 'contract_orig', 'is_lawyer'], 'integer'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Наименование'),
            'type' => Yii::t('app', 'Тип'),
            'name_case' => Yii::t('app', 'ФИО (Род. п.)'),
            'doljnost_rukovoditelya' => Yii::t('app', 'Должность руководителя'),
            'fio_polnostyu' => Yii::t('app', 'ФИО полностью'),
            'official_address' => Yii::t('app', 'Юридический адрес'),
            'bank_name' => Yii::t('app', 'Наименование банка'),
            'inn' => Yii::t('app', 'ИНН'),
            'kpp' => Yii::t('app', 'КПП'),
            'ogrn' => Yii::t('app', 'ОГРН'),
            'bic' => Yii::t('app', 'Бик'),
            'kr' => Yii::t('app', 'КР'),
            'nomer_rascheta' => Yii::t('app', 'Номер расчета'),
            'tel' => Yii::t('app', 'тел.'),
            'email' => Yii::t('app', 'email'),
            'nds' => Yii::t('app', 'Кол-во дней'),
            'doc' => Yii::t('app', 'Договор'),
            'mailing_address' => Yii::t('app', 'Почтовый адрес'),
            'code' => Yii::t('app', 'Код АТИ'),
            'user_id' => Yii::t('app', 'Создал'),
            'users' => Yii::t('app', 'Пользователи'),
            'contact' => Yii::t('app', 'Контакт и телефон'),
            'organization_id' => Yii::t('app', 'Наша организация'),
            'limit' => Yii::t('app', 'Лимит'),
            'contract'=> Yii::t('app', 'Договор Скан'),
            
            'contract_orig'=> Yii::t('app', 'Договор Оригинал'),
            'payment_terms'=> Yii::t('app', 'Сроки оплаты'),
            'payment_type'=> Yii::t('app', 'Тип оплаты'),
            

            'comment' => Yii::t('app', 'Комментарий'),
            'doc_date' => Yii::t('app', 'Дата'),
            
            'tabel' => Yii::t('app', 'Табель'),
        ];
    }

    

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

    public static function getTypeLabels()
    {
        return [
            self::TYPE_CLIENT => 'Заказчик',
            self::TYPE_DRIVER => 'Перевозчик',
        ];
    }




    public function getNamePart()
    {
        $parts = preg_split('/\s+/', $this->fio_polnostyu);

        return isset($parts[1]) ? $parts[1] : null;
    }
    public function getLastNamePart()
    {
        $parts = preg_split('/\s+/', $this->fio_polnostyu);

        return isset($parts[0]) ? $parts[0] : null;
    }
    public function getPatronymicPart()
    {
        $parts = preg_split('/\s+/', $this->fio_polnostyu);

        return isset($parts[2]) ? $parts[2] : null;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    


    public function tags($text)
    {
        $arr = [];
        $result = preg_match_all('/\{.*?}/',$text,$arr);
        foreach ($arr[0] as $value) {
            $value2 = str_replace('{', '', $value);
            $value2 = str_replace('}', '', $value2);
            try {
                $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2), $text);
            } catch(\Exception $e){
                $text = str_replace($value, null, $text);
            }
        }

        return $text;
    }  
    

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrganization()
    {
        return $this->hasOne(Requisite::className(), ['id' => 'organization_id']);
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
        return $this->hasMany(Flight::className(), ['zakazchik_id' => 'id']);
    }
    

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFlights0()
    {
        return $this->hasMany(Flight::className(), ['carrier_id' => 'id']);
    }

}