<?php
/**
 * LoginForm class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\models;

use Yii;
use yii\base\Model;
 
/**
 * For user login form.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 *
 * @property string $loginAttribute
 */
class LoginForm extends Model
{
    public $username;
    public $email;
    public $phone;
    public $password;
    public $rememberMe = true;

    protected $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [[$this->loginAttribute, 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['email', 'email'],
            [['username', 'phone'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'active'    => Yii::t('modules/user', 'Active'),
            'username'    => Yii::t('modules/user', 'Username'),
            'rememberMe'    => Yii::t('modules/user', 'Remember me'),
            'password'    => Yii::t('modules/user', 'Password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
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
            /** @var User $class */
            $class = Yii::$app->user->identityClass;
            $this->_user = $class::findByLogin($this->attributes);
        }

        return $this->_user;
    }

    /**
     * Attribute for login name (email, username, phone)
     * @return mixed
     */
    public function getLoginAttribute()
    {
        $class = Yii::$app->user->identityClass;
        return $class::$loginAttribute;
    }
}
