<?php

use \yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>

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
                                              <?php  $counter = 0; $files=json_decode($model->file, true);
                                              if($files):
                                              foreach ($files as $value):?>
                                                <?php if(isset($value['url']) == false) { continue; } ?>
                                                <tr class="template-upload fade in">
                                                      <td>
                                                          <span class="preview">
                                                            <img src="<?php if(isset($value['url']) && file_exists($value['url']) !== false or strpos($value['url'],'base64')){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
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
                                              <?php endif; ?>
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

<?php ActiveForm::end() ?>