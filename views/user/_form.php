<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var bariew\userAbstractModule\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if(!$model->owner_id) : ?>
        <?= $form->field($model, 'owner_id')->dropDownList($model->companyList()) ?>
    <?php endif; ?>

    <?php echo $form->field($model, 'status')->dropDownList($model->statusList()) ?>

    <?php echo $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

    <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>

    <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <div class="form-group text-right">
        <?php echo Html::submitButton($model->isNewRecord
                ? Yii::t('modules/user', 'Create') : Yii::t('modules/user', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
