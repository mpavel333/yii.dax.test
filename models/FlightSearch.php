<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Flight;

/**
 * FlightSearch represents the model behind the search form about `Flight`.
 */
class FlightSearch extends Flight
{
    public $carrierTel;

    public $driverPhone;

    public $driver_phone;

    public $table;

    public $rout_from;
    public $rout_to;
    public $driver_car_number;

    public $enableControllerCheck = true;

    public $salary_from;
    public $salary_to;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_id', 'zakazchik_id', 'carrier_id', 'driver_id', 'date', 'view_auto', 'shipping_date', 'type', 'name2', 'pay_us', 'payment1', 'recoil','otherwise2', 'otherwise3', 'date_cr', 'number', 'date2', 'date3', 'file', 'status', 'created_by', 'driver', 'user_id', 'carrierTel', 'auto', 'salary_from', 'salary_to'], 'safe'],
            [['rout', 'order', 'address1', 'telephone1', 'date_out2', 'address_out2', 'contact_out2', 'address_out3', 'date_out3', 'contact', 'name3', 'address_out4', 'date_out4', 'telephone', 'cargo_weight', 'name', 'address_out5', 'contact_out', 'date_out5', 'volume', 'address', 'date_out6', 'contact_out3', 'dop_informaciya_o_gruze', 'we', 'col2', 'payment_out', 'col1', 'fio', 'upd',  'your_text', 'otherwise4', 'otherwise', 'act', 'act_date', 'salary', 'driver_order', 'driverPhone', 'table'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param boolean $asArray
     *
     * @return ActiveDataProvider
     */
    public function search($params, $asArray = false)
    {
        $query = Flight::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    // 'created_at' => SORT_DESC,
                    // 'id' => SORT_DESC,
                ],
            ],
        ]);

        $orderById = true;

        //print_r($params); die;
        //$query->joinWith('user');
        $this->load($params);

/*
        $query->joinWith('user');
        $query->joinWith('carrier');
        $query->joinWith('organization');
        $query->joinWith('driver');
        $query->joinWith('zakazchik');
*/
        
        $query->joinWith(['user','carrier','organization','driver','zakazchik as client2']);



        //$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // <Default filter>
        if(Yii::$app->controller->id == 'flight' && $this->enableControllerCheck){ // Работает только в разделе "Рейсы"
            if(Yii::$app->user->identity->isSuperAdmin()){
                $dataProvider->query->andWhere(['or', ['or', ['flight.user_id' => Yii::$app->user->getId()], ['created_by' => Yii::$app->user->getId()]], ['is_register' => true]])->orWhere(['is_order' => true]);
            } else {
                if(Yii::$app->user->identity->can('flight_view_all')){
                    // $dataProvider->query->andWhere([
                    //         'and',
                    //         ['or', ['or', ['user_id' => Yii::$app->user->getId()], ['created_by' => Yii::$app->user->getId()]], ['is_order' => true]],
                    //         ['or', ['user_id' => null], ['user_id' => \Yii::$app->user->getId()]],
                    //     ]);
                         
                    //$dataProvider->query->andWhere(['or', ['or', ['flight.user_id' => Yii::$app->user->getId()], ['created_by' => Yii::$app->user->getId()]], ['is_register' => true]])->orWhere(['is_order' => true]);
                } else {
                    $dataProvider->query->andWhere(['or', ['flight.user_id' => Yii::$app->user->getId()], ['created_by' => Yii::$app->user->getId()]]);
                }
            }
            // $dataProvider->query->andWhere(['or', ['and', ['is_signature' => true], ['is_driver_signature' => true]], ['is_order' => false]]);
            $dataProvider->query->andWhere(['is', 'archive_datetime', null]);
        }
        // </Default filter>

        if(isset($this->auto[0]) && $this->auto[0] === ''){
            $this->auto = null;
        }

        $query->andFilterWhere([
            'organization_id' => $this->organization_id,
            'flight.zakazchik_id' => $this->zakazchik_id,
            'carrier_id' => $this->carrier_id,
            'driver_id' => $this->driver_id,
            'view_auto' => $this->view_auto,
            'type' => $this->type,
            'name2' => $this->name2,
            'pay_us' => $this->pay_us,
            'payment1' => $this->payment1,
            'otherwise2' => $this->otherwise2,
            'otherwise3' => $this->otherwise3,
            'status' => $this->status,
            'created_by' => $this->created_by,
            // 'driver_id' => $this->driver,
            'flight.user_id' => $this->user_id,
            // 'carrier_id' => $this->carrierTel,
            'auto' => $this->auto,
            'act' => $this->act,
            // 'date' => $this->date,
            // 'date_cr' => $this->date_cr,
            'user.role' => $this->table,
            'act_date' => $this->act_date,
            'salary' => $this->salary,
            'driver_order' => $this->driver_order,
        ]);

        $query->andFilterWhere(['like', 'rout', $this->rout])
            ->andFilterWhere(['like', 'order', $this->order])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'telephone1', $this->telephone1])
            ->andFilterWhere(['like', 'date_out2', $this->date_out2])
            ->andFilterWhere(['like', 'address_out2', $this->address_out2])
            ->andFilterWhere(['like', 'contact_out2', $this->contact_out2])
            ->andFilterWhere(['like', 'address_out3', $this->address_out3])
            ->andFilterWhere(['like', 'date_out3', $this->date_out3])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'name3', $this->name3])
            ->andFilterWhere(['like', 'address_out4', $this->address_out4])
            ->andFilterWhere(['like', 'date_out4', $this->date_out4])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'cargo_weight', $this->cargo_weight])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address_out5', $this->address_out5])
            ->andFilterWhere(['like', 'contact_out', $this->contact_out])
            ->andFilterWhere(['like', 'date_out5', $this->date_out5])
            ->andFilterWhere(['like', 'volume', $this->volume])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'date_out6', $this->date_out6])
            ->andFilterWhere(['like', 'contact_out3', $this->contact_out3])
            ->andFilterWhere(['like', 'dop_informaciya_o_gruze', $this->dop_informaciya_o_gruze])
            ->andFilterWhere(['like', 'we', $this->we])
            ->andFilterWhere(['like', 'col2', $this->col2])
            ->andFilterWhere(['like', 'payment_out', $this->payment_out])
            ->andFilterWhere(['like', 'col1', $this->col1])
            ->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'upd', $this->upd])
            ->andFilterWhere(['like', 'recoil', $this->recoil])
            ->andFilterWhere(['like', 'your_text', $this->your_text])
            ->andFilterWhere(['like', 'otherwise4', $this->otherwise4])
            ->andFilterWhere(['like', 'otherwise', $this->otherwise]);


        // if(Yii::$app->user->identity->can('flight_view_all') == false){
            // $query->andWhere(['user_id' => Yii::$app->user->getId()]);
        // }

        if($this->date){
            $dates = explode(' - ', $this->date);
            $query->andWhere(['between', 'date', $dates[0], $dates[1]]);
        }
        if($this->date_cr){
            $dates = explode(' - ', $this->date_cr);
            $query->andWhere(['between', 'date_cr', $dates[0], $dates[1]]);
        }
        
        if($this->salary_from){
            $query->andWhere(['>=', 'is_payed_date', date('Y-m-d H:i:s', strtotime($this->salary_from))]);
        }        
        
        if($this->salary_to){
            $query->andWhere(['<=', 'is_payed_date', date('Y-m-d H:i:s', strtotime($this->salary_to))]);
        }        

        if($asArray){
            $query->asArray();
        }
        
        //$q = $query->createCommand()->getRawSql();
        //echo $q; die;

        $clientPayment = isset($_GET['client_payment']) ? boolval($_GET['client_payment']) : false;
        $clientPaymentSort = isset($_GET['client_payment_sort']) ? boolval($_GET['client_payment_sort']) : null;
        $driverPayment = isset($_GET['driver_payment']) ? boolval($_GET['driver_payment']) : false;
        $driverPaymentSort = isset($_GET['driver_payment_sort']) ? boolval($_GET['driver_payment_sort']) : null;
        $clientPrepayment = isset($_GET['client_prepayment']) ? boolval($_GET['client_prepayment']) : false;
        $driverPrepayment = isset($_GET['driver_prepayment']) ? boolval($_GET['driver_prepayment']) : false;

        if($clientPayment || $driverPayment || $clientPrepayment || $driverPrepayment){
            $cloned = clone $dataProvider;
            $cloned->pagination = false;

            if($clientPayment){
                $pks = [];
                foreach ($cloned->models as $cModel) {
                    if($cModel->is_payed){
                        
                    } elseif($cModel->date2 == null) {
                      $dateCr = $cModel->date_cr;
                      if($dateCr){
                        $dateCr = new \DateTime($dateCr);
                        $dateCr->modify('+25 days');
                        if($dateCr->format('Y-m-d') < date('Y-m-d')){
                          $pks[] = $cModel->id;
                        }
                      }
                    } elseif($cModel->date2 && $cModel->col2) {
                        $date2 = new \DateTime($cModel->date2);

                        if(mb_stripos($cModel->col2, '-') !== false){
                          $col2 = explode('-', $cModel->col2);
                        } elseif(mb_stripos($cModel->col2, '+') !== false){
                          $col2 = explode('+', $cModel->col2);
                        } else {
                          $col2 = [$cModel->col2];
                        }
                        $col2 = $col2[count($col2)-1];

                        if(is_numeric($col2) == false){
                          continue;
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
                            $pks[] = $cModel->id;
                        } elseif((strtotime($date2) - time()) <= (86400 * 2)){ // 86400 - сутки
                          $pks[] = $cModel->id;
                        }
                    }
                }
                $dataProvider->query->andWhere(['flight.id' => $pks]);
                if($clientPaymentSort !== null){
                  $orderById = false;
                  if($clientPaymentSort === true){
                    $dataProvider->query->orderBy('date2_next desc');
                  } elseif($clientPaymentSort === false){
                    $dataProvider->query->orderBy('date2_next asc');
                  }
                }
            }

            if($driverPayment){
                $pks = [];
                $upds = [];
                foreach ($cloned->models as $cModel) {
                    if($cModel->is_driver_payed){
                        // return ['class' => 'success'];
                    } elseif($cModel->date3 && $cModel->col1) {


                        $date2 = new \DateTime($cModel->date3);

                        if(mb_stripos($cModel->col1, '-') !== false){
                          $col1 = explode('-', $cModel->col1);
                        } elseif(mb_stripos($cModel->col1, '+') !== false){
                          $col1 = explode('+', $cModel->col1);
                        } else {
                          $col1 = [$cModel->col1];
                        }
                        $col1 = $col1[count($col1)-1];
                        if(is_numeric($col1) == false){
                          // return [];
                            continue;
                        }


                        while($col1 > 0){
                          $date2->modify("+1 days");
                          $w = $date2->format('w');
                          if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
                            $col1--;
                          }
                        }


                        \app\models\Holiday::nextDate($date2);

                        $date2 = $date2->format('Y-m-d');

                        \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

                        if($date2 < date('Y-m-d')){
                          $pks[] = $cModel->id;
                        } elseif((strtotime($date2) - time()) <= (86400 * 2)){ // 86400 - сутки
                          $pks[] = $cModel->id;
                        }

                    }
                }
                \Yii::warning($upds, 'path upds');
                $dataProvider->query->andWhere(['flight.id' => $pks]);
                if($driverPaymentSort !== null){
                  $orderById = false;
                  if($driverPaymentSort === true){
                    $dataProvider->query->orderBy('date3_next desc');
                  } elseif($driverPaymentSort === false){
                    $dataProvider->query->orderBy('date3_next asc');
                  }
                }
            }

        }

        if($orderById){
          $dataProvider->query->orderBy('id desc');
        }
                                                    
        return $dataProvider;
    }
}
