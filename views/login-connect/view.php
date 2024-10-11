<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model LoginConnect */
?>
<div class="login-connect-view">
    <div class="row">
        <div class="col-md-12">
        <?php Pjax::begin(['id' => 'crud-datatable-login_connect-pjax', 'enablePushState' => true]); ?>        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"  style="cursor: pointer" onclick="if($(event.target).prop('tagName') == 'H4'){ $(this).parent().find('.panel-body').slideToggle(); }">
<div class="btn-headeding-btn">
                        <a class="btn btn-xs btn-info pull-left" href="#" onclick="history.go(-1)" data-pjax="0" style="margin-right: 5px;"><i class="fa fa-arrow-left"></i></a>                    </div>
                    <h4 class="panel-title">Информация</h4>
                    <div class="panel-heading-btn" style="margin-top: -20px;">
                        <a class="btn btn-warning btn-xs" href="/login-connect/update?id=<?= $model->id?>&amp;containerPjaxReload=%23pjax-container-info-container" role="modal-remote"><span class="glyphicon glyphicon-pencil"></span></a>
                    </div>
                </div>
                <div class="panel-body" style="display: none;">
                    <div class="row">
                            
                        <div class="col-md-12">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                        
                                                                                'ip_address',

                                                                                'status',

                                                                                'login',

                                                                                'password',

                                                                                'code',

                                                                                'create_at',
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
