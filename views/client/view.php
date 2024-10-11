<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model Client */
?>
<div class="client-view">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Информация</h4>
                    <div class="panel-heading-btn" style="margin-top: -20px;">
                        <a class="btn btn-warning btn-xs" href="/client/update?id=<?= $model->id?>&amp;containerPjaxReload=%23pjax-container-info-container" role="modal-remote"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">

                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                                            'name',
                                    'doljnost_rukovoditelya',
                                    'fio_polnostyu',
                                    'official_address',
                                    'bank_name',
                                    'inn',
                                    'kpp',
                                    'ogrn',
                                    'bic',
                                    'kr',
                                    'nomer_rascheta',
                                    'tel',
                                    'email',
                                    'nds',
                                    'doc',
                                    'mailing_address',
                                    'code',
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
            'additionalLinkParams' => ['Flight[carrier_id]' => $model->id],
        ]); ?>
    </div>
    

      </div>

    
</div>
