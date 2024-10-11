<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Message;

/**
 * MessageSearch represents the model behind the search form about `Message`.
 */
class MessageSearch extends Message
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['flight_id', 'user_id', 'user_to_id', 'create_at', 'is_read'], 'safe'],
            [['text'], 'string'],
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
        $query = Message::find();

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
            'flight_id' => $this->flight_id,
            'user_id' => $this->user_id,
            'user_to_id' => $this->user_to_id,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

              if($this->create_at){
             $date = explode(' - ', $this->create_at);
             if(isset($date[0]) && isset($date[1])){
             $query->andWhere(['between', 'create_at', $date[0].' 00:00:00', $date[1].' 23:59:59']);
            }
         }
 
      

        return $dataProvider;
    }
}
