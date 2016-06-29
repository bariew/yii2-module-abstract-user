<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var bariew\userAbstractModule\models\Company $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?php echo $form->field($model, 'description')->textarea() ?>

    <div class="form-group text-right">
        <?php echo Html::submitButton($model->isNewRecord
                ? Yii::t('modules/user', 'Create') : Yii::t('modules/user', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
