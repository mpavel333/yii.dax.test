<?php

namespace app\modules\mobile\models;

use Yii;
use app\models\User;
use yii\base\Model;

/**
 * Class Login
 * @package app\modules\api\models
 */
class Login extends Model
{
    public $login;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login', 'password'], 'string'],
        ];
    }

    /**
     * @return string
     */
    public function login()
    {
        if($this->validate()){
            $user = User::find()->where(['login' => $this->login])->one();

            if($user){

                if($user->validatePassword($this->password)){
                    return $user->token;

                }

                return null;

            } else {
                return null;
            }
        }

        return null;
    }
}