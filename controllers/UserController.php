<?php
namespace app\controllers;

use app\models\user\form\LoginPasswordForm;
use app\models\user\form\PasswordForm;
use app\models\user\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;


class UserController extends BasicController
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    return $this->goHome();
                },

                'only' => ['login', 'logout', 'first-activate-input-key','change-activate-password'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['change-activate-password'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action, $sd) {
                            $this->getGroupeRole();
                            return  (User::find()->where('(auth_key_first !="" AND auth_key_first IS NOT NULL AND auth_key_first != "0") AND id ='.$this->oUser->id)->exists());

                        }
                    ],
                    [
                        'actions' => ['login','first-activate-input-key'],
                        'roles'=>['?'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','first-activate-input-key',],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Обработка изменения пароля первичной авторизации
     * /user/change-activate-password
     *
     */
    public function actionChangeActivatePassword()
    {
        $model = new PasswordForm();
        if (!Yii::$app->user->isGuest && Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate('user_password')) {
                if ($model->setChangePassword()) {
                    Yii::$app->getSession()->setFlash('success', Yii::$app->params['messages']['user']['success']['firstKeyChange']);
                    return $this->render('@app/views/'.DEFAULT_ROUTE,['user'=>Yii::$app->user,'groupe'=>$this->aGroups]);
                }
            }
            Yii::$app->getSession()->setFlash('error', Yii::$app->params['messages']['user']['error']['password_key']);
            return $this->render('password_form');
        }
        Yii::$app->getSession()->setFlash('error', Yii::$app->params['messages']['request']['error']['basic']);
        return $this->render('login_password_form');
    }

    /**
     * Проверка первичного ключа
     * /user/first-activate-input-key/[key]
     *
     * @param bool $key
     * @return string
     */
    public function actionFirstActivateInputKey($key=FALSE)
    {
        $model = new PasswordForm();
        if (Yii::$app->request->isGet && $key) {
            $model->auth_key_first = $key;
            if ($model->keyLogin($this->sUser_ip)){
                Yii::$app->getSession()->setFlash('success', Yii::$app->params['messages']['user']['success']['firstKey']);
                return $this->render('password_form',['user'=>Yii::$app->user]);
            }
        }
        if (Yii::$app->user->isGuest) {
            Yii::$app->getSession()->setFlash('error', Yii::$app->params['messages']['user']['error']['firstKey']);
            return $this->render('login_password_form');
        } else {
            Yii::$app->getSession()->setFlash('error', Yii::$app->params['messages']['user']['error']['firstKey']);
            return $this->render('@app/views/'.DEFAULT_ROUTE,['user'=>Yii::$app->user,'groupe'=>$this->aGroups]);
        }
    }



    /**
     * Базовая авторизация по паре логин/пароль
     * user/login
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('@app/views/'.DEFAULT_ROUTE,['user'=>Yii::$app->user,'groupe'=>$this->aGroups]);
        }

        $model = new LoginPasswordForm();
        if (Yii::$app->user->isGuest && Yii::$app->request->isPost) {
            //загружаем параметры из формы
            $model->load(Yii::$app->request->post());
            //прогружаем дополнительные параметры
            $model->afterLoad();

            //проверка на блокировку
            if (User::testUserStop($model->id)) {
                Yii::$app->getSession()->setFlash('error', Yii::$app->params['messages']['user']['error']['stop']);
                return $this->render('login_password_form');
            }
            //проверка на ожидание
            if (User::testUserHold($model->id)) {
                Yii::$app->getSession()->setFlash('warning', Yii::$app->params['messages']['user']['error']['hold']);
                return $this->render('login_password_form');
            }

            //авторизация
            if ($model->basicLogin($this->sUser_ip)) {
                Yii::$app->getSession()->setFlash('success', Yii::$app->params['messages']['user']['success']['login']);
                return $this->render('@app/views/'.DEFAULT_ROUTE, ['user'=>Yii::$app->user,'groupe'=>$this->aGroups]);
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::$app->params['messages']['user']['error']['password']);
                return $this->render('login_password_form');
            }
        }

        if (Yii::$app->user->isGuest) {
            return $this->render('login_password_form');
        }

        return $this->goHome();


    }


    /**
     * Разлогинивание пользователя
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->getSession()->setFlash('info', Yii::$app->params['messages']['user']['success']['logout']);
        return $this->render('login_password_form');
    }
}