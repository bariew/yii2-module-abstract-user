<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bariew\userAbstractModule\models\Company $model
 */

$this->title = Yii::t('modules/user', 'Create Company');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modules/user', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
