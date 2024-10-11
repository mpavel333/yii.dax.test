<?php

namespace app\modules\api\models;

use app\models\MobileUser;
use app\models\MUserWallet;
use Yii;
use yii\base\Model;

/**
 * Class RegisterModel
 * @package app\modules\api\models
 */
class RegisterModel extends Model
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password', 'email'], 'required'],
            [['login', 'password'], 'string'],
            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => MobileUser::className(), 'targetAttribute' => 'email'],
        ];
    }

    /**
     * @return string
     */
    public function register()
    {
        if($this->validate()){
            $user = new MobileUser([
                'login' => $this->login,
                'password' => $this->password,
                'email' => $this->email,
            ]);
            $user->save(false);

            return $user->token;
        }

        return null;
    }
}