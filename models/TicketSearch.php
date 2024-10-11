<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ticket;

/**
 * TicketSearch represents the model behind the search form about `Ticket`.
 */
class TicketSearch extends Ticket
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject'], 'string'],
            [['status', 'user_id', 'user_service_id', 'create_at'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Ticket::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'user_service_id' => $this->user_service_id,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'status', $this->status]);

              if($this->create_at){
             $date = explode(' - ', $this->create_at);
             if(isset($date[0]) && isset($date[1])){
             $query->andWhere(['between', 'create_at', $date[0].' 00:00:00', $date[1].' 23:59:59']);
            }
         }

     

        return $dataProvider;
    }
}
