<?php

namespace app\models\user;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use app\models\Basic;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $id_lis
 * @property int $status
 * @property string $user_name
 * @property string $user_password
 * @property string $user_email
 * @property string $user_groupe_id
 * @property string $auth_key_first
 * @property string $auth_key_second
 * @property string $auth_key
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_WAIT       = 0;
    const STATUS_BLOCKED    = 5;
    const STATUS_ACTIVE     = 10;


    public $rememberMe = true;  //запомнить и оставить валидацию на 30 дней

    /**
     * Таблица пользователей
     *
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Валидация
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['user_name'], 'unique', 'message' => Yii::$app->params['messages']['user']['error']['login_unique']],
            [['user_email'], 'unique', 'message' => Yii::$app->params['messages']['user']['error']['email_unique']],
            [['user_name', 'user_password', 'user_email'], 'string', 'max' => 255,'message' => Yii::$app->params['messages']['user']['error']['post_long']],
            [['user_email'], 'email','message' => Yii::$app->params['messages']['user']['error']['email_format']],
            [['auth_key_first', 'auth_key_second','auth_key'], 'string', 'max' => 32],
            [['user_groupe_id'],'string'],
            [['status'], 'in', 'range' => array_keys(self::getStatusesArray())],
        ];
    }


    /**
     * Нименование полей
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID пользователя в ЛК',
            'status' => 'Статус пользователя',
            'user_name' => 'Логин',
            'user_password' => 'Пароль',
            'user_email' => 'Email',
            'user_groupe_id'   =>'Группы пользователя',
            'auth_key'  =>  'Ключ системной аутентификации',
            'auth_key_first' => 'Первичный ключ авторизации',
            'auth_key_second' => 'Вторичный ключ авторизации',
        ];
    }

    /**
     * Получаем список статусов
     *
     * @return mixed
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    /**
     * Статусы пользователей
     *
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_WAIT => 'Ожидает подтверждения',
        ];
    }

    /**
     * Поиск по имени пользователя
     *
     * @param $username
     * @return null|static
     */
    public static function findByUsername($user_name)
    {
        return static::findOne(['user_name' => $user_name, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Проверяем пароль
     *
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        if ($this->user_password == md5($password)) {
            return TRUE;
        } else {
            return FALSE;
        }
        //return Yii::$app->security->validatePassword($password, $this->user_password);
    }

    /**
     * Проверяем первичный ключ
     *
     * @param $key
     * @return bool
     */
    public function validateFirstKey($key)
    {
        if ($this->auth_key_first == $key) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Устанавливаем хэш пароля
     *
     * @param $user_password
     * @throws \yii\base\Exception
     */
    public function setUserPassword($user_password)
    {
        $this->user_password = md5($user_password);

    }

    /**
     * Ключ автоматический аутентификации
     *
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /**
     * Находит экземпляр identity class используя ID пользователя
     *
     * @param int|string $id
     * @return void|IdentityInterface
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * Этот метод находит экземпляр identity class, используя токен доступа
     *
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    /**
     * Возвращает ID пользователя
     *
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }


    /**
     * Внутренний ключ авторизации
     *
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Перед записью
     * Перегенерация ключа
     *
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
                if (empty($this->user_groupe_id)) {
                    $this->user_groupe_id = 10; //default
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Пользователь ожидает проверки
     *
     * @return bool
     */
    public static function testUserHold($user_id)
    {
        if (User::findOne(['id' => $user_id, 'status'=>User::STATUS_WAIT])) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Пользователь заблокирован
     *
     * @return bool
     */
    public static function testUserStop($user_id)
    {
        if (User::findOne(['id' => $user_id, 'status'=>User::STATUS_BLOCKED])) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Пользователь активен
     *
     * @return bool
     */
    public static function testUserActive($user_id)
    {
        if (User::findOne(['id' => $user_id, 'status'=>User::STATUS_ACTIVE])) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Удаляет первичный ключ
     */
    public function deleteAuthFirstKey()
    {
        $this->auth_key_first = '';
    }

    /**
     * Генерация первичного ключа авторизации
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public static function generateAuthFirstKey()
    {
        return Yii::$app->security->generateRandomString(8);
    }

    /**
     * Получаем разименновыный массив груп пользователей
     *
     * @param bool $user_id
     * @return array|bool
     */
    public static function getGroupeUser($user_id=FALSE)
    {
        if ($user_id && $model = User::findOne($user_id)) {
            if ($model->user_groupe_id) {
                if (substr_count($model->user_groupe_id, ',') >= 1) {
                    $aGroups = explode(',', $model->user_groupe_id);
                } else {
                    $aGroups[] = $model->user_groupe_id;
                }
                return $aGroups;
            }
        }
        return FALSE;
    }


    /**
     * Возвращает ID пользователя по его ключу
     *
     * @param bool $key
     * @return bool|int
     */
    public static function getApiAuth($key=FALSE)
    {
        if ($key && !empty($key) && User::find()->where(['auth_key_second'=>$key])->exists()) {
            $model = User::findOne(['auth_key_second'=>$key]);
            return $model->id;
        }
        return FALSE;
    }

    /**
     * Получаем актуальную дату время
     *
     * @return string
     */
    public static function getNowDateTime()
    {
        $dateFile = new \DateTime();
        return $dateFile->format('Y-m-d H:i:s');
    }

    public static function getNowDate()
    {
        $dateFile = new \DateTime();
        return $dateFile->format('Y-m-d');
    }
}
