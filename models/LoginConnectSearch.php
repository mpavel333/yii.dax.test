<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LoginConnect;

/**
 * LoginConnectSearch represents the model behind the search form about `LoginConnect`.
 */
class LoginConnectSearch extends LoginConnect
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip_address', 'status', 'login', 'password', 'code'], 'string'],
            [['create_at'], 'safe'],
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
        $query = LoginConnect::find();

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

        $query->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'code', $this->code]);

               if($this->create_at){
             $date = explode(' - ', $this->create_at);
             if(isset($date[0]) && isset($date[1])){
             $query->andWhere(['between', 'create_at', $date[0].' 00:00:00', $date[1].' 23:59:59']);
            }
         }


        return $dataProvider;
    }
}
