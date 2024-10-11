<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $userId;
    public $email;
    public $username;
    public $organizationName;
    public $inn;
    public $password;
    public $name;
    public $surname;
    public $patronymic;
    public $snp;
    public $phone;
    public $category;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],

            ['email', 'trim'],
            ['email', 'email'],
            [['email', 'organizationName', 'inn', 'name', 'phone'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот email уже зарегистрирован',
                'when' => function($model, $attribute){
                    if($this->userId != null)
                    {
                        $userModel = User::findOne($this->userId);
                        return $this->{$attribute} !== $userModel->getOldAttribute($attribute);
                    }
                    return true;
                },
            ],

            ['password', 'string', 'min' => 6],
        ];
    }

    // public function scenarios()
    // {
    //     $scenarios = parent::scenarios();
    //     $scenarios['update'] = ['password', 'email'];//Scenario Values Only Accepted
    //     return $scenarios;
    // }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'E-mail',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'snp' => 'ФИО',
            'phone' => 'Номер телефона',
            'category' => 'Категория пользователя',
            'department' => 'Отдел',
            'organizationName' => "Наименование организации",
            'inn' => 'ИНН',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }


        $user = new User();
        $user->name = $this->name;
        $user->login = $this->username;
        $user->organizationName = $this->organizationName;
        $user->inn = $this->inn;
        $user->phone = $this->phone;
        $user->setPassword($this->password);
        // $user->status = 10; // active status
        $user->role_id = 8;
        // $user->generateAuthKey();

        if($this->inn && $this->organizationName){
            $client = \app\models\Client::find()->where(['inn' => $this->inn])->one();
            if($client == null){
                $client = new \app\models\Client();
                $client->inn = $this->inn;
                $client->name = $this->organizationName;
                $client->save(false);
            }

            $user->client_id = $client->id;
        }

        return $user->save();
    }

    /**
     * update user.
     *
     * @param User $user
     * @return User|null the saved model or null if saving fails
     */
    public function update($user)
    {
        if (!$this->validate()) {
            return null;
        }

        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->patronymic = $this->patronymic;
        $user->phone = $this->phone;
        $user->category = $this->category;
        $user->department = $this->department;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->password = $this->password;
        // $user->generateAuthKey();

        return $user->update();
    }
}
