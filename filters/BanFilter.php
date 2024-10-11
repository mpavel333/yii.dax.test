<?php

namespace app\filters;

use Yii;
use yii\base\ActionFilter;
use app\models\User;
use yii\web\ForbiddenHttpException;

/**
 * Class BanFilter
 * @package app\filters
 */
class BanFilter extends ActionFilter
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if(Yii::$app->user->isGuest == false){
            if($user->role == User::ROLE_CANDIDATE){
                if($user->candidate && $user->candidate->is_banned == 1){
                    throw new ForbiddenHttpException('You are banned');
                }
            }
        }

        return parent::beforeAction($action);
    }
}