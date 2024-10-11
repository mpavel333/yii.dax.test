<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TicketMessage;

/**
 * TicketMessageSearch represents the model behind the search form about `TicketMessage`.
 */
class TicketMessageSearch extends TicketMessage
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_id', 'user_id', 'create_at', 'is_read'], 'safe'],
            [['text', 'application'], 'string'],
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
        $query = TicketMessage::find();

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
            'ticket_id' => $this->ticket_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'application', $this->application]);

              if($this->create_at){
             $date = explode(' - ', $this->create_at);
             if(isset($date[0]) && isset($date[1])){
             $query->andWhere(['between', 'create_at', $date[0].' 00:00:00', $date[1].' 23:59:59']);
            }
         }
 
      

        return $dataProvider;
    }
}
