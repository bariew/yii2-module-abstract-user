<?php
/**
 * UserSearch class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\models;

use bariew\abstractModule\models\AbstractModelExtender;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * For searching companies.
 * 
 * 
 * @example
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class CompanySearch extends AbstractModelExtender
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'owner_id'], 'safe'],
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

        $query->andWhere(['owner_id' => $this->owner_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ;

        return $dataProvider;
    }
}
