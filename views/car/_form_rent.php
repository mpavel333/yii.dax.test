<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Car */
/* @var $form yii\widgets\ActiveForm */

if($model->isNewRecord == false){
    $model->items = \app\models\CarTo::find()->where(['car_id' => $model->id])->asArray()->all();
}

?>
<style type="text/css">
    thead tr th.list-cell__info {
        white-space: nowrap;
    }

    thead tr th.list-cell__name {
        width: 85px;
    }
</style>
<div class="car-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'client_id')->widget(\kartik\select2\Select2::class, [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Client::find()->where(['type' => \app\models\Client::TYPE_DRIVER])->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => \Yii::t('app', 'Выберите'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
                </div>        
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(\app\models\Car::statusLabels(), ['prompt' => 'Выберите']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'driver_id')->widget(\kartik\select2\Select2::class, [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Driver::find()->all(), 'id', 'data'),
                        'options' => [
                            'placeholder' => \Yii::t('app', 'Выберите'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'license_number')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'license_date_start')->input('date') ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'license_date_end')->input('date') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'osago_number')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'osago_date_start')->input('date') ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'osago_date_end')->input('date') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'kasko_number')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'kasko_date_start')->input('date') ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'kasko_date_end')->input('date') ?>
                </div>
            </div>
        </div>
        
                        <div class="col-md-12">
                            <div class="row">
                <div class="col-md-5" style="height: 200px; overflow: auto;">
                      <label>Файлы</label>
            <?= \kato\DropZone::widget([
    'id'        => 'dzImage_file',
    'uploadUrl' => \yii\helpers\Url::toRoute([ '/car/upload-file']),
    'dropzoneContainer' => 'dz-container-images',
    'previewsContainer' => 'preview-images', // <-- уникальные previewsContainer,
    'options' => [
    'dictDefaultMessage' => 'Перетащите файлы сюда',
    ],
    'clientEvents' => [
      'complete' => "function(file){
        var path = JSON.parse(file.xhr.response);
        if($('[name=\'file_files_path\']').val()){
          var value = JSON.parse($('[name=\'file_files_path\']').val());
        } else {
          var value = [];
        }        console.log(value, 'current value');
        value.push(path);
        console.log(value, 'new value');        $('[name=\'file_files_path\']').val(JSON.stringify(value));
        var arr = [ 'jpeg', 'jpg', 'gif', 'png' ];
        var strE = path.url;        var ext = strE.substring(strE.lastIndexOf('.')+1);
        if(arr.includes(ext)){
          strE = path.url;
        } else {
          strE = 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';
        }
        var html = '<tr class=\"template-upload fade in\"><td><span class=\"preview\"><img src=\"'+strE+'\" style=\"width: 50px;\"></span></td>';
        html += '<td><p class=\"name\">'+path.name+'</p><p class=\"size\">'+path.size+'</p></td>';
        html += '<td><button data-delete=\"'+path.url+'\" class=\"btn btn-danger cancel\"><i class=\"glyphicon glyphicon-ban-circle\"></i><span>Удалить</span></button></td></tr>';
        $('.files').append(html);
        $('.files [data-delete]').unbind('click');
        $('.files [data-delete]').click(deleteEventHandler);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_files_path">
</div>
<?php
$script = <<< JS

var deleteEventHandler = function(e){
  e.preventDefault();

  var path = $(this).data('delete');

  var self = $(this).parent().parent();

  if($('[name=\'file_files_path\']').val()){
    var data = JSON.parse($('[name=\'file_files_path\']').val());
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

  $('[name=\'file_files_path\']').val(JSON.stringify(newData));




  $.get("/car/image-delete?id={$model->id}&name="+path, function(){
    $(self).remove();
  });
};

$('.files [data-delete]').click(deleteEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style="height: 200px; overflow: auto;">
                    <table role="presentation" class="table table-striped">
                        <tbody class="files">
                        <?php if ($model->files):?>
                        <?php  $counter = 0; foreach (json_decode($model->files, true) as $value):?>
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
                                <button data-delete="<?=$value['url']?>" class="btn btn-danger cancel" onclick="deleteEventHandler">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Удалить</span>
                                </button>
                                <a class="btn btn-primary" target="_blank" href="<?= \yii\helpers\ArrayHelper::getValue($value, 'preview_url') ?>">
                                    <span>Просмотр</span>
                                </a>
                                <?php

                                    $extension = explode('.', $value['url']);
                                    if(isset($extension[1])){
                                        $extension = $extension[1];
                                    }

                                ?>
                                <a class="btn btn-success" href="<?= \yii\helpers\Url::toRoute(['car/download-resource', 'path' => $value['url']]) ?>" download="<?= $value['name'] ?>">
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


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>


<?php

$script = <<< JS

function numerateList(){
    $('.multiple-input-list__item').each(function(i, el){
        $(el).find('.list-cell__name input').val("ТО "+(i + 1));
    });
}

$('#items-list').on('afterInit', function(){
}).on('beforeAddRow', function(e, row, currentIndex) {
}).on('afterAddRow', function(e, row, currentIndex) {
    numerateList(); 
}).on('beforeDeleteRow', function(e, row, currentIndex){
}).on('afterDeleteRow', function(e, row, currentIndex){
}).on('afterDropRow', function(e, item){       
});

numerateList();

$("#car-number").change(function(){
    var value = $(this).val();

    $.get("/flight/find-by-number?number="+value, function(response){
        if(response.distance){
            var distance = parseFloat($('#car-mileage').val());
            distance = distance + response.distance;
            $('#car-mileage').val(distance);
        }
    });
});

$(document).ready(function(){
    var distance = parseFloat($('#car-mileage').val());

    $('.multiple-input-list tr.multiple-input-list__item').each(function(){
        var value = parseFloat($(this).find('.list-cell__mileage input').val());
    
        if(value <= distance){
            $(this).addClass('danger');
        }
    });
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>