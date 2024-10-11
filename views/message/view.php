<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model Message */
?>
<div class="message-view">
    <div class="row">
        <div class="col-md-12">
        <?php Pjax::begin(['id' => 'crud-datatable-message-pjax', 'enablePushState' => true]); ?>        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"  style="cursor: pointer" onclick="if($(event.target).prop('tagName') == 'H4'){ $(this).parent().find('.panel-body').slideToggle(); }">
<div class="btn-headeding-btn">
                        <a class="btn btn-xs btn-info pull-left" href="#" onclick="history.go(-1)" data-pjax="0" style="margin-right: 5px;"><i class="fa fa-arrow-left"></i></a>                    </div>
                    <h4 class="panel-title">Информация</h4>
                    <div class="panel-heading-btn" style="margin-top: -20px;">
                        <a class="btn btn-warning btn-xs" href="/message/update?id=<?= $model->id?>&amp;containerPjaxReload=%23pjax-container-info-container" role="modal-remote"><span class="glyphicon glyphicon-pencil"></span></a>
                    </div>
                </div>
                <div class="panel-body" style="display: none;">
                    <div class="row">
                            
                        <div class="col-md-12">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                        
                                            
                                    [
                                        'value' => $model->flight_id ? $model->flight->index : "",
                                        'attribute' => 'flight_id',
                                    ],

                                                                                'text',

                                            
                                    [
                                        'value' => $model->user_id ? $model->user->name : "",
                                        'attribute' => 'user_id',
                                    ],

                                            
                                    [
                                        'value' => $model->user_to_id ? $model->userTo->login : "",
                                        'attribute' => 'user_to_id',
                                    ],

                                                                                'create_at',

                                                                                'is_read',
                                ],
                            ]) ?>

                        </div>                         </div>
                </div>
            </div>
        <?php Pjax::end() ?> 
        </div>
    </div>



    
    

      </div>

    
</div>
