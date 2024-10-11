<?php

namespace app\bootstrap;

use kartik\grid\GridView;
use Yii;


/**
 * Class AppBootstrap
 * @package app\bootstrap
 */
class AppBootstrap implements \yii\base\BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\base\Application $app the application currently running
     */
    public function bootstrap($app)
    {
        /////////// 
        // Дополнительная проверка авторизации, чтобы небыло ошибок в шаблоне
        $login = \app\models\LoginConnect::find()->where(['ip_address' => Yii::$app->request->getUserIP()])->one(); 

        if (Yii::$app->user->isGuest == false && !$login) {
           Yii::$app->user->logout();
           header("Refresh: 0");
        }        
        ///////////
        
        Yii::$container->set(GridView::className(), [
            'containerOptions' => ['style' => 'width: 99%;'],
            'responsive' => true,
        ]);

        Yii::$container->set(\yii\data\ActiveDataProvider::className(), [
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        Yii::$container->set('kartik\grid\SerialColumn', 'app\widgets\SerialColumnReverse');


        Yii::$container->setDefinitions([
            'yii\widgets\ActiveField' => 'app\base\ActiveField',
            'unclead\multipleinput\components\BaseColumn' => 'app\base\BaseColumn',
        ]);

        $roles = Yii::$app->myCache->get('roles');
        if($roles == null){
            $roles = \app\models\Role::find()->all();
            Yii::$app->myCache->set('roles',$roles);
        }

    }
}