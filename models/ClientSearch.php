<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Client;

/**
 * ClientSearch represents the model behind the search form about `Client`.
 */
class ClientSearch extends Client
{
    
    public $contract;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract'],'integer'],
            [['name', 'doljnost_rukovoditelya', 'fio_polnostyu', 'official_address', 'bank_name', 'inn', 'kpp', 'ogrn', 'bic', 'kr', 'nomer_rascheta', 'tel', 'email', 'doc', 'mailing_address', 'code', 'contact'], 'string'],
            [['nds', 'organization_id'], 'safe'],
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
        
        //echo $this->contract; die;
        
        $query = Client::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $query->joinWith(['user']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'client.name', $this->name])
            ->andFilterWhere(['like', 'doljnost_rukovoditelya', $this->doljnost_rukovoditelya])
            ->andFilterWhere(['like', 'fio_polnostyu', $this->fio_polnostyu])
            ->andFilterWhere(['like', 'official_address', $this->official_address])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'kpp', $this->kpp])
            ->andFilterWhere(['like', 'ogrn', $this->ogrn])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'bic', $this->bic])
            ->andFilterWhere(['like', 'kr', $this->kr])
            ->andFilterWhere(['like', 'nomer_rascheta', $this->nomer_rascheta])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'doc', $this->doc])
            ->andFilterWhere(['like', 'mailing_address', $this->mailing_address])
            ->andFilterWhere(['like', 'code', $this->code]);
        
        if(isset($this->contract) && ($this->contract == 1 or $this->contract == 0)){
            $query->andFilterWhere(['like', 'contract', $this->contract]);
            $query->andFilterWhere(['like', 'contract_orig', 0]);
        }elseif(isset($this->contract) && ($this->contract == 2)){
            $query->andFilterWhere(['like', 'contract', 1]);
            $query->andFilterWhere(['like', 'contract_orig', 1]);            
        }

        if(Yii::$app->user->identity->can('client_view_all') == false){
            $query->andWhere(['like', 'users', '"'.Yii::$app->user->getId().'"']);
        }

                 
        return $dataProvider;
    }
}
