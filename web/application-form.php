<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Request-Method: *');
header('Access-Control-Allow-Credentials: *');

$domain = 'https://bee.teo-crm.com';

?>
<html lang="en"><!--<![endif]--><head>
    <meta charset="utf-8">
    <title>Application Form</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="<?=$domain?>/theme/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet">
    <link href="<?=$domain?>/theme/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--    <link href="<?=$domain?>/theme/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" />-->
    <link href="<?=$domain?>/theme/assets/css/animate.min.css" rel="stylesheet">
    <link href="<?=$domain?>/theme/assets/css/style.min.css" rel="stylesheet">
    <link href="<?=$domain?>/theme/assets/css/style-responsive.min.css" rel="stylesheet">
    <link href="<?=$domain?>/css/site.css" rel="stylesheet">

    <!--[CDATA[YII-BLOCK-HEAD]]-->
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN BASE JS ================== -->
    <!--    <script src="<?=$domain?>/theme/assets/plugins/pace/pace.min.js"></script>-->
    <!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top">
<!--[CDATA[YII-BLOCK-BODY-BEGIN]]--><!-- begin #page-loader -->
<div id="page-loader" class="fade in hide"><span class="spinner"></span></div>
<!-- end #page-loader -->

<!-- begin #page-container -->
<div id="page-container" class="fade in">
    <!-- begin login -->

    <style>
        #page-container {
            margin: 2% 15%;
        }
    </style>


    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Application form</h4>
        </div>
        <div class="panel-body">


            <div class="step-form" data-step-current="1" data-step-max="8">
                <p>Загрузка ...</p>
            </div>




        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=$domain?>/libs/jquery.maskedinput.min.js"></script>

    <script>
        // window.onbeforeunload = function(){
        //     return 'Are you sure you want to leave?';
        // };


        $(document).ready(function(){
            $.ajax({
                url: '<?=$domain?>/question/log',
                method: 'POST',
                data: {
                    'action': 'on',
                    '_csrf': '4WxUSiPnildo9kwu6w3oOY1FKcZ_bOdngRuaATtL_-u1GmEecI3gPxLDAG3ZarlcyHFvvBcJkA7taepGcBPKmw=='
                },
                done: function(response){
                    console.log(response, 'log ON response');
                }
            });
        });

        $.get('<?=$domain?>/question/application-form-ajax', function(response){
            $('.step-form').html(response);
            setTimeout(function(){ formInit(); }, 500);
        });

        function formInit()
        {
            $('[data-role="phone"]').mask('+1(999)999-99-99');
            $('[data-role="date"]').mask('9999-99-99');
            $('.step-form').each(function(index){
                var currentStep = $(this).data('step-current');
                var maxSteps = $(this).data('step-max');
                var nextBtnText = '';
                var defaultTitleName = $(this).find('[data-step-count="1"] h3').html();

                $(this).find('[data-step-control="prev"]').click(function(e){
                    e.preventDefault();
                    if(currentStep > 1){
                        $(this).parent().parent().find('[data-step-label="'+currentStep+'"]').attr('class', '');
                        $(this).parent().parent().find('[data-step-count="'+currentStep+'"]').hide();
                        currentStep = currentStep - 1;
                        $(this).parent().parent().find('[data-step-label="'+currentStep+'"]').attr('class', 'active');
                        $(this).parent().parent().find('[data-step-count="'+currentStep+'"]').show();
                        if(currentStep == 1){
                            $(this).hide();
                        }
                        if(nextBtnText != ''){
                            $(this).parent().find('[data-step-control="next"]').html(nextBtnText);
                        }
                    }
                });

                $(this).find('[data-step-control="next"]').click(function(e){
                    e.preventDefault();
                    if(currentStep < maxSteps){

                        var isValid = true;

                        $('[data-step-count="'+currentStep+'"] input[required="required"], [data-step-count="'+currentStep+'"] select[required="required"]').each(function(){
                            if($(this).val() == ''){
                                isValid = false;
                            }
                        });

                        if(isValid){
                            $(this).parent().parent().find('[data-step-label="'+currentStep+'"]').attr('class', 'done');
                            $(this).parent().parent().find('[data-step-count="'+currentStep+'"]').hide();
                            currentStep = currentStep + 1;
                            $(this).parent().parent().find('[data-step-label="'+currentStep+'"]').attr('class', 'active');
                            $(this).parent().parent().find('[data-step-count="'+currentStep+'"]').show();
                            if(currentStep > 1){
                                $(this).parent().find('[data-step-control="prev"]').show();
                                var titleName = $(this).parent().parent().find('[data-role="steps-next-header-title"]').val();
                                $(this).parent().parent().find('[data-step-count="'+currentStep+'"] h3').html(titleName);
                            }
                            if(currentStep == maxSteps){
                                var nextBtn = $(this).parent().find('[data-step-control="next"]');
                                nextBtnText = nextBtn.html();
                                nextBtn.html(nextBtn.data('step-last-text'));
                            }

                            $.ajax({
                                url: '<?=$domain?>/question/log',
                                method: 'POST',
                                data: {
                                    'action': 'next',
                                    '_csrf': '4WxUSiPnildo9kwu6w3oOY1FKcZ_bOdngRuaATtL_-u1GmEecI3gPxLDAG3ZarlcyHFvvBcJkA7taepGcBPKmw=='
                                },
                                done: function(response){
                                    alert('Successfully sent');
                                    document.location.reload(true);
                                    // console.log(response, 'log NEXT response');
                                }
                            });
                        } else {
                            alert('Please enter required fields');
                        }

                    } else {
                        // $(this).parent().parent().find('[data-role="steps-form"]').submit();
                        // var form = $(this).parent().parent().find('[data-role="steps-form"]');
                        var form = document.getElementById('w0');
                        var formData = new FormData(form);

                        console.log(formData);

                        $.ajax({
                            method: 'POST',
                            url: "<?=$domain?>/question/application-form",
                            data: formData,
                            success: function(response){
                                alert('Successfully sent');
                                document.location.reload(true);
                            },
                            crossDomain: true,
                            cache: false,
                            contentType: false,
                            processData: false,
                        });
                    }
                });
            });

            $('[data-role="phone"]').click(function () {
                var phone = $(this).val();
                var input = $(this)[0];
                console.log('Phone: ' + phone);
                input.setSelectionRange(3,3);
            });
        }



    </script>

    <!-- end login -->

</div>
<!-- end page container -->

<!-- ================== BEGIN BASE JS ================== -->
<script src="<?=$domain?>/theme/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="<?=$domain?>/theme/assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="<?=$domain?>/theme/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="<?=$domain?>/theme/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="<?=$domain?>/theme/assets/plugins/crossbrowserjs/html5shiv.js"></script>
<script src="<?=$domain?>/theme/assets/plugins/crossbrowserjs/respond.min.js"></script>
<script src="<?=$domain?>/theme/assets/plugins/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<!--<script src="<?=$domain?>/theme/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>-->
<script src="<?=$domain?>/theme/assets/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?=$domain?>/theme/assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->


<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<div>
</div>

</body></html>