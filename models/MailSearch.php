<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mail;

/**
 * MailSearch represents the model behind the search form about `Mail`.
 */
class MailSearch extends Mail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'organization_name', 'from', 'to', 'track','information'], 'string'],
            [['user_id','when_send', 'when_receive'], 'safe'],
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
        $query = Mail::find();

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

        $query->joinWith(['client']);
        $query->joinWith(['user']);

        

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'from', $this->from])
            ->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'track', $this->track])        
            
            ->andFilterWhere(['=','mail.user_id', $this->user_id])
            ->andFilterWhere(['like','mail.information', $this->information])
            ->andFilterWhere(['=','mail.when_send', $this->when_send]);
        

        if(\Yii::$app->controller->id == 'mail'){
            $query->andFilterWhere(['like', 'organization_name', $this->organization_name]);
        } else{
            $query->andFilterWhere(['like', 'client.name', $this->organization_name]);
        }

        if(\Yii::$app->user->identity->can('mail_view_all') == false && \Yii::$app->user->identity->isSuperAdmin() == false){
            $query->andWhere(['mail.user_id' => \Yii::$app->user->getId()]);
        }

       
        return $dataProvider;
    }
}
