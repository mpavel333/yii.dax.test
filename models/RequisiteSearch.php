<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Requisite;

/**
 * RequisiteSearch represents the model behind the search form about `Requisite`.
 */
class RequisiteSearch extends Requisite
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'doljnost_rukovoditelya', 'fio_polnostyu', 'official_address', 'bank_name', 'inn', 'kpp', 'ogrn', 'bic', 'kr', 'nomer_rascheta', 'tel', 'fio_buhgaltera'], 'string'],
            [['nds', 'pechat'], 'safe'],
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
        $query = Requisite::find();

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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'doljnost_rukovoditelya', $this->doljnost_rukovoditelya])
            ->andFilterWhere(['like', 'fio_polnostyu', $this->fio_polnostyu])
            ->andFilterWhere(['like', 'official_address', $this->official_address])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'kpp', $this->kpp])
            ->andFilterWhere(['like', 'ogrn', $this->ogrn])
            ->andFilterWhere(['like', 'bic', $this->bic])
            ->andFilterWhere(['like', 'kr', $this->kr])
            ->andFilterWhere(['like', 'nomer_rascheta', $this->nomer_rascheta])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'fio_buhgaltera', $this->fio_buhgaltera]);

               
        return $dataProvider;
    }
}
