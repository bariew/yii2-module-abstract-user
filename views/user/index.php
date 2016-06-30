<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var bariew\userAbstractModule\models\UserSearch $searchModel
 */

$this->title = Yii::t('modules/user', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1>
        <?php echo Html::encode($this->title) ?>
        <?php echo Html::a(Yii::t('modules/user', 'Create User'), ['create'], ['class' => 'btn btn-success pull-right']) ?>
    </h1>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            \bariew\yii2Tools\helpers\GridHelper::listFormat($searchModel, 'role'),
            'email:email',
            'username',
            \bariew\yii2Tools\helpers\GridHelper::dateFormat($searchModel, 'created_at'),
            ['class' => 'yii\grid\ActionColumn', 'options' => ['style' => 'width:70px']],
        ],
    ]); ?>

</div>
