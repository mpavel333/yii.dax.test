<?php

namespace app\modules\api\controllers;

use yii\base\Controller;

class InfoController extends Controller
{


    /**
     *
     */
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

}