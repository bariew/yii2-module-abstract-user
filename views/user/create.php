<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bariew\userAbstractModule\models\User $model
 */

$this->title = Yii::t('modules/user', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modules/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
