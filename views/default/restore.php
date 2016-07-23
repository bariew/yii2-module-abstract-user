<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var bariew\userAbstractModule\models\UserRestoreForm $model
 */
$this->title = Yii::t('modules/user', 'Password restore');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?php echo $form->field($model, 'email') ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?php echo Html::submitButton(Yii::t('modules/user', 'Restore'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
