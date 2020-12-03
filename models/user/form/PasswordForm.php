<?php
namespace app\models\user\form;

use Yii;
use app\models\Basic;
use app\models\user\User;
use app\components\jobs\Worker;
use yii\db\ActiveRecord;

/**
* Первичная авторизация пользователя
*
* Class BasicLogin
* @package app\models\form
*/
class PasswordForm extends Basic
{
    /**
     * Первичный ключ
     *
     * @var
     */
    public $auth_key_first;

    /**
     * Пароль пользователя
     *
     * @var
     */
    public $user_password;


    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['auth_key_first'], 'string', 'max' => 32],
            [['user_password'], 'match', 'pattern' => '#^[\w_-]+$#is'],
            ['user_password','required'],
        ];
    }

    /**
     * Авторизация по ключу
     *
     * @return bool
     */
    public function keyLogin($ip=FALSE)
    {
        $identity = User::findOne(['auth_key_first' => $this->auth_key_first]);

        if ($identity && $identity->validateFirstKey($this->auth_key_first)) {
            Yii::$app->user->login($identity,Yii::$app->params['time_auth']);
            Yii::$app->queue->push(new Worker([
                'user_id' => $identity->id,
                'user_ip' => $ip,
                'user_type_auth' => 'auth_key_first',
                'info' => 'Авторизация по первичному ключу',
                'session_key' => $identity->auth_key,
            ]));
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Меняем пароль текущему пользователю
     *
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function setChangePassword()
    {
        $identity = User::findOne(['id' => Yii::$app->user->identity->id]);
        if ($identity) {
            $identity->setUserPassword($this->user_password);
            $identity->deleteAuthFirstKey();
            $identity->update(0);
            return TRUE;
        }
        return FALSE;
    }


}