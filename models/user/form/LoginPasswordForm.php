<?php

namespace app\models\user\form;

use Yii;
use app\models\Basic;
use app\models\user\User;
use yii\db\ActiveRecord;
use yii\validators\EmailValidator;
use app\components\jobs\Worker;
/**
 * Базовая авторизация пользователя
 *
 * Class BasicLogin
 * @package app\models\form
 */
class LoginPasswordForm extends Basic
{
    /**
     * ID Пользователя
     *
     * @var
     */
    public $id;

    /**
     * Имя пользователя
     *
     * @var
     */
    public $user_name;

    /**
     * Пароль пользователя
     *
     * @var
     */
    public $user_password;

    /**
     * Почта пользователя
     *
     * @var
     */
    public $user_email;

    /**
     * Экземпляр класса модели пользователя
     *
     * @var
     */
    public $identity;

    /**
     * Функция инциализации после загрузки (можно активировать в ручную)
     */
    public function afterLoad()
    {

        if (!empty($this->user_name)) {
            $validator = new EmailValidator();
            if ($validator->validate($this->user_name)) {
                $this->identity = User::findOne(['user_email' => $this->user_name]);
            } else {
                $this->identity = User::findOne(['user_name' => $this->user_name]);
            }
        }
        $this->user_email = ($this->identity) ? $this->identity->user_email : FALSE;
        $this->id = ($this->identity) ? $this->identity->id : FALSE;
    }

    /**
     * Валидация формы
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['user_name', 'user_password', 'user_email'], 'string', 'max' => 255,],
        ];
    }

    /**
     * Базовая авторизация
     *
     * @return bool
     */
    public function basicLogin($ip=FALSE)
    {
        if (User::testUserActive($this->id)) {
            $identity = User::findOne(['id' => $this->id]);
            if ($identity && $identity->validatePassword($this->user_password)) {

                if (Yii::$app->user->login($identity,Yii::$app->params['time_auth'])) {
                    $identity->deleteAuthFirstKey();
                    $identity->update(0);
                }
                return TRUE;
            }
        }
        return FALSE;
    }



}