<?php
use app\models\Template;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $form yii\widgets\ActiveForm */

$whiteNameSearch = '';
$listTag = $model->getListTag();
if (isset($_GET['whiteNameSearch'])) {

    if (!empty($_GET['whiteNameSearch'])) {
        $whiteNameSearch = $_GET['whiteNameSearch'];
        $listTag = array_filter($model->getListTag(), function ($value) use ($whiteNameSearch) {
            if (strpos($value,$whiteNameSearch) !== false) {
                return $value;
            }
        });
    }
}
?>

<div class="template-form">
            <div class="col-md-2">
                <div class="panel panel-inverse">

                    <?php \yii\widgets\Pjax::begin(['id' => 'pjax-white-name-container', 'enablePushState' => true]) ?>
                    <?php $form = ActiveForm::begin(['action' => '#', 'id' => 'white-name-form', 'method' => 'GET', 'options' => ['data-pjax' => true]]) ?>
                    <div class="panel-heading">
                            <input type="" class="form-control" value="<?=$whiteNameSearch?>" name="whiteNameSearch" placeholder="Поиск..." onchange="$('#white-name-form').submit();">
                    </div>
                    <div class="panel-body" id="whitename-panel-body" style="overflow: auto; height: 80vh;">
                        <div class="form-group">
                        </div>
                        <?php ActiveForm::end() ?>
                            <?php foreach ($listTag as $tag => $label): ?>
                                    <a href='#' class="list-group-item list-group-item-action " onclick='event.preventDefault(); window.CKEDITOR.instances["template-text"].insertHtml("<?=$tag?>");'><?=$label?></a> 
                            <?php endforeach; ?>
                        <?php \yii\widgets\Pjax::end() ?>
                    </div>
                </div>
            </div>

        <div class="col-md-10">
            <div class="panel panel-inverse">
                <div class="panel-body">

                    <?php $form = ActiveForm::begin(); ?>

                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'type')->dropDownList(Template::typeLabels()) ?>
                        </div>

                        <div class="col-md-12">
                            <?= $form->field($model, 'text')->widget(\mihaildev\ckeditor\CKEditor::class, [
                              ]) ?>
                        </div>


                    <?php if (!Yii::$app->request->isAjax){ ?>
                     <div class="col-md-12">
                	  	<div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                	    </div>
                    </div>
                    <?php } ?>

                    <?php ActiveForm::end(); ?>
    
  
                    </div>
                </div>              
            </div>

    
</div>