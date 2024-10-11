<?php

use app\models\Call;
use app\models\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Call */
/* @var $form yii\widgets\ActiveForm */

if($model->isNewRecord){
    $model->create_at = date('Y-m-d H:i:s');
}
if($model->isNewRecord == false){
    $model->rows = \app\models\CallRow::find()->where(['call_id' => $model->id])->orderBy('datetime desc')->asArray()->all();
    for ($i=0; $i < count($model->rows); $i++) {
        $row = $model->rows[$i];
        foreach($row as $key => $value){
        }
    }
}

?>

<style type="text/css">
    .mfp-bg {
        z-index: 10042 !important;
    }

    .mfp-wrap {
        z-index: 10043 !important;
    }

    .panel-default .panel-heading {
        background: #ededed !important;
    }

    .panel-default .panel-body {
        padding: 10px !important;
        background: #f9f9f9 !important;
        border-right: 1px solid #e9e9e9;
        border-left: 1px solid #e9e9e9;
        border-bottom: 1px solid #e9e9e9;
    }

    .multiple-input table tr td, .multiple-input table tr th {
        background: #f9f9f9;
    }
</style>

<div class="call-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Звонок</h4>
                </div>
                <div class="panel-body">
                    <div class="row">

                         <?= $form->field($model, 'rows', ['cols' => 12, 'colsOptionsStr' => " ", "checkPermission" => false])->widget(unclead\multipleinput\MultipleInput::className(), [
                            'id' => 'my_idchannels',
                            'min' => 0,
            //'sortable' => true,        
                            'columns' => [

                                [
                                    'name' => 'id',
                                    'options' => [
                                        'type' => 'hidden'
                                    ]
                                ],
                                [
                                    'name' => 'text',
                                    'title' => Yii::t('app','Текст результата'),
                                    'enableError' => true,
                                ],        
                                [
                                    'name' => 'datetime',
                                    'title' => Yii::t('app','Дата и время'),                
                                ],        
                                ],
                            ])->label(false)  ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Данные</h4>
                </div>
                <div class="panel-body">
                    <div class="row">

                        <?= $form->field($model, 'client_id', ['cols' => 6, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
                            'data' => ArrayHelper::map(Client::find()->where(['type' => Client::TYPE_CLIENT])->all(), 'id', 'name'),
                            'options' => [
                                'allowClear' => true,
                                'placeholder' => 'Выберите',
                            ],
                        ]) ?>

                        <?= $form->field($model, 'inn', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'region', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['maxlength' => true]) ?>
                        
                        <?= $form->field($model, 'city', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['maxlength' => true]) ?>
                        
                        <?= $form->field($model, 'post', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['maxlength' => true]) ?>
                        
                        <?= $form->field($model, 'site', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['maxlength' => true]) ?>




                        <?= $form->field($model, 'phone', ['cols' => 12, 'colsOptionsStr' => " "])->textarea(['rows' => 1]) ?>


                        <?= $form->field($model, 'industry', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['maxlength' => true]) ?>



                        <?= $form->field($model, 'timezone', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['maxlength' => true]) ?>



                        <?= $form->field($model, 'result', ['cols' => 12, 'colsOptionsStr' => " "])->dropDownList(Call::resultList(), ['prompt' => 'Выберите']) ?>


                        <?= $form->field($model, 'status', ['cols' => 12, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
                            'data' => Call::statusLabels(),
                            'options' => [
                                'allowClear' => true,
                                'placeholder' => 'Выберите',
                            ],
                        ])->label('Статус') ?>


                    </div>
                </div>
            </div>
        </div>

                        <div class="col-md-12" <?= \Yii::$app->user->identity->can('flight_upload_file') ? '' : 'style="display: none;"' ?>>
                            <div class="row">
                <div class="col-md-5" style="height: 200px; overflow: auto;">
                      <label>Файлы</label>
            <?= \kato\DropZone::widget([
    'id'        => 'dzImage_file',
    'uploadUrl' => \yii\helpers\Url::toRoute([ '/call/upload-file']),
    'dropzoneContainer' => 'dz-container-images',
    'previewsContainer' => 'preview-images', // <-- уникальные previewsContainer,
    'options' => [
    'dictDefaultMessage' => 'Перетащите файлы сюда',
    ],
    'clientEvents' => [
      'complete' => "function(file){
        var path = JSON.parse(file.xhr.response);
        if($('[name=\'file_file_path\']').val()){
          var value = JSON.parse($('[name=\'file_file_path\']').val());
        } else {
          var value = [];
        }        console.log(value, 'current value');
        value.push(path);
        console.log(value, 'new value');        $('[name=\'file_file_path\']').val(JSON.stringify(value));
        var arr = [ 'jpeg', 'jpg', 'gif', 'png' ];
        var strE = path.url;        var ext = strE.substring(strE.lastIndexOf('.')+1);
        if(arr.includes(ext)){
          strE = path.url;
        } else {
          strE = 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';
        }
        var html = '<tr class=\"template-upload fade in\"><td><span class=\"preview\"><img src=\"'+strE+'\" style=\"width: 50px;\"></span></td>';
        html += '<td><p class=\"name\">'+path.name+'</p><p class=\"size\">'+path.size+'</p></td>';
        html += '<td><a data-delete=\"'+path.url+'\" class=\"btn btn-danger cancel\"><i class=\"glyphicon glyphicon-ban-circle\"></i><span>Удалить</span></a></td></tr>';
        $('.files').append(html);
        $('.files [data-delete]').unbind('click');
        $('.files [data-delete]').click(deleteEventHandler);
        $('.files [data-delete]').on('keydown', keyDownEventHandler);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_file_path">
</div>
<?php
$script = <<< JS


var keyDownEventHandler = function(e){
   if(e.keyCode === 13){
       e.preventDefault();
   }
};



var deleteEventHandler = function(e){
  e.preventDefault();

  var path = $(this).data('delete');

  var self = $(this).parent().parent();

  if($('[name=\'file_file_path\']').val()){
    var data = JSON.parse($('[name=\'file_file_path\']').val());
  } else {
    var data = [];
  }

  var newData = [];

  console.log(data, 'current data');

  $.each(data, function(){
      if(this.url != path){
        newData.push(this);
      }
  });

  console.log(newData, 'new data');

  $('[name=\'file_file_path\']').val(JSON.stringify(newData));




  $.get("/call/image-delete?id={$model->id}&name="+path, function(){
    $(self).remove();
  });
};

$('.files [data-delete]').click(deleteEventHandler);
$('.files [data-delete]').on('keydown', keyDownEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style="height: 200px; overflow: auto;">
                    <table role="presentation" class="table table-striped">
                        <tbody class="files">
                        <?php if ($model->files):?>
                        <?php  $counter = 0; foreach (json_decode($model->files, true) as $value):?> <?php if(isset($value['url']) == false) { continue; } ?>
                          <tr class="template-upload fade in">
                                <td>
                                    <span class="preview">
                                      <img src="<?php if(file_exists(substr($value['url'], 1)) !== false){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
                                    </span>
                                </td>
                                <td>
                                    <p class="name"></p><?=$value['name']?></p>
                                    <p class="size"><?=$value['size']?></p>
                                </td>
                                <td>
                                    <?= \yii\helpers\Html::dropDownList('fileType'.$counter, (isset($value['type']) ? $value['type'] : null), [
                                        'Заявка' => 'Заявка',
                                        'Договор' => 'Договор',
                                        'Счёт' => 'Счёт',
                                        'УПД' => 'УПД',
                                        'АКТ' => 'АКТ',
                                        'Закрывающие' => 'Закрывающие',
                                    ], ['class' => 'form-control', 'prompt' => 'Выберите', 'onchange' => '$.get("/call/update-file-attr?id='.$model->id.'&i='.$counter.'&value="+$(this).val());']) ?>
                                </td>
                                <td>
                                <a data-delete="<?=$value['url']?>" class="btn btn-danger cancel" onclick="deleteEventHandler">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Удалить</span>
                                </a>
                                <a class="btn btn-primary" target="_blank" href="<?= \yii\helpers\ArrayHelper::getValue($value, 'preview_url') ?>">
                                    <span>Просмотр</span>
                                </a>
                                <?php

                                    $extension = explode('.', $value['url']);
                                    if(isset($extension[1])){
                                        $extension = $extension[1];
                                    }

                                ?>
                                <a class="btn btn-success" href="<?= \yii\helpers\Url::toRoute(['call/download-resource', 'path' => $value['url']]) ?>" download="<?= $value['name'] ?>">
                                    <span>Скачать</span>
                                </a>
                                    
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach;?>
<?php

$script = <<< JS
    $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
            verticalFit: true
        }
        
    });
JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>    
                        <?php endif;?>
                      </tbody>
                  </table>
              </div>
                            </div>                          
                        </div>

    </div>



  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>


<?php

$script = <<< JS

$(".js-input-plus").hide();

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>


<?php

if(\Yii::$app->user->identity->isSuperAdmin() == false){
$script = <<< JS

$(".js-input-remove").hide();

JS;

$this->registerJs($script, \yii\web\View::POS_READY);
}

?>