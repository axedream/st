<?php

namespace app\models\user;

use Yii;
use app\models\Basic;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "user_groupe".
 *
 * @property int $id
 * @property string $groupe_name
 * @property string $rules
 */
class UserGroupe extends ActiveRecord
{

    /**
     * Таблица группы пользователей
     *
     * @return string
     */
    public static function tableName()
    {
        return 'user_groupe';
    }

    /**
     * Валидация полей
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['rules'], 'string'],
            [['groupe_name'], 'string', 'max' => 255],
        ];
    }


    /**
     * Наименование полей
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groupe_name' => 'Имя группы',
            'rules' => 'Правила',
        ];
    }
}
