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
 * For searching users.
 * 
 *
 * @mixin User
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class UserSearch extends AbstractModelExtender
{
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'email', 'password', 'username', 'owner_id', 'role'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'role' => \Yii::t('modules/user', 'Role'),
        ]);
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
        if ($this->role) {
            $query->andWhere(['id' => \Yii::$app->authManager->getUserIdsByRole($this->role)]);
        }
        return $dataProvider;
    }
}
