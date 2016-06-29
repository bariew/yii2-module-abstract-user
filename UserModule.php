<?php
/**
 * UserModule class file.
 * @copyright (c) 2016, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule;
use Yii;
use yii\web\Application;

/**
 * Description.
 *
 * Usage:
 * @author Pavel Bariev <bariew@yandex.ru>
 *
 */
class UserModule extends \yii\base\Module
{
    public $params = [
        'emailConfirm' => false,
        'resetTokenExpireSeconds' => 86400 // one day
    ];


    public function init()
    {
        $this->params['menu'] = (!static::hasUser())
            ? [
                  'label'    => 'Login', 
                  'url' => ['/user/default/login']
              ]
            : [
                  'label'    => Yii::$app->user->identity->username,
                  'items' => [
                      ['label'    => 'All users', 'url' => ['/user/user/index']],
                      ['label'    => 'All companies', 'url' => ['/user/company/index']],
                      ['label'    => 'Profile', 'url' => ['/user/default/update']],
                      ['label'    => 'Logout', 'url' => ['/user/default/logout']],
                  ]
              ];
        parent::init();
    }

    /**
     * We just check whether module is installed and user is logged in.
     * @return bool
     */
    public static function hasUser()
    {

        if (!(Yii::$app instanceof Application)) {
            return false;
        }
        try {
            $identityClass = Yii::$app->user->identityClass;
        } catch (\Exception $e) {
            $identityClass = false;
        }

        if (!$identityClass) {
            return false;
        }

        return !Yii::$app->user->isGuest;
    }
}
