<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "requisite".
*
    * @property string $name Наименование
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
    * @property string $fio_buhgaltera ФИО бухгалтера
    * @property  $nds НДС
    * @property  $pechat Печать
*/
class Requisite extends \app\base\ActiveRecord
{

    public $fileUploading;
    public $filePechat;
    public $fileSignature;

    public $rows;


    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'requisite';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['name', 'doljnost_rukovoditelya', 'fio_polnostyu', 'official_address', 'bank_name', 'inn', 'kpp', 'ogrn', 'bic', 'kr', 'nomer_rascheta', 'tel', 'fio_buhgaltera', 'post_address', 'card', 'main_bank_name', 'main_ogrn', 'main_bic', 'main_kr', 'main_nomer_rascheta', 'add_bank_name', 'add_ogrn', 'add_bic', 'add_kr', 'add_nomer_rascheta', 'contacts1', 'contacts2'], 'string'],
            [['nds', 'is_hidden'], 'integer'],
            [['pechat', 'signature', 'name_case', 'rows'], 'safe'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Наименование'),
            'name_case' => Yii::t('app', 'Наименование (Род. п.)'),
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
            'card' => Yii::t('app', 'Номер карты'),
            'tel' => Yii::t('app', 'тел.'),
            'fio_buhgaltera' => Yii::t('app', 'ФИО бухгалтера'),
            'nds' => Yii::t('app', 'НДС'),
            'pechat' => Yii::t('app', 'Печать'),
            'signature' => Yii::t('app', 'Подпись'),
            'post_address' => Yii::t('app', 'Почтовый адрес'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'is_hidden' => Yii::t('app', 'Скрытое'),
            'filePechat' => Yii::t('app', 'Печать'),
            'fileSignature' => Yii::t('app', 'Подпись'),

            'main_bank_name' => Yii::t('app', 'Наименование банка'),
            'main_ogrn' => Yii::t('app', 'ОГРН'),
            'main_bic' => Yii::t('app', 'Бик'),
            'main_kr' => Yii::t('app', 'КР'),
            'main_nomer_rascheta' => Yii::t('app', 'Номер расчета'),
        
            'add_bank_name' => Yii::t('app', 'Наименование банка'),
            'add_ogrn' => Yii::t('app', 'ОГРН'),
            'add_bic' => Yii::t('app', 'Бик'),
            'add_kr' => Yii::t('app', 'КР'),
            'add_nomer_rascheta' => Yii::t('app', 'Номер расчета'),
            
            'contacts1' => Yii::t('app', 'Контакты 1'),
            'contacts2' => Yii::t('app', 'Контакты 2'),

            'rows' => Yii::t('app', 'Страховка'),
        ];
    }

    

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        $filePechat = UploadedFile::getInstance($this, 'filePechat');
        if($filePechat ){
            if(is_dir('uploads') == false){
                mkdir('uploads');
            }

            $path = "uploads/".Yii::$app->security->generateRandomString().'.'.$filePechat->extension;

            $filePechat->saveAs($path);
            $this->pechat = $path;
        }

        $fileSignature = UploadedFile::getInstance($this, 'fileSignature');
        if($fileSignature ){
            if(is_dir('uploads') == false){
                mkdir('uploads');
            }

            $path = "uploads/".Yii::$app->security->generateRandomString().'.'.$fileSignature->extension;

            $fileSignature->saveAs($path);
            $this->signature = $path;
        }
        
        if($this->isNewRecord){
            $this->user_id = Yii::$app->user->getId();
        }

        
        return parent::beforeSave($insert);
    }

    /**
    * {@inheritdoc}
    */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->rows != null){
            $bankAll = RequisiteEnsurance::find()->where(['requisite_id' => $this->id])->all();
            foreach ($bankAll as $item1) {
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
                $bank = RequisiteEnsurance::find()->where(['id' => $item['id']])->one();

                $selection = isset($item['selection']) ? implode(',', $item['selection']) : null;
                
                //print_r($item['condition']); die;
                
                if (!$bank) {
                    $itemNew = new RequisiteEnsurance($item);
                    $itemNew->requisite_id = $this->id;
                    $itemNew->save(false);
                    
                    $requisite_ensurance_id = $itemNew->id;
                    
                } else {
                    $bank->name = isset($item['name']) ? $item['name'] : null;
                    $bank->contract = isset($item['contract']) ? $item['contract'] : null;
                    $bank->condition = isset($item['condition']) ? $item['condition'] : null;
                    $bank->percent = isset($item['percent']) ? $item['percent'] : null;

                    $bank->save(false);
                    
                    $requisite_ensurance_id = $bank->id;
                }
                
                if(!empty($item['condition']) && !empty($item['percent'])){
                $condition = \app\models\RequisiteEnsuranceConditions::find()->where(['condition' => $item['condition'],'percent' => $item['percent'],])->one();
                    if(!$condition){
                        $itemNew = new RequisiteEnsuranceConditions();
                        $itemNew->requisite_ensurance_id = $requisite_ensurance_id;
                        $itemNew->condition = isset($item['condition']) ? $item['condition'] : null;
                        $itemNew->percent = isset($item['percent']) ? $item['percent'] : null;                    
                        $itemNew->save(false);                    
                    }
                }
                //print_r($condition);
                //die;

                $counter++;
            }

        } else {
            if ($this->rows) {
                RequisiteEnsurance::deleteAll(['requisite_id' => $this->id]);
            }
        }

        if($this->bank_name){
            $bank = \app\models\Bank::find()->where(['name' => $this->bank_name])->one();
            if($bank == null){
                $bank = new \app\models\Bank();
            }

            $bank->name = $this->bank_name;
            $bank->inn = $this->inn;
            $bank->kpp = $this->kpp;
            $bank->ogrn = $this->ogrn;
            $bank->bik = $this->bic;
            $bank->kr = $this->kr;
            $bank->number = $this->nomer_rascheta;

            $bank->save(false);
        }

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



    
    
    
    
    
    
    
    
    
    
    
    
    
    public function banksMap()
    {
        $map = [];

        if($this->name){
            $map[$this->name] = "{$this->name} ({$this->nomer_rascheta})";
        }

        if($this->main_bank_name){
            $map[$this->main_bank_name] = "{$this->main_bank_name} ({$this->main_nomer_rascheta})";
        }

        if($this->add_bank_name){
            $map[$this->add_bank_name] = "{$this->add_bank_name} ({$this->add_nomer_rascheta})";
        }

        return $map;
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
    
    public function getInitials()
    {
        $name = $this->fio_polnostyu;

        $nameParts = explode(' ', $name);

        if(count($nameParts) >= 3){
            $nameParts[1] = mb_strtoupper(mb_substr($nameParts[1], 0, 1)).'.';
            $nameParts[2] = mb_strtoupper(mb_substr($nameParts[2], 0, 1)).'.';

            return $nameParts[0].' '.$nameParts[1].' '.$nameParts[2];
        }
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
        return $this->hasMany(Flight::className(), ['organization_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFlightsCount()
    {
        return \app\models\Flight::find()->where(['organization_id' => $this->id])->count();
    }

}