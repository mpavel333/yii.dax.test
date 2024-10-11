<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

return [

    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'app\widgets\SerialColumnReverse',
        'contentOptions' => function($model){

            if($model->is_register){
                return ['class' => 'success'];
            }
        },
        'width' => '30px',
    ],
    // [
    //     'attribute' => 'index',
    //     'label' => '#',
    //     'contentOptions' => function($model){

    //         if($model->is_register){
    //             return ['class' => 'success'];
    //         }
    //     },
    //     'hAlign' => \kartik\grid\GridView::ALIGN_CENTER,
    //     'width' => '30px',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to(["flight"."/".$action,'id'=>$key]);
        },
        // 'template' => '{copy} {export3} {print} {view} {update} {delete}',
        'template' => '{print-pdf} {api} {copy} {export3} {print} {update} {delete} {archive} {signature}',
        'buttons' => [
            'print' => function($url, $model){
                if((($model->status == 3) || (\Yii::$app->user->identity->isClient() == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin()) && \Yii::$app->user->identity->can('flight_btn_print')){
                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['flight/print', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать']);
                }
            },
            'update' => function($url, $model){

                $url = ['flight/update', 'id' => $model->id];

                if(\Yii::$app->controller->id == 'flight-group'){
                  $url['group'] = true;
                }

                if(\Yii::$app->user->identity->can('flight_btn_update') || \Yii::$app->user->identity->can('flight_btn_update_permament')){

                  if(\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('flight_btn_update_permament')){
                      if(($model->created_by == \Yii::$app->user->getId() && $model->status == 0 && $model->is_signature == false || $model->user_id == Yii::$app->user->getId() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isManager() == false) && (($model->status == 1 && \Yii::$app->user->identity->isClient()) == false && ($model->status == 1 && \Yii::$app->user->identity->isClient() || \Yii::$app->user->identity->isManager()) == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('flight_btn_update_permament')){
                          return Html::a('<i class="fa fa-pencil"></i>', $url, ['role' => 'modal-remote', 'title' => 'Редактировать']);
                      }
                  }

                  if(\Yii::$app->user->identity->isClient()){
                      if($model->status != 1){
                          return Html::a('<i class="fa fa-pencil"></i>', $url, ['role' => 'modal-remote', 'title' => 'Редактировать']);
                      }
                  }

                }

            },
            'export3' => function($url, $model){
                if(\Yii::$app->user->identity->isClient() == false && \Yii::$app->user->identity->can('flight_btn_export')){
                    return Html::a('<i class="fa fa-arrow-right"></i>', ['flight/export3', 'id' => $model->id], ['data-pjax' => '0', 'title' => 'Экспорт']);
                }
            },
            'print-pdf' => function($url, $model){
                if(\Yii::$app->user->identity->isClient() == false && \Yii::$app->user->identity->can('flight_btn_print_pdf')){
                    return Html::a('<i class="fa fa-file-pdf-o"></i>', ['flight/print-pdf', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Экспорт']);
                }
            },
            'copy' => function($url, $model){
                if(\Yii::$app->user->identity->can('flight_btn_copy')){
                  return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['flight/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                            'data-request-method'=>'post',
                            'data-toggle'=>'tooltip',
                            'data-confirm-title'=>'Вы уверены?',
                            'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию?']);
                }
            },
            'delete' => function($url, $model){
                if(\Yii::$app->controller->id == 'flight-group'){
                  // if(\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == true && $model->is_driver_signature == true) && \Yii::$app->user->identity->can('flight_btn_delete')){
                  // if(\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && \Yii::$app->user->identity->can('flight_btn_delete'))){
                  if(\Yii::$app->user->identity->can('flight_btn_delete')){
                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/delete', 'id' => $model->id], [
                          'role'=>'modal-remote','title'=>'Удалить', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию?'
                      ]);
                  }
                } else {
                    if(((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == false && $model->is_driver_signature == false)) && \Yii::$app->user->identity->can('flight_btn_delete')) || \Yii::$app->user->identity->can('flight_btn_permament_delete')){
                    // if(\Yii::$app->user->identity->can('flight_btn_delete')){
                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/delete', 'id' => $model->id], [
                          'role'=>'modal-remote','title'=>'Удалить', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию?'
                      ]);
                  }
                }
            },
            'archive' => function($url, $model){
                if((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == false && $model->is_driver_signature == false)) && \Yii::$app->user->identity->can('flight_btn_archive')){
                    return Html::a('<i class="fa fa-archive"></i>', ['flight/archive', 'id' => $model->id], [
                        'role'=>'modal-remote','title'=>'Архивировать', 
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Вы уверены?',
                        'data-confirm-message'=>'Вы уверены что хотите поместить данный рейс в архив?'
                    ]);
                }
            },
            'signature' => function($url, $model){
                if(\Yii::$app->user->identity->can('flight_btn_signature')){
                    return Html::a('<i class="fa fa-pencil-square-o"></i>', ['flight/signature', 'id' => $model->id], [
                        'role'=>'modal-remote','title'=>'Подписать', 
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Вы уверены?',
                        'data-confirm-message'=>'Вы уверены что хотите подписать данный рейс?'
                    ]);
                }
            },
            'api' => function($url, $model){
                    if(\Yii::$app->user->identity->can('flight_btn_api')){
                      return Html::a('<i class="fa fa-server"></i>', ['flight/api-send', 'id' => $model->id], [
                          'role'=>'modal-remote','title'=>'API', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите отправить данный рейс св систему ATI?'
                      ]); 
                    }
            },
        ],
        'viewOptions'=>['data-pjax'=>'0','title'=>'Просмотр','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Изменить', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Удалить', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию'], 
    ],
    [
        'attribute' => 'status',
        'visible'=>\app\models\Flight::isVisibleAttr('status'),
        'filter'=> \app\models\Flight::statusLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ],
        'content' => function($model){
          if(Yii::$app->user->identity->getRoleName() != "Заказчик"){
            return Html::dropDownList('status'.$model->id, $model->status, \app\models\Flight::statusLabels(), [
                'class' => 'form-control',
                'prompt' => 'Выберите',
                'onchange' => '$.get("/flight/update-attr?id='.$model->id.'&attr=status&value="+$(this).val());',
            ]);
          } else {
            return \yii\helpers\ArrayHelper::getValue(\app\models\Flight::statusLabels(), $model->status);
          }
        },

    ],

    [
        'attribute' => 'user_id',
        'visible'=>\app\models\Flight::isVisibleAttr('user_id'),
        'label' => 'Менеджер',
        'content' => function($model){
            $userName = null;
            $user = \app\models\User::findOne($model->user_id);

            if($user){
                $userName = $user->name;
            }

            if(\Yii::$app->user->identity->can('flight_manager_change')){
                return Html::dropDownList('status'.$model->id, $model->user_id, \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'), [
                        'class' => 'form-control',
                        'prompt' => 'Выберите',
                        'onchange' => '$.get("/flight/update-attr?id='.$model->id.'&attr=user_id&value="+$(this).val());',
                    ]);
            } else {
                return $userName;
            }

        },
        'filter'=> ArrayHelper::map(\app\models\User::find()->asArray()->all(), 'id', 'login'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
        'attribute' => 'checks',
        // 'visible'=> \Yii::$app->user->identity->can('flight_checks'),
        'contentOptions' => function($model){

          $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
          $docs = explode(',', $role ? $role->docs : '');

          $allChecks = false;
          foreach($docs as $doc){
              if(isset($json[$doc]) && isset($json[$doc]['value']) && $json[$doc]['value']){
                  $allChecks = true;
              } else {
                  $allChecks = false;
                  break;
              }
          }


            if($allChecks){
              return ['class' => 'success'];
            } else {
              return ['class' => 'danger'];
            }
        },
        'content' => function($model){

            $output = '';
            $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
            $docs = explode(',', $role ? $role->docs : '');

            $json = json_decode($model->checks, true);
            $roleJson = [];
            foreach(\app\models\Role::find()->where(['id' => 1])->all() as $role)
            {
              foreach(explode(',', $role->docs) as $el)
              {
                if($el == null){
                  continue;
                }
                if(in_array($el, $roleJson) == false){
                  $roleJson[] = $el;
                }
              }
            }


            if(is_array($json) == false){
              $json = [];
            }

            // foreach($json as $doc => $jsonValue){
            $hasDocs = [];
            foreach($roleJson as $doc){
              $hasDocs[] = $doc;
              $jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
              $jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$doc.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }
            foreach($json as $jsonName => $jsonEl){
              if(in_array($jsonName, $hasDocs)){
                continue;
              }
              $jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
              $jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$jsonName.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }


            return $output;
        },
    ],
    [
        'attribute' => 'checks1',
        // 'visible'=> \Yii::$app->user->identity->can('flight_checks1'),
        'contentOptions' => function($model){

          $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
          $docs = explode(',', $role ? $role->docs1 : '');

          $allChecks = false;
          foreach($docs as $doc){
              if(isset($json[$doc]) && isset($json[$doc]['value']) && $json[$doc]['value']){
                  $allChecks = true;
              } else {
                  $allChecks = false;
                  break;
              }
          }


            if($allChecks){
              return ['class' => 'success'];
            } else {
              return ['class' => 'danger'];
            }
        },
        'content' => function($model){

            $output = '';
            $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
            $docs = explode(',', $role ? $role->docs1 : '');

            $json = json_decode($model->checks1, true);
            $roleJson = [];
            foreach(\app\models\Role::find()->where(['id' => 1])->all() as $role)
            {
              foreach(explode(',', $role->docs1) as $el)
              {
                if($el == null){
                  continue;
                }
                if(in_array($el, $roleJson) == false){
                  $roleJson[] = $el;
                }
              }
            }
            if(is_array($json) == false){
              $json = [];
            }

            $hasDocs = [];
            foreach($roleJson as $doc){
              $hasDocs[] = $doc;
              $jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
              $jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks1') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks1" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$doc.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }
            foreach($json as $jsonName => $jsonEl){
              if(in_array($jsonName, $hasDocs)){
                continue;
              }
              $jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
              $jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks1" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$jsonName.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }



            return $output;
        },
    ],
    [
        'attribute' => 'checks2',
        // 'visible'=> \Yii::$app->user->identity->can('flight_checks2'),
        'contentOptions' => function($model){

          $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
          $docs = explode(',', $role ? $role->docs2 : '');

          $allChecks = false;
          foreach($docs as $doc){
              if(isset($json[$doc]) && isset($json[$doc]['value']) && $json[$doc]['value']){
                  $allChecks = true;
              } else {
                  $allChecks = false;
                  break;
              }
          }


            if($allChecks){
              return ['class' => 'success'];
            } else {
              return ['class' => 'danger'];
            }
        },
        'content' => function($model){

            $output = '';
            $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
            $docs = explode(',', $role ? $role->docs2 : '');

            $json = json_decode($model->checks2, true);
            $roleJson = [];
            foreach(\app\models\Role::find()->where(['id' => 1])->all() as $role)
            {
              foreach(explode(',', $role->docs2) as $el)
              {
                if($el == null){
                  continue;
                }
                if(in_array($el, $roleJson) == false){
                  $roleJson[] = $el;
                }
              }
            }
            if(is_array($json) == false){
              $json = [];
            }

            $hasDocs = [];
            foreach($roleJson as $doc){
              $hasDocs[] = $doc;
              $jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
              $jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks2') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks2" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$doc.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }
            foreach($json as $jsonName => $jsonEl){
              if(in_array($jsonName, $hasDocs)){
                continue;
              }
              $jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
              $jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks2" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$jsonName.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }


            return $output;
        },
    ],
    [
        'attribute' => 'checks3',
        // 'visible'=>\app\models\Flight::isVisibleAttr('checks3'),
        // 'visible'=> \Yii::$app->user->identity->can('flight_checks3'),
        'contentOptions' => function($model){

          $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
          $docs = explode(',', $role ? $role->docs3 : '');

          $allChecks = false;
          foreach($docs as $doc){
              if(isset($json[$doc]) && isset($json[$doc]['value']) && $json[$doc]['value']){
                  $allChecks = true;
              } else {
                  $allChecks = false;
                  break;
              }
          }


            if($allChecks){
              return ['class' => 'success'];
            } else {
              return ['class' => 'danger'];
            }
        },
        'content' => function($model){

            $output = '';
            $role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
            $docs = explode(',', $role ? $role->docs3 : '');

            $json = json_decode($model->checks3, true);
            $roleJson = [];
            foreach(\app\models\Role::find()->where(['id' => 1])->all() as $role)
            {
              foreach(explode(',', $role->docs3) as $el)
              {
                if($el == null){
                  continue;
                }
                if(in_array($el, $roleJson) == false){
                  $roleJson[] = $el;
                }
              }
            }
            if(is_array($json) == false){
              $json = [];
            }

            $hasDocs = [];
            foreach($roleJson as $doc){
              $hasDocs[] = $doc;
              $jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
              $jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks3') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks3" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$doc.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }
            foreach($json as $jsonName => $jsonEl){
              if(in_array($jsonName, $hasDocs)){
                continue;
              }
              $jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
              $jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
              if($jsonDatetime){
                $jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
              }
              $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
              // if(in_array($doc, $docs) == false){
              //   $disabled = 'disabled=""';
              // }
              $output .= '<p><input '.$disabled.' type="checkbox" data-attr="checks3" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$jsonName.'</p>';
              $output .= '<p style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
            }


            return $output;
        },
    ],
    [
      'label' => 'Табель',
      'attribute' => 'table',
      // 'attribute' => 'user_id',
      'visible'=>\app\models\Flight::isVisibleAttr('user_id'),
      'content' => function($model){
        return \yii\helpers\ArrayHelper::getValue($model, 'user.role');
      },
    ],
    [
        'attribute' => 'organization_id',
        'visible'=>\app\models\Flight::isVisibleAttr('organization_id'),
        'label' => 'Наша фирма',
        'value' => 'organization.name',
        'filter'=> ArrayHelper::map(\app\models\Requisite::find()->asArray()->all(), 'id', 'name'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
        'attribute' => 'order',
        'visible'=>\app\models\Flight::isVisibleAttr('order'),
        'label' => 'Заявка',
        'contentOptions' => function($model){

            if($model->is_signature && $model->is_driver_signature && $model->date_cr <= date('Y-m-d')){
                return ['class' => 'success'];
            }

            if($model->is_register){
                return ['class' => 'danger'];
            }
        },
        'width' => '10px',
        'content' => function($model){
            $output = $model->order.'<br>';

            // if(Yii::$app->user->identity->can('flight_payment_check')){
            // }

            return $output;
        },
    ],
    [
        'attribute' => 'driver_order',
        'visible'=>\app\models\Flight::isVisibleAttr('driver_order'),
        'label' => 'Заявка перевозчика',
        'width' => '10px',
        'content' => function($model){
            $output = $model->driver_order.'<br>';

            // if(Yii::$app->user->identity->can('flight_payment_check')){
            // }

            return $output;
        },
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date',
        'visible'=>\app\models\Flight::isVisibleAttr('date'),
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
          'convertFormat'=>true,
          'pluginEvents' => [
              'cancel.daterangepicker'=>'function(ev, picker) { $($("input[name=\'Flight[date]\']")).val(null).trigger("change"); }',
            ],
           'pluginOptions' => [
              'opens'=>'right',
              'locale' => [
                  'cancelLabel' => 'Clear',
                  'format' => 'Y-m-d',
               ]
           ]
         ],
        'label' => 'Дата',
        'contentOptions' => function($model){

            if($model->order_check){
                return ['class' => 'success'];
            }
        },
        'format'=> ['date', 'php:d.m.Y'],
        'content' => function($model){
            $output = \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y')."<br>";

            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="order_check" '.($model->order_check ? 'checked="true"' : '').'">';

            return $output;
        }
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'rout',
         'visible'=>\app\models\Flight::isVisibleAttr('rout'),
         'content' => function($model){
            $output = $model->rout;

            if($model->distance){
                $output .= ' '.Html::a("{$model->distance} Км.", ['flight/distance', 'id' => $model->id], ['class' => 'label label-success', 'data-pjax' => 0, 'target' => '_blank']);
            }

            return $output;
         },
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'zakazchik_id',
        'visible'=>\app\models\Flight::isVisibleAttr('zakazchik_id'),
        'contentOptions' => function($model){
            if($model->is_signature){
                return ['class' => 'success'];
            }
        },
        'value'=>'zakazchik.name',
        'filter'=> ArrayHelper::map(\app\models\Client::find()->asArray()->all(), 'id', 'name'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date_cr',
        'visible'=>\app\models\Flight::isVisibleAttr('date_cr'),
        'format'=> ['date', 'php:d.m.Y'],
        // 'filter' => '<input class="form-control" name="FlightSearch[date_cr]" type="date" value="'.\yii\helpers\ArrayHelper::getValue($_GET, 'FlightSearch.date_cr').'">',
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
          'convertFormat'=>true,
          'pluginEvents' => [
              'cancel.daterangepicker'=>'function(ev, picker) { $($("input[name=\'Flight[date_cr]\']")).val(null).trigger("change"); }',
            ],
           'pluginOptions' => [
              'opens'=>'right',
              'locale' => [
                  'cancelLabel' => 'Clear',
                  'format' => 'Y-m-d',
               ]
           ]
         ],
        'contentOptions' => function($model){

            if($model->date_cr_check){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            $output = \Yii::$app->formatter->asDate($model->date_cr, 'php:d.m.Y').'<br>';

            // if(Yii::$app->user->identity->can('flight_payment_check')){
                $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="date_cr_check" '.($model->date_cr_check ? 'checked="true"' : '').'">';
            // }

            return $output;
        },
    ], 

    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'number',
    //     'visible'=>\app\models\Flight::isVisibleAttr('number'),
    //     'contentOptions' => function($model){

    //         if($model->number_check){
    //             return ['class' => 'success'];
    //         }
    //     },
    //     'content' => function($model){
    //         $output = $model->number.'<br>';

    //         // if(Yii::$app->user->identity->can('flight_payment_check')){
    //             $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="number_check" '.($model->number_check ? 'checked="true"' : '').'">';
    //         // }

    //         return $output;
    //     },
    // ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'upd',
        'visible'=>\app\models\Flight::isVisibleAttr('upd'),
        'contentOptions' => function($model){
          if($model->date2){
            return ['class' => 'success'];
          }
        },
    ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'visible'=>\app\models\Flight::isVisibleAttr('carrier_id'),
        'contentOptions' => function($model){
            if($model->is_driver_signature){
                return ['class' => 'success'];
            }
        },
        'value'=>'carrier.name',
        'filter'=> ArrayHelper::map(\app\models\Client::find()->asArray()->all(), 'id', 'name'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'label' => 'Перевозчик тел',
        'attribute' => 'carrierTel',
        'visible' => \Yii::$app->user->identity->getRoleName() != 'Заказчик',
        'value'=>'carrier.tel',
        'filter'=> ArrayHelper::map(\app\models\Client::find()->asArray()->all(), 'id', 'tel'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'act',
        'contentOptions' => function($model){
            if($model->act){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            return \app\widgets\AjaxInput::widget([
                'model' => $model,
                'attr' => 'act',
            ]);
        },
        'visible'=>\app\models\Flight::isVisibleAttr('act'),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'act_date',
        'filter' => '<input class="form-control" name="FlightSearch[act_date]" type="date" value="'.\yii\helpers\ArrayHelper::getValue($_GET, 'FlightSearch.act_date').'">',
        'format' => ['date', 'php:d.m.Y'],
        'contentOptions' => function($model){
            if($model->act_date){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            return \app\widgets\AjaxInput::widget([
                'model' => $model,
                'format' => ['date', 'php:d.m.Y'],
                'type' => 'date',
                'attr' => 'act_date',
            ]);
        },
        'visible'=>\app\models\Flight::isVisibleAttr('act_date'),
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'label' => 'ФИО Водителя',
        'attribute'=>'driver_id',
        'visible'=>\app\models\Flight::isVisibleAttr('driver_id'),
        'value'=>'driver.data',
        'filter'=> ArrayHelper::map(\app\models\Driver::find()->asArray()->all(), 'id', 'data'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'label' => 'Телефон Водителя',
        'attribute'=>'driver_id',
        // 'visible'=>\app\models\Flight::isVisibleAttr('driver_id'),
        'value'=>'driver.phone',
        'filter'=> ArrayHelper::map(\app\models\Driver::find()->asArray()->all(), 'id', 'phone'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Данные авто',
        'attribute'=>'auto',
        // 'value'=>'auto',
        'content' => function($model){
            $driver = \app\models\Driver::find()->where(['id' => $model->auto])->one();

            if($driver){
                return "{$driver->data_avto} {$driver->car_number} {$driver->car_truck_number}";
            }         
        },
        'filter'=> ArrayHelper::map(\app\models\Driver::find()->all(), 'data_avto', 'data_avto'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],


    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'carrier_id',
    //     'visible'=>\app\models\Flight::isVisibleAttr('carrier_id'),
    //     'value'=>'carrier.name',
    // ],

    [
        'attribute' => 'we',
        'visible'=>\app\models\Flight::isVisibleAttr('we'),
        'pageSummary' => true,
        'contentOptions' => function($model){
            // if($model->we_prepayment && $model->is_payed == false){
            //     return ['class' => 'warning'];
            // }

            if($model->is_payed){
                return ['class' => 'success'];
            } elseif($model->date2 == null) {
              $dateCr = $model->date_cr;
              if($dateCr){
                $dateCr = new \DateTime($dateCr);
                $dateCr->modify('+25 days');
                if($dateCr->format('Y-m-d') < date('Y-m-d')){
                  return ['class' => 'danger'];
                }
              }
            } elseif($model->date2 && $model->col2) {
                $date2 = new \DateTime($model->date2);

                if(mb_stripos($model->col2, '-') !== false){
                  $col2 = explode('-', $model->col2);
                } elseif(mb_stripos($model->col2, '+') !== false){
                  $col2 = explode('+', $model->col2);
                } else {
                  $col2 = [$model->col2];
                }
                $col2 = $col2[count($col2)-1];

                if(is_numeric($col2) == false){
                  return [];
                }

                try {
                  // $date2->modify("+{$col2} days");
                  while($col2 > 0){
                    $date2->modify("+1 days");
                    $w = $date2->format('w');
                    if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
                      $col2--;
                    }
                  }
                } catch(\Exception $e){
                  \Yii::warning($model->upd, 'upd');                  
                }


                // $w = $date2->format('w');

                // if($w == 6){
                //     $date2->modify("+2 days");
                // }

                // if($w == 0){
                //     $date2->modify("+1 days");
                // }

                // // if(\app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one()){
                // //   $date2->modify("+1 days"); 
                // // }
                // \app\models\Holiday::nextDate($date2);

                $date2 = $date2->format('Y-m-d');

                \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');


                if($date2 < date('Y-m-d')){
                    return ['class' => 'danger'];
                // } elseif($model->we_prepayment && (strtotime($date2) - time()) <= (86400 * 2)){ // 86400 - сутки
                } elseif((strtotime($date2) - time()) <= (86400 * 2)){ // 86400 - сутки
                  return ['class' => 'warning'];
                }
            }
        },
        'content' => function($model){
            $output = number_format(doubleval($model->we), 0, '.', ' ').' руб.'.'<br>';

            if(Yii::$app->user->identity->can('flight_payment_check')){
                $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_payed" '.($model->is_payed ? 'checked="true"' : '').'">';
            }

            if(Yii::$app->user->identity->can('flight_prepayment')){
              

              $dateStr = '';
              $inputStyle = $model->we_prepayment ? ' background: #fff9f1;' : null;
              if($model->date2 && $model->col2) {
                  $date2 = new \DateTime($model->date2);

                  if(mb_stripos($model->col2, '-') !== false){
                    $col2 = explode('-', $model->col2);
                  } elseif(mb_stripos($model->col2, '+') !== false){
                    $col2 = explode('+', $model->col2);
                  } else {
                    $col2 = [$model->col2];
                  }
                  $col2 = $col2[count($col2)-1];

                  if(is_numeric($col2) == false){
                    $output .= "<input type='input' data-id='".$model->id."' data-e='we_prepayment' class='form-control' value='".$model->we_prepayment."' style='padding: 1px 5px; height: 20px; font-size: 10px;".$inputStyle."' placeholder='Предоплата'>";
                    return $output;
                  }

                  try {
                    // $date2->modify("+{$col2} days");
                    while($col2 > 0){
                      $date2->modify("+1 days");
                      $w = $date2->format('w');
                      if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
                        $col2--;
                      }
                    }
                  } catch(\Exception $e){
                    \Yii::warning($model->upd, 'upd');                  
                  }

                  // $w = $date2->format('w');

                  // if($w == 6){
                  //     $date2->modify("+2 days");
                  // }

                  // if($w == 0){
                  //     $date2->modify("+1 days");
                  // }

                  // if(\app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one()){
                    // $date2->modify("+1 days"); 
                  // }
                  // \app\models\Holiday::nextDate($date2);

                  $date2 = $date2->format('Y-m-d');

                  $dateStr = \Yii::$app->formatter->asDate($date2, 'php:d.m.Y');    
              }
              if($model->we_prepayment_datetime){
                $output .= "   <span style='font-size: 11px;'>".\Yii::$app->formatter->asDate($model->we_prepayment_datetime, 'php:d.m.Y')."</span><br>";
              }
              $output .= "<input type='input' data-id='".$model->id."' data-e='we_prepayment' class='form-control' value='".$model->we_prepayment."' style='padding: 1px 5px; height: 20px; font-size: 10px;".$inputStyle."' placeholder='Предоплата'>".$dateStr;
            }


            return $output;
        },
    ],

    // [
    //     'header' => '<i class="fa fa-rub"></i>',
    //     'content' => function($model){
    //         return '<input type="checkbox" data-id="'.$model->id.'" data-edit="is_payed" '.($model->is_payed ? 'checked="true"' : '').'">';
    //     },
    // ],


    [
        'attribute' => 'pay_us',
        'label' => 'Форма оплаты (Заказчик)',
        'visible'=>\app\models\Flight::isVisibleAttr('pay_us'),
    ],




    [
        'attribute' => 'payment_out',
        'visible'=>\app\models\Flight::isVisibleAttr('payment_out'),
        'pageSummary' => true,
        'contentOptions' => function($model){
            // if($model->payment_out_prepayment && $model->is_driver_payed == false){
            //     return ['class' => 'warning'];
            // }
            \Yii::warning('tez', 'tez');
            if($model->is_driver_payed){
                return ['class' => 'success'];
            }
            // elseif($model->date3 && $model->col1) {
            //     if(strpos($model->col1, '—') === null){
            //         $date3 = new \DateTime($model->date3);

            //         $date3->modify("+{$model->col1} days");
                
            //         if($w == 0){
            //             $date3->modify("+2 days");
            //         }

            //         if($w == 6){
            //             $date3->modify("+1 days");
            //         }

            //         $date3 = $date3->format('Y-m-d');

            //         \Yii::warning("{$date3} > ".date('Y-m-d'), 'danger');

            //         if($date3 < date('Y-m-d')){
            //             return ['class' => 'danger'];
            //         }
            //     }
            // }
             elseif($model->date3 && $model->col1) {
                $date2 = new \DateTime($model->date3);

                if(mb_stripos($model->col1, '-') !== false){
                  $col1 = explode('-', $model->col1);
                } elseif(mb_stripos($model->col1, '+') !== false){
                  $col1 = explode('+', $model->col1);
                } else {
                  $col1 = [$model->col1];
                }
                $col1 = $col1[count($col1)-1];
                if(is_numeric($col1) == false){
                  return [];
                }


                while($col1 > 0){
                  $date2->modify("+1 days");
                  $w = $date2->format('w');
                  if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
                    $col1--;
                  }
                }
                // $date2->modify("+{$col1} days");

                // $w = $date2->format('w');

                // if($w == 6){
                //     $date2->modify("+2 days");
                // }

                // if($w == 0){
                //     $date2->modify("+1 days");
                // }

                // if(\app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one()){
                //   $date2->modify("+1 days"); 
                // }


                // if(\app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one()){
                //   $date2->modify("+1 days"); 
                // }
                \app\models\Holiday::nextDate($date2);

                $date2 = $date2->format('Y-m-d');

                \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

                if($date2 < date('Y-m-d')){
                    return ['class' => 'danger'];
                } elseif((strtotime($date2) - time()) <= (86400 * 2)){ // 86400 - сутки
                  return ['class' => 'warning'];
                }
            }
        },
        'content' => function($model){
            $output = number_format(doubleval($model->payment_out), 0, '.', ' ').' руб.'.'<br>';

            if(Yii::$app->user->identity->can('flight_payment_check')){
                $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_driver_payed" '.($model->is_driver_payed ? 'checked="true"' : '').'">';
            }

            if(Yii::$app->user->identity->can('flight_prepayment')){
              

               $dateStr = '';
               $inputStyle = $model->payment_out_prepayment ? ' background: #fff9f1;' : null;
              if($model->date3 && $model->col1) {

                $date2 = new \DateTime($model->date3);

                if(mb_stripos($model->col1, '-') !== false){
                  $col1 = explode('-', $model->col1);
                } elseif(mb_stripos($model->col1, '+') !== false){
                  $col1 = explode('+', $model->col1);
                } else {
                  $col1 = [$model->col1];
                }
                $col1 = $col1[count($col1)-1];
                if(is_numeric($col1) == false){
                  $output .= "<input type='input' data-id='".$model->id."' data-e='payment_out_prepayment' class='form-control' value='".$model->payment_out_prepayment."' style='padding: 1px 5px; height: 20px; font-size: 10px;".$inputStyle."' placeholder='Предоплата'>";
                  return $output;
                }


                while($col1 > 0){
                  $date2->modify("+1 days");
                  $w = $date2->format('w');
                  if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
                    $col1--;
                  }
                }

                // $date2->modify("+{$col1} days");

                // $w = $date2->format('w');

                // if($w == 6){
                //     $date2->modify("+2 days");
                // }

                // if($w == 0){
                //     $date2->modify("+1 days");
                // }

                // // if(\app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one()){
                // //   $date2->modify("+1 days"); 
                // // }
                // \app\models\Holiday::nextDate($date2);


                $date2 = $date2->format('Y-m-d');

                \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

                $dateStr = \Yii::$app->formatter->asDate($date2, 'php:d.m.Y');

              }
              if($model->payment_out_prepayment_datetime){
                $output .= "   <span style='font-size: 11px;'>".\Yii::$app->formatter->asDate($model->payment_out_prepayment_datetime, 'php:d.m.Y')."</span><br>";
              }
              $output .= "<input type='input' data-id='".$model->id."' data-e='payment_out_prepayment' class='form-control' value='".$model->payment_out_prepayment."' style='padding: 1px 5px; height: 20px; font-size: 10px;".$inputStyle."' placeholder='Предоплата'>".$dateStr;
            }

            return $output;
        },
    ],

    [
        'attribute' => 'otherwise2',
        'visible'=>\app\models\Flight::isVisibleAttr('otherwise2'),
        'label' => 'Форма оплаты (Водитель)',
    ],

    [
        'attribute' => 'salary',
        'visible'=>\app\models\Flight::isVisibleAttr('salary'),
        'label' => 'Зарплата',
        'contentOptions' => function($model){
            if($model->is_salary_payed){
                return ['class' => 'success'];
            }
        },
        'pageSummary' => true,
        'content' => function($model){
            $remain = $model->is_salary_payed ? number_format(doubleval($model->payment_out - $model->salary), 0, '.', ' ').' руб.' : null;

            if(\Yii::$app->user->identity->can('flight_check_salary')){
              $checkbox = '<input type="checkbox" data-id="'.$model->id.'" data-e="is_salary_payed" '.($model->is_salary_payed ? 'checked="true"' : '').'">';
              // if($model->is_salary_payed_datetime){
                $checkbox .= "<p><i>".\Yii::$app->formatter->asDate($model->is_salary_payed_datetime, 'php:d.m.Y H:i')."</i></p>";
              // }
            } else {
              $checkbox = null;
            }

            // if(\Yii::$app->user->identity->isSuperAdmin()){
            if(\Yii::$app->user->identity->can('flight_check_salary')){
                return \app\widgets\AjaxInput::widget([
                    'model' => $model,
                    'format' => ['integer'],
                    'attr' => 'salary',
                ])."<div>{$checkbox}</div><div>{$remain}</div>";
            } else {
                return \Yii::$app->formatter->asInteger($model->salary).' руб.'."<div>{$checkbox}</div><div>{$remain}</div>";
            }
        },
        'format' => ['decimal', 2],
    ],


    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'visible'=>\app\models\Flight::isVisibleAttr('carrier_id'),
        'filter' => '',
        'contentOptions' => function($model){
            if($model->is_ati_driver){
                return ['class' => 'success'];
            }
        },
        'label' => 'АТИ Водителя',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->carrier_id);

            if($carrier){
                return \yii\helpers\Html::a($carrier->code, "https://ati.su/firms/{$carrier->code}/rating", ['data-pjax' => 0, 'target' => '_blank']).'<br>'.Html::a($carrier->email, 'mailto:'.$carrier->email, ['data-pjax' => 0, 'target' => '_blank']).'<br>'.'<input type="checkbox" data-id="'.$model->id.'" data-e="is_ati_driver" '.($model->is_ati_driver ? 'checked="true"' : '').'">';
            }
        },
        // ''
        // 'visible'=>\app\models\Flight::isVisibleAttr('driver_id'),
        // 'value'=>'driver.data',
        // 'filter'=> ArrayHelper::map(\app\models\Driver::find()->asArray()->all(), 'id', 'data'),
        // 'filterType'=> GridView::FILTER_SELECT2,
        // 'filterWidgetOptions'=> [
        //        'options' => ['prompt' => '', 'multiple' => true],
        //        'pluginOptions' => [
        //               'allowClear' => true,
        //               'tags' => false,
        //               'tokenSeparators' => [','],
        //        ]
        // ]
    ],


        [
         'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'organization_id',
        'visible'=>\app\models\Flight::isVisibleAttr('organization_id'),
        'label' => 'АТИ Заказчика',
        // 'headerOptions' => ['style' => 'width: 10px;'],
        'width' => '10px',
        'contentOptions' => function($model){
            if($model->is_ati_client){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->zakazchik_id);



            if($carrier){
                return \yii\helpers\Html::a($carrier->code, "https://ati.su/firms/{$carrier->code}/rating", ['data-pjax' => 0, 'target' => '_blank']).'<br>'.Html::a($carrier->email, 'mailto:'.$carrier->email, ['data-pjax' => 0, 'target' => '_blank']).'<br>'.'<input type="checkbox" data-id="'.$model->id.'" data-e="is_ati_client" '.($model->is_ati_client ? 'checked="true"' : '').'">';
            }
        },
        // 'visible'=>\app\models\Flight::isVisibleAttr('organization_id'),
        // 'value'=>'organization.name',
        // 'filter'=> ArrayHelper::map(\app\models\Requisite::find()->asArray()->all(), 'id', 'name'),
        // 'filterType'=> GridView::FILTER_SELECT2,
        // 'filterWidgetOptions'=> [
        //        'options' => ['prompt' => '', 'multiple' => true],
        //        'pluginOptions' => [
        //               'allowClear' => true,
        //               'tags' => false,
        //               'tokenSeparators' => [','],
        //        ]
        // ]
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'order',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'body_type',
         'value' => function($model){
          $data = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/cars.php'), 'TypeId', 'Name');
          return isset($data[$model->body_type]) ? $data[$model->body_type] : null;
         },
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'loading_type',
         'value' => function($model){
          $data = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/loading-types.php'), 'TypeId', 'Name');
          return isset($data[$model->loading_type]) ? $data[$model->loading_type] : null;
         },
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'uploading_type',
         'value' => function($model){
          $data = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/loading-types.php'), 'TypeId', 'Name');
          return isset($data[$model->uploading_type]) ? $data[$model->uploading_type] : null;
         },
    ],
    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'date',
    //     'visible'=>\app\models\Flight::isVisibleAttr('date'),
    //     'format'=> ['date', 'php:d.m.Y'],
    // ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'view_auto',
        'visible'=>0,
        'filter'=> \app\models\Flight::view_autoLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //      'attribute'=>'address1',
    //      'visible'=>\app\models\Flight::isVisibleAttr('address1'),
    // ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'shipping_date',
        'visible'=>\app\models\Flight::isVisibleAttr('shipping_date'),
        'format'=> ['date', 'php:d.m.Y'],
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'telephone1',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'type',
        'visible'=>0,
        'filter'=> \app\models\Flight::typeLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact_out2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name2',
        'visible'=>0,
        'filter'=> \app\models\Flight::name2Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out3',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out3',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'name3',
         'content' => function($model){
            $name3 = $model->name3;
            if(mb_strlen($name3) > 30){
                $first = mb_substr($name3, 0, 30);
                $name3 = $first.'<a onclick=\'event.preventDefault(); $("#name3-'.$model->id.'").html($(this).data("text"));\' data-text="'.str_replace('"', "&#34;", $name3).'" class="text-dots">...</a>';

                return '<span id="name3-'.$model->id.'">'.$name3.'</span>';
            }
            return $name3;
         }
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out4',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out4',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'telephone',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'cargo_weight',
         'value' => function($model){
          return $model->cargo_weight.' '.ArrayHelper::getValue(app\models\Flight::typeWeightLabels(), $model->type_weight);
         },
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'name',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out5',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact_out',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out5',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'volume',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out6',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact_out3',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'dop_informaciya_o_gruze',
         'visible'=>0,
    ],
    [
      'class'=>'\kartik\grid\DataColumn',
      'attribute' => 'information',
    ],
    
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'pay_us',
        'visible'=>0,
        'filter'=> \app\models\Flight::pay_usLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'payment1',
        'visible'=>0,
        'filter'=> \app\models\Flight::payment1Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'col2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'otherwise2',
        'visible'=>0,
        'filter'=> \app\models\Flight::otherwise2Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'otherwise3',
        'visible'=>0,
        'filter'=> \app\models\Flight::otherwise3Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'col1',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'fio',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'number',
        'visible' => 0,
        // 'format' =>['decimal', 2],
        // 'pageSummary' => true, 
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'upd',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date2',
        'visible'=>0,
        'format'=> ['date', 'php:d.m.Y'],
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date3',
        'visible'=>0,
        'format'=> ['date', 'php:d.m.Y'],
    ],
    [

         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'recoil',
         'contentOptions' => function($model){
             if($model->is_recoil_payment){
                 return ['class' => 'success'];
             }
         },
         'content' => function($model){
          $output = "<p>{$model->recoil}</p>";

          if(\Yii::$app->user->identity->can('flight_check_recoil')){
            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_recoil_payment" '.($model->is_recoil_payment ? 'checked="true"' : '').'">';
          }

          return $output;
         },
         'visible'=>1,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'is_insurance',
         'contentOptions' => function($model){
             if($model->is_ensurance_payment){
                 return ['class' => 'success'];
             }
         },
         'content' => function($model){
          $output = "<p>{$model->ensurance_prime}</p>";

          if(\Yii::$app->user->identity->can('flight_check_insurance')){
            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_ensurance_payment" '.($model->is_ensurance_payment ? 'checked="true"' : '').'">';
          }

          return $output;
         },
         'visible'=>1,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'additional_credit',
         'contentOptions' => function($model){
             if($model->is_additional_credit_payment){
                 return ['class' => 'success'];
             }
         },
         'content' => function($model){
          $output = "<p>{$model->additional_credit}</p>";

          if(\Yii::$app->user->identity->can('flight_check_additional_credit')){
            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_additional_credit_payment" '.($model->is_additional_credit_payment ? 'checked="true"' : '').'">';
          }

          return $output;
         },
         'visible'=>1,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'your_text',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'otherwise4',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'otherwise',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'file',
         'visible'=>0,
    ],

];   

