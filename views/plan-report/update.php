<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Flight */
?>
<div class="flight-update">
    <?php 
        $tpl = '_form';
        //if(Yii::$app->controller->action->id == 'update-files') $tpl .='_files';
        
        echo $this->render($tpl, [
            'model' => $model,
        ]) 
    ?>

</div>
