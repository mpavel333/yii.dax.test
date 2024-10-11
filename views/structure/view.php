<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model Driver */
?>
<div class="driver-view">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Информация</h4>
                    <div class="panel-heading-btn" style="margin-top: -20px;">
                        <a class="btn btn-warning btn-xs" href="/driver/update?id=<?= $model->id?>&amp;containerPjaxReload=%23pjax-container-info-container" role="modal-remote"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">

                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                                            'data',
                                    'driver',
                                    'phone',
                                    'data_avto',
                                ],
                            ]) ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    




    
            
        
    <div class="col-md-12">
   <?= $this->render("@app/views/flight/index.php", [
            'searchModel' => $flightSearchModel,
            'dataProvider' => $flightDataProvider,
            'additionalLinkParams' => ['Flight[driver_id]' => $model->id],
        ]); ?>
    </div>
    

      </div>

    
</div>
