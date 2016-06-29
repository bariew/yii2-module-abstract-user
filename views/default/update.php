<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bariew\userAbstractModule\models\User $model
 */

$this->title = Yii::t('modules/user', 'My Profile');
?>
<div class="user-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
