<?php
/**
 * DefaultController class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userAbstractModule\controllers;

use bariew\abstractModule\controllers\AbstractModelController;
use bariew\userAbstractModule\models\Auth;
use bariew\userAbstractModule\models\UserLoginForm;
use bariew\userAbstractModule\models\UserRegisterForm;
use bariew\userAbstractModule\models\UserRestoreForm;
use yii\authclient\AuthAction;
use bariew\userAbstractModule\models\User;
use yii\authclient\BaseOAuth;
use Yii;

/**
 * Default controller for all users.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class DefaultController extends AbstractModelController
{
    /**
     * Url for redirecting after login
     * @return null
     */
    public function getLoginRedirect()
    {
        return ["/"];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'authCallback'],
                'successUrl' => Yii::$app->urlManager->createAbsoluteUrl(['/user/default/login'])
            ],
        ];
    }

    /**
     * @param BaseOAuth $client
     */
    public function authCallback(BaseOAuth $client)
    {
        $user = Auth::clientUser($client);
        /** @var UserLoginForm $model */
        $model = static::getModelClass('UserLoginForm', $user->attributes);
        $model->login(false);
    }

    /**
     * Renders login form.
     * @param string $view
     * @param bool $partial
     * @return string view.
     */
    public function actionLogin($view = 'login', $partial = false)
    {
        /** @var UserLoginForm $model */
        $model = static::getModelClass('UserLoginForm', []);
        if (!\Yii::$app->user->isGuest) {
            $this->redirect($this->getLoginRedirect());
        } else if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->redirect($this->getLoginRedirect());
        }else if (\Yii::$app->request->isAjax || $partial) {
            return $this->renderAjax($view, compact('model'));
        } else {
            return $this->render($view, compact('model'));
        }
    }

    /**
     * Logs user out and redirects to homepage.
     * @return string view.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->goHome();
    }
    
    /**
     * Registers user.
     * @return string view.
     */
    public function actionRegister()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        /** @var UserRegisterForm $model */
        $model = static::getModelClass('UserRegisterForm', []);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", Yii::$app->user->isGuest
                ? Yii::t('modules/user', 'Please confirm registration email!')
                : Yii::t('modules/user', 'Registration completed!')
            );
        }
        if (($url = $this->getLoginRedirect()) && !Yii::$app->user->isGuest) {
            $this->redirect($url);
        } else {
            $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
            return $this->$render('register', compact('model'));
        }
    }
    
    /**
     * For registration confirmation by email auth link.
     * @param string $auth_key user authorization key.
     * @return string view.
     */
    public function actionConfirm($auth_key)
    {
        $model = $this->findModel(true);
        /**
         * @var User $user
         */
        if ($auth_key && ($user = $model::findOne(compact('auth_key')))) {
            Yii::$app->session->setFlash("success", Yii::t('modules/user',
                "You have successfully completed your registration."));
            Yii::$app->user->login($user);
            $user->activate();
            $this->redirect($this->getLoginRedirect());
        }else{
            Yii::$app->session->setFlash("error", Yii::t('modules/user', "Your auth link is invalid."));
            $this->goHome();
        }
    }


    /**
     * For registration confirmation by email auth link.
     * @param string|bool $token user authorization key.
     * @return string view.
     */
    public function actionRestore($token = false)
    {
        $user = $this->findModel(true);
        /** @var UserRestoreForm $model */
        $model = static::getModelClass('UserRestoreForm', []);
        if ($restoredUser = $user::findByPasswordResetToken($token)) {
            Yii::$app->user->login($restoredUser);
            Yii::$app->session->setFlash("success", Yii::t('modules/user', "You can change your password now."));
            $this->redirect($this->getLoginRedirect());
        } else if ($token) {
            Yii::$app->session->setFlash("error", Yii::t('modules/user', "Token is invalid."));
            $this->goHome();
        } else if ($model->load(Yii::$app->request->post()) && $model->send()) {
            Yii::$app->session->setFlash("success", Yii::t('modules/user', "Password reset link has been sent to your email."));
            $this->goHome();
        } else {
            $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
            return $this->$render('restore', compact('model'));
        }
    }
    
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdate()
    {
        if (!$model = $this->findModel()) {
            Yii::$app->session->setFlash("error", Yii::t('modules/user', "You are not logged in."));
            $this->goHome();
        } else if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", Yii::t('modules/user', "Changes has been saved."));
            $this->refresh();
        } else {
            return $this->render('update', ['model' => $model,]);
        }
    }

    /**
     * Finds user model.
     * @param bool $id // if true will return new User
     * @param bool $search
     * @return User
     */
    public function findModel($id = false, $search = false)
    {
        $class = \Yii::$app->user->identityClass;
        return $id === false ? Yii::$app->user->identity : new $class();
    }
}