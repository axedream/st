<?php

use yii\db\Migration;

/**
 * Class m180618_035513_user_add
 */
class m180618_035513_user_add extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user%}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'user_name' => $this->string(),
            'user_password' => $this->string(),
            'user_email' => $this->string(),
            'user_groupe_id' => $this->string(500),
            'auth_key_first' => $this->string(32),
            'auth_key_second' => $this->string(32),
            'auth_key'=> $this->string(32),
        ], $tableOptions);


        $model = new \app\models\user\User;
        $model->user_name = 'admin';
        $model->setUserPassword('tomas4321');
        $model->user_email = 'axe_dream@list.ru';
        $model->status = 10;
        $model->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user%}}');
    }
}
