<?php
/**
 * User class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\models;

use bariew\abstractModule\models\AbstractModel;
use yii\web\IdentityInterface;
use Yii;
 
/**
 * Application user model.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 * 
 * @property integer $id
 * @property string $username
 * @property integer $owner_id
 * @property string $auth_key
 * @property string $api_key
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Company $company
 */
class User extends AbstractModel implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 10;
    public static $loginAttribute = 'username';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => static::STATUS_ACTIVE],
            ['owner_id', 'safe', 'when' => function($model){ return !$model->owner_id;}],
            ['username', 'filter', 'filter' => 'trim'],
            [[static::$loginAttribute, 'password'], 'required'],
            [['email', 'username', 'api_key'], 'unique'],
            [['username', 'password'], 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'        => Yii::t('modules/user', 'Email'),
            'username'     => Yii::t('modules/user', 'Login'),
            'owner_id'     => Yii::t('modules/user', 'Owner ID'),
            'auth_key'     => Yii::t('modules/user', 'Auth key'),
            'api_key'      => Yii::t('modules/user', 'Api key'),
        ];
    }

    /**
     * gets all available user status list
     * @return array statuses
     */
    public static function statusList()
    {
        return [
            static::STATUS_INACTIVE => Yii::t('modules/user', 'Deactivated'),
            static::STATUS_ACTIVE   => Yii::t('modules/user', 'Active')
        ];
    }

    /**
     * Available company list
     * @return array
     */
    public function companyList()
    {
        $class = Company::childClass();
        return $class::find()->select('title')->indexBy('id')->column();
    }

    /**
     * return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return static::hasOne(Company::childClass(), ['id' => 'owner_id']);
    }

    /**
     * Gets model readable status name.
     * @return string
     */
    public function getStatusName()
    {
        return static::statusList()[$this->status];
    }

    /**
     * Activates user.
     * @return boolean
     */
    public function activate()
    {
        return $this->updateAttributes([
            'status' => static::STATUS_ACTIVE,
            'auth_key' => null
        ]);
    }
    
    /**
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == static::STATUS_ACTIVE;
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($auth_key, $type = NULL)
    {
        static::findOne(compact('auth_key'));
    }

    /**
     * Finds user by username
     *
     * @param  string $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::findOne([
            static::$loginAttribute => $login,
            'status' => static::STATUS_ACTIVE
        ]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire    = \Yii::$app->getModule('user')->params['resetTokenExpireSeconds'];
        $parts     = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => static::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password original password.
     * @return string hashed password.
     */
    public function generatePassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        return $this->auth_key = md5(Yii::$app->security->generateRandomKey());
    }

    public function generateApiKey()
    {
        return $this->api_key = md5(Yii::$app->security->generateRandomKey());
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = md5(Yii::$app->security->generateRandomKey()) . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert && !$this->api_key) {
            $this->generateApiKey();
        }
        if ($insert && !$this->auth_key) {
            $this->generateAuthKey();
        }
        if ($insert || $this->isAttributeChanged('password')) {
            $this->password = $this->generatePassword($this['password']);
        }
        return true;
    }

    /**
     * Finds all users with role
     * @param $role
     * @return \yii\db\ActiveQuery
     */
    public function searchForRole($role)
    {
        return $this->search()->andWhere([
            'id' => Yii::$app->authManager->getUserIdsByRole($role),
        ]);
    }
}
