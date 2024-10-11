<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Flight;

/**
 * MetalSearch represents the model behind the search form about `Flight`.
 */
class MetalSearch extends Metal
{
    public $carrierTel;

    public $driverPhone;

    public $table;

    public $enableControllerCheck = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_id', 'zakazchik_id', 'carrier_id', 'driver_id', 'date', 'view_auto', 'shipping_date', 'type', 'name2', 'pay_us', 'payment1', 'recoil','otherwise2', 'otherwise3', 'date_cr', 'number', 'date2', 'date3', 'file', 'status', 'created_by', 'driver', 'user_id', 'carrierTel', 'auto'], 'safe'],
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
        $query = Metal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    // 'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ],
            ],
        ]);


        $query->joinWith(['user','carrier','organization','driver','zakazchik as client2']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // <Default filter>
        /*
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
                    $dataProvider->query->andWhere(['or', ['or', ['flight.user_id' => Yii::$app->user->getId()], ['created_by' => Yii::$app->user->getId()]], ['is_register' => true]])->orWhere(['is_order' => true]);
                } else {
                    $dataProvider->query->andWhere(['or', ['flight.user_id' => Yii::$app->user->getId()], ['created_by' => Yii::$app->user->getId()]]);
                }
            }
            // $dataProvider->query->andWhere(['or', ['and', ['is_signature' => true], ['is_driver_signature' => true]], ['is_order' => false]]);
            $dataProvider->query->andWhere(['is', 'archive_datetime', null]);
        }
        */
        // </Default filter>

        if(isset($this->auto[0]) && $this->auto[0] === ''){
            $this->auto = null;
        }

        $query->andFilterWhere([
            'organization_id' => $this->organization_id,
            'zakazchik_id' => $this->zakazchik_id,
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
            'metal.user_id' => $this->user_id,
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

        //var_dump($dataProvider->query->createCommand()->getRawSql()); die();

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


        if($asArray){
            $query->asArray();
        }

        $clientPayment = isset($_GET['client_payment']) ? boolval($_GET['client_payment']) : false;
        $driverPayment = isset($_GET['driver_payment']) ? boolval($_GET['driver_payment']) : false;
        $clientPrepayment = isset($_GET['client_prepayment']) ? boolval($_GET['client_prepayment']) : false;
        $driverPrepayment = isset($_GET['driver_prepayment']) ? boolval($_GET['driver_prepayment']) : false;

        if($clientPayment || $driverPayment || $clientPrepayment || $driverPrepayment){
            $cloned = clone $dataProvider;
            $cloned->pagination = false;

            if($clientPayment){
                $pks = [];
                foreach ($cloned->models as $cModel) {
                    if($cModel->is_payed){
                        
                    } elseif($cModel->date2 && $cModel->col2) {
                        $date2 = new \DateTime($cModel->date2);

                        try {
                            $date2->modify("+{$cModel->col2} days");
                        } catch(\Exception $e){
                          \Yii::warning($cModel->upd, 'upd');                    
                        }

                        $w = $date2->format('w');

                        if($w == 0){
                            $date2->modify("+2 days");
                        }

                        if($w == 6){
                            $date2->modify("+1 days");
                        }

                        $date2 = $date2->format('Y-m-d');

                        \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

                        if(($date2 < date('Y-m-d')) || ($cModel->we_prepayment && $cModel->is_payed == false)){
                            $pks[] = $cModel->id;
                        }
                    }
                }
                $dataProvider->query->andWhere(['MetalSearch.id' => $pks]);
            }

            if($driverPayment){
                $pks = [];
                $upds = [];
                foreach ($cloned->models as $cModel) {
                    if($cModel->is_driver_payed){
                        // return ['class' => 'success'];
                    } elseif($cModel->date3 && $cModel->col1) {
                        $date2 = new \DateTime($cModel->date3);

                        if(is_numeric($cModel->col1) == false){
                            \Yii::warning($cModel->upd, '$cModel->col1 string!!!');
                            $upds[] = $cModel->upd;
                            continue;
                        }

                        $date2->modify("+{$cModel->col1} days");

                        $w = $date2->format('w');

                        if($w == 0){
                            $date2->modify("+2 days");
                        }

                        if($w == 6){
                            $date2->modify("+1 days");
                        }

                        $date2 = $date2->format('Y-m-d');

                        \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

                        if(($date2 < date('Y-m-d')) || ($cModel->payment_out_prepayment && $cModel->is_driver_payed == false)){
                            $pks[] = $cModel->id;
                        }
                    }
                }
                \Yii::warning($upds, 'path upds');
                $dataProvider->query->andWhere(['metal.id' => $pks]);
            }

        }
                                                    
        return $dataProvider;
    }
}
