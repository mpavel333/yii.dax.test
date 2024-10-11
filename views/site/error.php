<?php 
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

?>
<div class="site-error">

    <!-- begin error -->
    <div class="error">
        <div class="error-code m-b-10"><?=$exception->statusCode?> <i class="fa fa-warning"></i></div>
        <div class="error-content">
            <div class="error-message"><?=$message?></div>
            <div class="error-desc m-b-20">
                if it is a server error. <br>
                Contact tech support.
            </div>
            <div>
                <a href="/" class="btn btn-success">To Dashboard</a>
            </div>
        </div>
    </div>
    <!-- end error -->

</div>
