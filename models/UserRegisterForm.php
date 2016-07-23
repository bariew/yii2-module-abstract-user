<?php
/**
 * RegisterForm class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\models;
use bariew\abstractModule\models\AbstractModelExtender;

/**
 * Form for user registration.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 *
 * @mixin User
 */
class UserRegisterForm extends AbstractModelExtender
{
    public $password_repeat;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'status' => [['status'], 'filter', 'filter' => function() {
                return \Yii::$app->getModule('user')->params['emailConfirm'] 
                    ? User::STATUS_INACTIVE
                    : User::STATUS_ACTIVE;
            }],
            ['password_repeat', 'compare', 'compareAttribute'=> 'password'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->isActive()) {
            \Yii::$app->user->login($this);
        }
    }
}
