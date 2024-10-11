<?php

namespace app\modules\mobile;

/**
 * api module definition class
 */
class Mobile extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\mobile\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();


        if(\Yii::$app->request->headers->has('api-language')){
            \Yii::$app->language = \Yii::$app->request->headers->get('api-language');
        } else {
            \Yii::$app->language = 'ru';
        }
    }
}
