<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var bariew\userAbstractModule\models\LoginForm $model
 */
$this->title = Yii::t('modules/user', 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    
    <?php echo $form->field($model, 'email') ?>
    
    <?php echo $form->field($model, 'username') ?>
        
    <?php echo $form->field($model, 'password')->passwordInput() ?>
    
    <?php echo $form->field($model, 'password_repeat')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?php echo Html::submitButton(
                \Yii::t('modules/user', 'Register'), 
                ['class' => 'btn btn-primary', 'name' => 'register-button']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
