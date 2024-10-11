<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'display:none;';
}
if($model->isNewRecord == false){
}
?>
<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
          <div style="display: none;">
            <input id="idHiddenInput" type="hidden" name="id" value="<?= $model->id ?>">
          </div>

          <div class="col-md-12">
              <div class="row">

                <?php if(\Yii::$app->user->identity->isSuperAdmin()): ?>

                  <?= $form->field($model, 'user_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
                      'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'),
                      'options' => ['prompt' => 'Выбрать', 'multiple' => false],
                      'pluginOptions' => [
                          'allowClear' => true,
                          'tags' => false,
                          'tokenSeparators' => [','],
                      ]
                ]) ?>  
                
                <?php else: ?>

                  <div class="col-md-12">
                    <div class="form-group field-client-name">
                      <label class="control-label" for="client-user_id">Создатель</label>
                      <span><?php echo \yii\helpers\ArrayHelper::getValue($model, 'user.name'); ?></span> 
                    </div>
                  </div>

                <?php endif; ?>                

              </div>
            </div>

            <div class="col-md-4">
              <div class="row">
                <?= $form->field($model, 'name', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                <?= $form->field($model, 'official_address', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                <?= $form->field($model, 'mailing_address', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                <?= $form->field($model, 'inn', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                <?= $form->field($model, 'kpp', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                    
                <?= $form->field($model, 'ogrn', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                <?= $form->field($model, 'doljnost_rukovoditelya', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                <?= $form->field($model, 'fio_polnostyu', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

             <?= $form->field($model, 'name_case', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>


              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
             <?= $form->field($model, 'bank_name', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                  

             <?= $form->field($model, 'kr', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>


             <?= $form->field($model, 'nomer_rascheta', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>


             <?= $form->field($model, 'bic', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>


              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
             <?= $form->field($model, 'email', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
             <?= $form->field($model, 'contact', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                  
             <?= $form->field($model, 'code', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                    
             <?= $form->field($model, 'tel', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                    

                  
             <?= $form->field($model, 'doc', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()  ?>
                    
             <?php 
                //\Yii::$app->cache->flush();
                if(\Yii::$app->user->identity->can('client_contract')): ?>
                    <?= $form->field($model, 'contract',['cols' => 6, 'colsOptionsStr' => " "])->checkbox()  ?>
                <?php else: ?>
                  <?= $form->field($model, 'contract',['cols' => 6, 'colsOptionsStr' => " "])->checkbox(['disabled' => true])  ?>
                <?php endif; ?>                      
             

             <?= $form->field($model, 'nds',['cols' => 12, 'colsOptionsStr' => " "])->checkbox()  ?>



              </div>
            </div>
             

                    
                    
                    
                    
                    <div class="col-md-12"></div>
                    


    
                <div class="col-md-5" style="height: 200px; overflow: auto;">
                      
            <?= \kato\DropZone::widget([
    'id'        => 'dzImage_file',
    'uploadUrl' => \yii\helpers\Url::toRoute([ '/client/upload-file']),
    'dropzoneContainer' => 'dz-container-images',
    'previewsContainer' => 'preview-images', // <-- уникальные previewsContainer
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
        html += '<td><button data-delete=\"'+path.url+'\" class=\"btn btn-danger cancel\"><i class=\"glyphicon glyphicon-ban-circle\"></i><span>Удалить</span></button></td></tr>';
        $('.files').append(html);
        $('[data-delete]').unbind('click');
        $('[data-delete]').click(deleteEventHandler);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_file_path">
</div>
<?php
$script = <<< JS

var deleteEventHandler = function(e){
  e.preventDefault();

  var path = $(this).data('delete');
  
  //if(path.indexOf('base64')){
  //  path = 'image';
  //}

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




  $.get("/client/image-delete?id={$model->id}&name="+path, function(){
    $(self).remove();
  });
};

$('[data-delete]').click(deleteEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style="height: 200px; overflow: auto;">
                    <table role="presentation" class="table table-striped">
                        <tbody class="files">
                        <?php if ($model->file):?>
                        <?php  $counter = 0; foreach (json_decode($model->file, true) as $value):?>
                          <tr class="template-upload fade in">
                                <td>
                                    <span class="preview">
                                      <img src="<?php if(file_exists($value['url']) !== false or strpos($value['url'],'base64')){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
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
                                    ], ['class' => 'form-control', 'prompt' => 'Выберите', 'onchange' => '$.get("/client/update-file-attr?id='.$model->id.'&i='.$counter.'&value="+$(this).val());']) ?>
                                </td>
                                <td>
                                <button data-delete="<?=$value['url']?>" class="btn btn-danger cancel">
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
                                <a class="btn btn-success" href="<?= \yii\helpers\Url::toRoute(['flight/download-resource', 'path' => $value['url']]) ?>" download="<?= $value['name'] ?>">
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


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<!-- <script src="/libs/jquery.maskedinput.min.js"></script> -->

<!-- <script>$("#client-phone").mask("+7 (999) 999-9999");</script>-->

<?php

$script = <<< JS

$("#client-inn").change(function(){
  var value = $(this).val();

  var self = this;

  $.get("/client/view-ajax?inn="+value, function(response){

    if(response.model != null){
      $('#idHiddenInput').val(response.model.id);
      $('#client-name').val(response.model.name);
      $('#client-doljnost_rukovoditelya').val(response.model.doljnost_rukovoditelya);
      $('#client-fio_polnostyu').val(response.model.fio_polnostyu);
      $('#client-official_address').val(response.model.official_address);
      $('#client-bank_name').val(response.model.bank_name);
      $('#client-kpp').val(response.model.kpp);
      $('#client-ogrn').val(response.model.ogrn);
      $('#client-bic').val(response.model.bic);
      $('#client-kpp').val(response.model.kpp);
      $('#client-nomer_rascheta').val(response.model.nomer_rascheta);
      $('#client-tel').val(response.model.tel);
      $('#client-email').val(response.model.email);
      $('#client-doc').val(response.model.doc);
      $('#client-mailing_address').val(response.model.mailing_address);
      $('#client-code').val(response.model.code);
      $('#client-name_case').val(response.model.name_case);
    } else {
      $('#idHiddenInput').val(null);
    }

  });
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>