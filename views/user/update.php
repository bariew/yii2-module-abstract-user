<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bariew\userAbstractModule\models\User $model
 */

$this->title = Yii::t('modules/user', 'Update User: {id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('modules/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('modules/user', 'Update');
?>
<div class="user-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
