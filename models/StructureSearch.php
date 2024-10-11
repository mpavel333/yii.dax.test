<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Structure;

/**
 * StructureSearch represents the model behind the search form about `Structure`.
 */
class StructureSearch extends Structure
{
    /**
     * @inheritdoc
     */
    public $email;
    public $phone;
    public $tabel;
    
    
    public function rules()
    {
        return [
            [['name','email','phone','tabel'], 'string'],
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
        $query = Structure::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $query->joinWith('structureUsers');


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'structure_users.email', $this->email])
              ->andFilterWhere(['like', 'structure_users.phone', $this->phone])
              ->andFilterWhere(['like', 'structure_users.tabel', $this->tabel]);
        
/*
        $query->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'driver', $this->driver])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'data_avto', $this->data_avto]);

        if(Yii::$app->user->identity->can('driver_view_all') == false){
            $query->andWhere(['user_id' => Yii::$app->user->getId()]);
        }
*/  
        return $dataProvider;
    }
}
