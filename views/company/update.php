<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bariew\userAbstractModule\models\Company $model
 */

$this->title = Yii::t('modules/user', 'Update Company: {id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('modules/user', 'Company'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('modules/user', 'Update');
?>
<div class="company-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
