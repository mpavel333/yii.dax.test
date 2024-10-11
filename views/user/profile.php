<?php

use app\models\User;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/**
 * @var $model User
 * @var $answerDataProvider \yii\data\ActiveDataProvider
 * @var $answerSearchModel \app\models\CandidateAnswerSearch
 * @var $candidate \app\models\Candidate
 */

CrudAsset::register($this);

$this->title = 'Профиль пользователя';
/** @var User $identity */
$identity = Yii::$app->user->identity;

function equalsUsers($model)
{
    return $model->id == Yii::$app->user->identity->id;
}

?>
    <div class="row">
        <div class="col-md-12">
            <?php //= $candidate ? $candidate->getTopButton() : ''; ?>
        </div>
    </div>
    <div class="panel panel-inverse">
        <div class="panel-heading user-profile">
            <h4 class="panel-title">Профиль пользователя</h4>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin() ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => '+1(999)999-99-99'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'login')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'value'=>""]) ?>
                        </div>
                    </div>

                    <?php /* if ($model->candidate_id == null): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'email')->textInput() ?>
                            </div>
                        </div>
                    <?php endif; 

                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $items = [];
                            for ($i = -12; $i < 13; $i++) {
                                $items[$i] = $i >= 0 ? '(UTC +' . $i . ':00)' : '(UTC ' . $i . ':00)';
                            }
                            echo $form->field($model, 'timezone')->dropDownList($items, [
                                'prompt' => 'Select value...'
                            ]) ?>
                        </div>

                        
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => '+1(999)999-99-99'
                            ]) ?>
                        </div>

                        <div class="col-md-6">
                            <?php //= $form->field($model, 'new_password')->passwordInput(['maxlength' => true,'value'=>""]) ?>
                        </div>

                    </div>
                    */ ?>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <?= \yii\helpers\Html::submitButton('<i class="fa fa-check"></i> Применить',
                                ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <img class="circle-img"
                                 style="height: 250px; width: 250px; border-radius: 100%; object-fit: contain; border: 2px solid #cecece; cursor: pointer;"
                                 src="/<?= $model->getRealAvatarPath() ?>" data-role="profile-image-select">
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>

<?php /* if ($model->role == User::ROLE_CANDIDATE): ?>

    <?php if ($model->candidate->status == \app\models\Candidate::STATUS_CONTRACT): ?>
        <?php
//        echo $this->render('_contract', [
//            'candidate' => $model->candidate,
//        ])
        ?>
    <?php endif; ?>
    <?php if (equalsUsers($model)): ?>
        <?php
        //echo $this->render('_profile_edit_photos', [
        //                'candidate' => $model->candidate,
        //])
        ?>
    <?php else: ?>
        <?= $this->render('_profile_view_photos', [
            'candidate' => $model->candidate,
        ]) ?>
    <?php endif; ?>

<?php endif; ?>
<?php if ($answerDataProvider ?? null): ?>

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Application form</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <?= $this->render('@app/views/candidate-answer/index', [
                    'searchModel' => $answerSearchModel,
                    'dataProvider' => $answerDataProvider
                ]) ?>
            </table>
        </div>
    </div>
<?php endif;  */?>


<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


<?php

$script = <<<JS
    $('[data-role="profile-image-select"]').click(function(){
        $('#avatar-form input').trigger('click');
    });

    $('#avatar-form input').change(function(){
        $('#avatar-form').submit();
    });
    
    $('#avatar-form').submit(function(e){
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: $('#avatar-form').attr('action'),
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response){
                if(response.success === 1){
                    var path = '/'+response.path;
                    $('[data-role="avatar-view"]').each(function(i){
                        $(this).attr('src', path);
                    });
                    $('[data-role="profile-image-select"]').each(function(i){
                        $(this).attr('src', path);
                    });
                }
            }
        });
        e.preventDefault();
    });
JS;

$this->registerJs($script, \yii\web\View::POS_READY);


?>