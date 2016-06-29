<?php
/**
 * RegisterForm class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\models;
 
/**
 * Form for user registration.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class RegisterForm extends User
{
    public $password_repeat;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['status'], 'filter', 'filter' => function() {
                return \Yii::$app->getModule('user')->params['emailConfirm'] 
                    ? User::STATUS_INACTIVE 
                    : User::STATUS_ACTIVE;
            }],
            [['email', 'username'], 'unique'],
            ['email', 'email'],
            [[static::$loginAttribute, 'password', 'password_repeat'], 'required'],
            [['username', 'password', 'password_repeat'], 'string', 'min' => 2, 'max' => 255],
            ['password_repeat', 'rulePassword', 'message'=>'Incorrect username or password.'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function rulePassword($attribute, $message)
    {
        if ($this->password != $this->$attribute) {
            $this->addError($attribute, $message);
        }
    }

    /**
     * Logs user in after registration.
     * @param boolean $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->isActive()) {
            $loginForm = new LoginForm($this->attributes);
            $loginForm->login(false);
        }
    }
}
