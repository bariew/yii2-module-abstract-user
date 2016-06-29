<?php
/**
 * UserSearch class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\models;

use bariew\abstractAbstractModule\models\AbstractModelExtender;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * For searchin users.
 * 
 * 
 * @example
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class UserSearch extends AbstractModelExtender
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'email', 'password', 'username', 'owner_id'], 'safe'],
            [['status'], 'integer'],
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
     * Searches users.
     * @param array $params search query data
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = parent::search();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere([
                'like', 'DATE_FORMAT(FROM_UNIXTIME(created_at), "%Y-%m-%d")', $this->created_at
            ])->andFilterWhere([
                'like', 'DATE_FORMAT(FROM_UNIXTIME(updated_at), "%Y-%m-%d")', $this->updated_at
            ])
            ;

        return $dataProvider;
    }
}
