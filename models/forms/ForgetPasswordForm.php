<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use Yii;

class ForgetPasswordForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email']
        ];
    }

    /**
     * @return bool
     */
    public function changePassword()
    {
        if($this->validate())
        {
            $user = User::find()->where(['login' => $this->email])->one();

            if($user == null){
                $this->addError('Email не найден');
                return false;
            }

            $user->password = \Yii::$app->security->generateRandomString(10);
            $user->save(false);

            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom('a.ntfier@yandex.ru')
                ->setSubject('Востановление пароля')
                ->setHtmlBody("<p>Доброго времени суток</p>
                <p>Ваш новый пароль: <b>{$user->password}</b></p>")
                ->send();


            return true;
        }

        return false;
    }
}