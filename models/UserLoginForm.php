<?php
/**
 * UserLoginForm class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\models;

use bariew\abstractModule\models\AbstractModelExtender;
use Yii;

/**
 * For user login form.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 *
 * @property string $loginAttribute
 *
 * @mixin User
 */
class UserLoginForm extends AbstractModelExtender
{
    public $rememberMe = true;

    protected $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [[static::$loginAttribute, 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['email', 'email'],
            [['username'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'rememberMe'    => Yii::t('modules/user', 'Remember me'),
        ]);
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @param bool $validate
     * @return boolean whether the user is logged in successfully
     */
    public function login($validate = true)
    {
        if (!$validate || $this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $parentClass = get_parent_class();
            $this->_user = $parentClass::findByLogin($this->attributes);
        }

        return $this->_user;
    }
}
