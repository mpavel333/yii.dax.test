<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;



/* @var $this yii\web\View */
/* @var $model app\models\Flight */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'display:none;';
}
if($model->isNewRecord == false){
}


$dateCr = new \DateTime($model->date_cr);
$dateCr->modify('+2 days');

// $managerReadonly = \Yii::$app->user->identity->isManager() && $model->is_signature && $model->date_cr <= date('Y-m-d');
$managerReadonly = (\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->can('flight_btn_update')) && $model->is_signature && $dateCr->format('Y-m-d') <= date('Y-m-d');

// $managerReadonlyDateTime = $managerReadonly && $model->date_cr <= date('Y-m-d');
$managerReadonlyDateTime = $managerReadonly && $dateCr->format('Y-m-d') <= date('Y-m-d');
$managerReadonlyDateTimeTop = $managerReadonly && $dateCr->format('Y-m-d') <= date('Y-m-d') && $model->is_signature && $model->is_driver_signature;



if($model->isNewRecord){
	$model->fio = 'Стандарт';
	$model->type_weight = \app\models\Flight::WEIGHT_TYPE_TONS;

	$flightCount = \app\models\Flight::find()->orderBy('id desc')->one();
	if($flightCount){
		$flightCount = $flightCount->index;
	} else {
		$flightCount = 1;
	}
	if(\Yii::$app->user->identity->role){
		$userPks = \yii\helpers\ArrayHelper::getColumn(\app\models\User::find()->where(['role' => \Yii::$app->user->identity->role])->all(), 'id');
		$flightCount = (string) \app\models\Flight::find()->where(['created_by' => $userPks])->andWhere(['>', 'date', date('Y').'-01-01'])->count();
		// $flightCount = \app\models\Flight::find()->orderBy('id desc')->one();
		// if($flightCount){
		// 	$flightCount = $flightCount->index;
		// } else {
		// 	$flightCount = 1;
		// }
		if(mb_strlen($flightCount) == 1){
			$flightCount = '00'.$flightCount;
		} elseif(mb_strlen($flightCount) == 2) {
			$flightCount = '0'.$flightCount;
		}
		$model->order = \Yii::$app->user->identity->role.$flightCount + 1;
		// $model->number = \Yii::$app->user->identity->role.$flightCount + 1;
		// $model->upd = \Yii::$app->user->identity->role.$flightCount + 1;
	}

	if(\Yii::$app->request->isGet){
		$model->date = date('Y-m-d');
	}
	// $model->index = \app\models\Flight::find()->count() + 1;
	$model->index = $flightCount + 1;
}



$group = isset($_GET['group']) ? true : false;


if($model->flights_count == null){
	$model->flights_count = 1;
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
</style>
<div class="flight-form">

<?php $form = ActiveForm::begin(); ?>
                    
<div class="row">
	<div class="col-md-6" <?= \Yii::$app->user->identity->can('flight_upload_file') ? '' : 'style="display: none;"' ?>>
		<div class="row">
			<div class="col-md-5" style=" overflow: auto;">
				<label>Заказчик</label>
						<?= \kato\DropZone::widget([
				'id'        => 'dzImage_file',
				'uploadUrl' => \yii\helpers\Url::toRoute([ '/flight/upload-file']),
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
					var strE = path.url;        
					//var ext = strE.substring(strE.lastIndexOf('.')+1);
					var ext = path.ext;
					if(arr.includes(ext)){
					strE = path.url;
					} else {
					strE = 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';
					}
					var html = '<tr class=\"template-upload fade in\"><td><span class=\"preview\"><img src=\"'+strE+'\" style=\"width: 50px;\"></span></td>';
					html += '<td><p class=\"name\">'+path.name+'</p><p class=\"size\">'+path.size+'</p><p class=\"preview_url\"><a target=\"_blank\" href=\"'+path.preview_url+'\">'+path.preview_url+'</a></p></td>';
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




  $.get("/flight/image-delete?id={$model->id}&name="+path, function(){
    $(self).remove();
  });
};

$('.files [data-delete]').click(deleteEventHandler);
$('.files [data-delete]').on('keydown', keyDownEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style=" overflow: auto;">
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
                                      <img src="<?php if(file_exists(substr($value['url'], 1)) !== false or strpos($value['url'],'base64')){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
                                    </span>
                                </td>
                                <td>
                                    <p class="name"></p><?=$value['name']?></p>
                                    <p class="size"><?=$value['size']?></p>
                                    <p class="preview_url"><a target="_blank" href="<?=$value['preview_url']?>"><?=$value['preview_url']?></a></p>
                                </td>
                                <td>
                                    <?= \yii\helpers\Html::dropDownList('fileType'.$counter, (isset($value['type']) ? $value['type'] : null), [
                                        'Заявка' => 'Заявка',
                                        'Договор' => 'Договор',
                                        'Счёт' => 'Счёт',
                                        'УПД' => 'УПД',
                                        'АКТ' => 'АКТ',
                                        'Закрывающие' => 'Закрывающие',
                                    ], ['class' => 'form-control', 'prompt' => 'Выберите', 'onchange' => '$.get("/flight/update-file-attr?id='.$model->id.'&i='.$counter.'&value="+$(this).val());']) ?>
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
							</div>                    		
                    	</div>
                    	<div class="col-md-6" <?= \Yii::$app->user->identity->can('flight_driver_upload_file') ? '' : 'style="display: none;"' ?>>
                    		<div class="row">
                <div class="col-md-5" style=" overflow: auto;">
                      <label>Перевозчик</label>
            <?= \kato\DropZone::widget([
    'id'        => 'dzImage_file_provider',
    'uploadUrl' => \yii\helpers\Url::toRoute([ '/flight/upload-file']),
    'dropzoneContainer' => 'dzz-container-images',
    'previewsContainer' => 'previeww-images', // <-- уникальные previewsContainer
    'options' => [
    'dictDefaultMessage' => 'Перетащите файлы сюда',
    ],
    'clientEvents' => [
      'complete' => "function(file){
        var path = JSON.parse(file.xhr.response);
        if($('[name=\'file_provider_file_path\']').val()){
          var value = JSON.parse($('[name=\'file_provider_file_path\']').val());
        } else {
          var value = [];
        }        console.log(value, 'current value');
        value.push(path);
        console.log(value, 'new value');        $('[name=\'file_provider_file_path\']').val(JSON.stringify(value));
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
        $('.files-provider').append(html);
        $('.files-provider [data-delete]').unbind('click');
        $('.files-provider [data-delete]').click(deleteEventHandlerProvider);
        $('.files-provider [data-delete]').on('keydown', keyDownEventHandler);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_provider_file_path">
</div>
<?php
$script = <<< JS


var keyDownEventHandler = function(e){
   if(e.keyCode === 13){
       e.preventDefault();
   }
};

var deleteEventHandlerProvider = function(e){
  e.preventDefault();

  var path = $(this).data('delete');

  var self = $(this).parent().parent();

  if($('[name=\'file_provider_file_path\']').val()){
    var data = JSON.parse($('[name=\'file_provider_file_path\']').val());
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

  $('[name=\'file_provider_file_path\']').val(JSON.stringify(newData));




  $.get("/flight/image-delete-provider?id={$model->id}&name="+path, function(){
    $(self).remove();
  });
};

$('.files-provider [data-delete]').click(deleteEventHandlerProvider);
$('.files-provider [data-delete]').on('keydown', keyDownEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style=" overflow: auto;">
                    <table role="presentation" class="table table-striped">
                        <tbody class="files-provider">
                        <?php if ($model->file_provider):
                        $counter = 0; 
                        $files=json_decode($model->file_provider, true);
                        if($files):
                        foreach ($files as $value):?>
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
                                    <?= \yii\helpers\Html::dropDownList('fileType'.$counter, (isset($value['type']) ? $value['type'] : null), [
                                        'Заявка' => 'Заявка',
                                        'Договор' => 'Договор',
                                        'Счёт' => 'Счёт',
                                        'УПД' => 'УПД',
                                        'АКТ' => 'АКТ',
                                        'Закрывающие' => 'Закрывающие',
                                    ], ['class' => 'form-control', 'prompt' => 'Выберите', 'onchange' => '$.get("/flight/update-file-provider-attr?id='.$model->id.'&i='.$counter.'&value="+$(this).val());']) ?>
                                </td>
                                <td>
                                <a data-delete="<?=$value['url']?>" class="btn btn-danger cancel" onclick="deleteEventHandlerProvider">
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
                    	</div>
                    </div>
            

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

<div style="display: none;">
	<div id="map" style="width: 500px; height: 500px;">
	
	</div>
	<div id="viewContainer"></div>
</div>

<?php

$script = <<< JS

$("#flight-fio").change(function(){
    var value = $(this).val();


    if(value == 'Стандарт'){

    }
    if(value == 'Срыв погрузки'){

    }
    if(value == 'Предоплата'){

    }
    if(value == 'Ваш текст'){
        
    }
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);


$script = <<< JS

ymaps.modules.define('MultiRouteCustomView', [
    'util.defineClass'
], function (provide, defineClass) {
    // Класс простого текстового отображения модели мультимаршрута.
    function CustomView (multiRouteModel) {
        this.multiRouteModel = multiRouteModel;
        // Объявляем начальное состояние.
        this.state = "init";
        this.stateChangeEvent = null;
        // Элемент, в который будет выводиться текст.
        this.outputElement = $('<div></div>').appendTo('#viewContainer');

        this.rebuildOutput();

        // Подписываемся на события модели, чтобы
        // обновлять текстовое описание мультимаршрута.
        multiRouteModel.events
            .add(["requestsuccess", "requestfail", "requestsend"], this.onModelStateChange, this);
    }

    // Таблица соответствия идентификатора состояния имени его обработчика.
    CustomView.stateProcessors = {
        init: "processInit",
        requestsend: "processRequestSend",
        requestsuccess: "processSuccessRequest",
        requestfail: "processFailRequest"
    };

    // Таблица соответствия типа маршрута имени его обработчика.
    CustomView.routeProcessors = {
        "driving": "processDrivingRoute",
        "masstransit": "processMasstransitRoute",
        "pedestrian": "processPedestrianRoute"
    };

    defineClass(CustomView, {
        // Обработчик событий модели.
        onModelStateChange: function (e) {
            // Запоминаем состояние модели и перестраиваем текстовое описание.
            this.state = e.get("type");
            this.stateChangeEvent = e;
            this.rebuildOutput();
        },

        rebuildOutput: function () {
            // Берем из таблицы обработчик для текущего состояния и исполняем его.
            var processorName = CustomView.stateProcessors[this.state];
            this.outputElement.html(
                this[processorName](this.multiRouteModel, this.stateChangeEvent)
            );
        },

        processInit: function () {
            return "Инициализация ...";
        },

        processRequestSend: function () {
            return "Запрос данных ...";
        },

        processSuccessRequest: function (multiRouteModel, e) {
            var routes = multiRouteModel.getRoutes(),
                result = ["Данные успешно получены."];
            if (routes.length) {
                result.push("Всего маршрутов: " + routes.length + ".");
                // for (var i = 0, l = routes.length; i < l; i++) {
                    result.push(this.processRoute(0, routes[0]));
                // }
            } else {
                result.push("Нет маршрутов.");
            }
            return result.join("<br/>");
        },

        processFailRequest: function (multiRouteModel, e) {
            return e.get("error").message;
        },

        processRoute: function (index, route) {
            // Берем из таблицы обработчик для данного типа маршрута и применяем его.
            var processorName = CustomView.routeProcessors[route.properties.get("type")];
            return (index + 1) + ". " + this[processorName](route);
        },

        processDrivingRoute: function (route) {
            var result = ["Автомобильный маршрут."];
            result.push(this.createCommonRouteOutput(route));
            return result.join("<br/>");
        },

        processMasstransitRoute: function (route) {
            var result = ["Маршрут на общественном транспорте."];
            result.push(this.createCommonRouteOutput(route));
            result.push("Описание маршута: <ul>" + this.createMasstransitRouteOutput(route) + "</ul>");
            return result.join("<br/>");
        },

        processPedestrianRoute: function (route) {
            var result = ["Пешеходный маршрут."];
            result.push(this.createCommonRouteOutput(route));
            return result.join("<br/>");
        },

        // Метод, формирующий общую часть описания для всех типов маршрутов.
        createCommonRouteOutput: function (route) {
        	var distanceValue = route.properties.get("distance").text;

        	distanceValue = distanceValue.replace(' км', '');
        	distanceValue = distanceValue.replace(',', '.');
        	distanceValue = distanceValue.replace(/\s/, '');

        	$('#flight-distance').val(distanceValue);
            return "Протяженность маршрута: " + route.properties.get("distance").text + "<br/>" +
                "Время в пути: " + route.properties.get("duration").text;
        },

        // Метод, строящий список текстовых описаний для
        // всех сегментов маршрута на общественном транспорте.
        createMasstransitRouteOutput: function (route) {
            var result = [];
            for (var i = 0, l = route.getPaths().length; i < l; i++) {
                var path = route.getPaths()[i];
                for (var j = 0, k = path.getSegments().length; j < k; j++) {
                    result.push("<li>" + path.getSegments()[j].properties.get("text") + "</li>");
                }
            }
            return result.join("");
        },

        destroy: function () {
            this.outputElement.remove();
            this.multiRouteModel.events
                .remove(["requestsuccess", "requestfail", "requestsend"], this.onModelStateChange, this);
        }
    });

    provide(CustomView);
});


function init () {
    // Создаем модель мультимаршрута.
    var multiRouteModel = new ymaps.multiRouter.MultiRouteModel(window.routes, {
        });



    ymaps.modules.require([
        'MultiRouteCustomView'
    ], function (MultiRouteCustomView) {
        // Создаем экземпляр текстового отображения модели мультимаршрута.
        // см. файл custom_view.js
        new MultiRouteCustomView(multiRouteModel);
    });

    // Создаем карту с добавленной на нее кнопкой.
    var myMap = new ymaps.Map('map', {
            center: [55.750625, 37.626],
            zoom: 7,
            controls: []
        }, {
            buttonMaxWidth: 300
        }),

        // Создаем на основе существующей модели мультимаршрут.
        multiRoute = new ymaps.multiRouter.MultiRoute(multiRouteModel, {
        });

    // Добавляем мультимаршрут на карту.
    myMap.geoObjects.add(multiRoute);
}

// ymaps.ready(init);

$("#flight-rout").change(function(){
	var value = $(this).val();

	value = value.split(/\s[-,—]\s/);

	console.log(value, 'cities');

	if(value[0] && value[1]){
		// window.mapFrom = value[0].trim();
		// window.mapTo = value[1].trim();

		window.routes = value;


		$("#map").html(null);
		$("#viewContainer").html(null);

		ymaps.ready(init);
	}

});


JS;

$this->registerJs($script, \yii\web\View::POS_READY);


?>

<?php

$script = <<< JS

$(document).ready(function(){
	$('#flight-pay_us, #flight-otherwise2').change(function(){
		var value = $(this).val();

		if(value == 'Наличными' || value == 'на карту'){
			$('#flight-number, #flight-upd').val('Нал');
		}
	});

	$('#flight-date_out4').change(function(){
		var value = $(this).val();
		var value2 = $('#flight-date_out4_2').val();

		console.log('here');
		console.log(value2);

		if(value2 == ''){
			$('#flight-date_cr').val(value);
		}
	});

	
	$('#flight-date_out4_2').change(function(){
		var value = $(this).val();
		$('#flight-date_cr').val(value);
	});
});

$('#flight-organization_id').change(function(){
	var value = $(this).val();

	// $.get('/requisite/get-bank?id='+value, function(response){
	// 	if(response.id){
	// 		$('#flight-payment_bank_id').val(response.id);
	// 		$('#flight-payment_bank_id').trigger("change");
	// 	}
	// });

	$.get("/requisite/bank-map?id="+value, function(response){
		$("#flight-bank").html(response.data);
		$("#flight-bank").trigger("change");
	});

	var organizationName = $("#flight-organization_id option[value="+value+"]").text();

	if($('#flight-is_insurance').is(':checked')){
		$('#flight-ensurance_organization').val(organizationName);

		$.get("/requisite/ensurance-map?id="+value, function(response){
			$('#flight-ensurance_contract_where').html(response.data);
			// $('#flight-ensurance_contract').html(response.data3);
			$('#flight-ensurance_condition').html(response.data2);
		});
	}

});

$("#flight-ensurance_contract_where").change(function(){
	var id = $(this).val();

	$.get("/requisite/get-ensurance-by-name?id="+id, function(response){ 
	    if(response.model){
	        $("#flight-ensurance_contract").val(response.model.contract);
	        // $("#flight-ensurance_condition").val(response.model.condition);
	        // $("#flight-ensurance_percent").val(response.model.percent).trigger("change");
	    }
	    if(response.conditions){
	    	var options = '<option value="">Выберите</option>';
	    	$.each(response.conditions, function(key, value){
	    		options = options + '<option value="'+value+'">'+key+'</option>';
	    	});
	    	$("#flight-ensurance_condition").html(options);
	    }
	 }); 
});

$("#flight-ensurance_condition").change(function(){
	var value = $(this).val();
	$("#flight-ensurance_percent").val(value);
});


$("#flight-ensurance_condition").change(function(){
	var id = $(this).val();

	$.get("requisite/get-ensurance-by-id?id="+id, function(response){ 
	    if(response.model){
	        // $("#flight-ensurance_contract").val(response.model.contract);
	        // $("#flight-ensurance_condition").val(response.model.condition);
	        $("#flight-ensurance_percent").val(response.model.percent).trigger("change");
	    }
	 }); 
});

$("#flight-fio").change(function(){
	var value = $(this).val();

	if(value == 'Срыв погрузки'){
		$('#flight-pay_us').val('без НДС');
		$('#flight-otherwise2').val('без НДС');
	}
});


$("#flight-ensurance_percent, #flight-name_price").change(function(){
	var percent = $('#flight-ensurance_percent').val();
	var sum = $('#flight-name_price').val();
	var sum2 = sum * percent / 100;
	console.log(sum2, 'sum2');
	$("#flight-ensurance_prime").val(sum2);

	$('#namePrice').val(sum);
});

$('#flight-date, #flight-order').change(function(){
	var order = $('#flight-order').val();
	var date = $('#flight-date').val().split('-').reverse().join('.');

	var str = "Новый к заявке №"+order+" от "+date+" г.";

	$("#flight-ensurance_additional").val(str);
});




$("#flight-is_insurance").change(function(){
	var value = $(this).is(":checked");

	if(value){
		$("#panel-ensurance .panel-body").slideDown();
		$('#flight-organization_id').trigger("change");
		$("#flight-order").trigger("change");
	} else {
		$("#panel-ensurance .panel-body").slideUp();
	}
});





$("#flight-we, #flight-payment_out, #flight-recoil, #flight-additional_credit").change(function(){
	var we = $('#flight-we').val();
	var payment_out = $('#flight-payment_out').val();
	var recoil = $('#flight-recoil').val();
	var additional_credit = $('#flight-additional_credit').val();
});


$("#flight-we, #flight-payment_out, #flight-recoil, #flight-daks_balance, #flight-delta, #flight-delta_percent").change(function(){
	$.ajax({
		url: "/flight/calculate-salary",
		method: "POST",
		data: $(".modal-body form").serialize(),
		success: function(response){
			$("#flight-salary").val(reponse.salary);
		},
	});
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>