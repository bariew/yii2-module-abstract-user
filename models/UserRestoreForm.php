<?php
/**
 * UserRestoreForm class file.
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
class UserRestoreForm extends AbstractModelExtender
{
    const EVENT_PASSWORD_RESTORE = 'passwordRestore';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'email'],
        ];
    }

    public function send()
    {
        if (!$this->validate()) {
            return false;
        }
        if (!$user = static::findOne(['email' => $this->email])) {
            return true; // we don't let user know email doesn't exist
        }
        $this->generatePasswordResetToken();
        $user->updateAttributes(['password_reset_token' => $this->password_reset_token]);
        $this->trigger(static::EVENT_PASSWORD_RESTORE);
        return true;
    }
}
