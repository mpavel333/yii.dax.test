<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model Brand */
?>
<div class="brand-view">
    <div class="row">
        <?php Pjax::begin(['id' => 'crud-datatable-brand-pjax', 'enablePushState' => true]); ?>        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"  style="cursor: pointer" onclick="$(this).parent().find('.panel-body').slideToggle();">
                    <h4 class="panel-title">Информация</h4>
                    <div class="panel-heading-btn" style="margin-top: -20px;">
                        <a class="btn btn-warning btn-xs" href="/brand/update?id=<?= $model->id?>&amp;containerPjaxReload=%23pjax-container-info-container" role="modal-remote"><span class="glyphicon glyphicon-pencil"></span></a>
                    </div>
                </div>
                <div class="panel-body" style="display: none;">
                    <div class="row">
                            
                        <div class="col-md-12">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                                            'name',
                                ],
                            ]) ?>

                        </div>                         </div>
                </div>
            </div>
        </div>
        <?php Pjax::end() ?> 




    
            
        
    <div class="col-md-12">
   <?= $this->render("@app/views/goods/index.php", [
            'searchModel' => $goodsSearchModel,
            'dataProvider' => $goodsDataProvider,
            'additionalLinkParams' => ['Goods[brand_id]' => $model->id],
        ]); ?>
    </div>
    

      </div>

    
</div>
