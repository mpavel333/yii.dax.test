<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Car */
/* @var $form yii\widgets\ActiveForm */

if($model->isNewRecord == false){
    $model->items = \app\models\CarTo::find()->where(['car_id' => $model->id])->asArray()->all();

    for($i = 0; $i < count($model->items); $i++)
    {
        $model->items[$i]['close'] = isset($model->items[$i]['response_user']) && mb_strlen($model->items[$i]['response_user']) > 0;
    }
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

<script>
    var user_id = '<?= \Yii::$app->user->getId() ?>';
    var user_name = '<?= \Yii::$app->user->identity->name ?>';
</script>

<?php
//print_r(\Yii::$app->user);
?>

<div class="car-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php if(\Yii::$app->user->identity->can('car_to_update')): ?>
        <div class="col-md-4">
        <?php else: ?>
        <div class="col-md-12">    
        <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">

                <?php if(\Yii::$app->user->identity->isSuperAdmin()): ?>

                    <?= $form->field($model, 'user_id')->widget(\kartik\select2\Select2::class, [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => "Выберите",
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>

                <?php else: ?>

                    <div class="form-group field-car-user-name">
                        <label class="control-label" for="car-user_id">Диспетчер</label>
                        <input type="text" id="car-user_id" class="form-control" value="<?php echo \yii\helpers\ArrayHelper::getValue($model, 'user.name'); ?>" maxlength="255" disabled>
                        <span></span> 
                    </div>

                <?php endif; ?>                     
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
                </div>        
                <div class="col-md-6">
                    <?= $form->field($model, 'truck_number')->textInput(['maxlength' => true]) ?>
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
                        'pluginEvents' => [
                            'change' => 'function(){ 
                                $.get("/driver/view-ajax?id="+$(this).val(), 
                                    function(response){ 
                                        console.log(response);
                                        $("#car-truck_number").val(response.car_truck_number); 
                                        $("#car-phone").val(response.phone); 
                                        $("#car-driver").val(response.driver); 
                                    }); 
                                }'
                        ],
                    ]) ?>
                </div>

               

            </div>

            <div class="row">    

                <div class="col-md-6">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>           

                <div class="col-md-6">
                    <?= $form->field($model, 'driver')->textInput(['maxlength' => true]) ?>
                </div> 
            
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'mileage')->textInput() ?>
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
        <?php if(\Yii::$app->user->identity->can('car_to_update')): ?>
        <div class="col-md-8">
            <div class="row" style="overflow-y: auto;">
                <div class="col-md-12">
                    <?= $form->field($model, 'items')->widget(unclead\multipleinput\MultipleInput::class, [
                        'id' => 'items-list',
                        'min' => 1,
                        'max' => 30,
                        'columns' => [
                            [
                                'name' => 'id',
                                'options' => [
                                    'type' => 'hidden'
                                ]
                            ],
                            [
                                'name' => 'response_user_id',
                                'options' => [
                                    'type' => 'hidden'
                                ]
                            ],
                            /*
                            [
                                'name' => 'close_user_id',
                                'options' => [
                                    'type' => 'hidden'
                                ]
                            ],
                            */
                            [
                                'name' => 'name',
                                'title' => 'ТО',
                                'type' => \kartik\select2\Select2::class,
                                'options' => [ 
                                    'options' => [
                                        'placeholder' => \Yii::t('app', 'Выберите'),
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                    'data' => [
                                        'ТО' => 'ТО',
                                        'Осмотр' => 'Осмотр',
                                        'Ремонт' => 'Ремонт',
                                    ],
                                ],
                            ],
                            [
                                'name' => 'date',
                                'title' => 'Дата',
                                'options' => [
                                    'type' => 'date',
                                ], 
                            ],
                            [
                                'name' => 'mileage',
                                'title' => 'Километраж',
                            ],
                            [
                                'name' => 'info',
                                'title' => 'Информация по ремонту',
                                'type' => 'textarea',
                            ],
                            [
                                'name' => 'response_user',
                                'title' => 'Ответственный',
                            ],
                            [
                                'name' => 'driver_id',
                                'title' => 'Водитель',
                                'type' => \kartik\select2\Select2::class,
                                'options' => [ 
                                    'options' => [
                                        'placeholder' => \Yii::t('app', 'Выберите'),
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Driver::find()->all(), 'id', 'data'),
                                ],
                            ],
                            [
                                'name'  => 'close',
                                'title' => 'Закрыл',
                                'type'  => 'checkbox',
                                // 'value' => function($data) {
                                    
                                //     if(isset($data['id']) && !empty($data['close_user_id'])){
                                //         return '<i class="fa fa-check text-success" style="font-size: 16px;"></i>';  
                                //     }else{
                                //         return Html::checkbox( 'Car[items]['.$data['id'].'][close]', 0, []);
                                //     }
                                // },
                            ]

                        ],
                    ])->label(false) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
                        <?php if(isset($value['url']) == false) { continue; } ?>
                          <tr class="template-upload fade in">
                                <td>
                                    <span class="preview">
                                      <img src="<?php if(file_exists(substr($value['url'], 1)) !== false or strpos($value['url'],'base64')){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
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

//var user_id = 444;

function numerateList(){
    $('.multiple-input-list__item').each(function(i, el){
        $(el).find('.list-cell__name input').val("ТО "+(i + 1));
    });
}

$('#items-list').on('afterInit', function(){
}).on('beforeAddRow', function(e, row, currentIndex) {
}).on('afterAddRow', function(e, row, currentIndex) {
    numerateList();
    //console.log(currentIndex); 

    $('#car-items-'+currentIndex+'-response_user_id').val(user_id);
    $('#car-items-'+currentIndex+'-response_user').val(user_name);

    // $(this).parent().parent()

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

$('.list-cell__close input').each(function(){
    if($(this).is(':checked')){
        $(this).attr('disabled', true);
    }
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>