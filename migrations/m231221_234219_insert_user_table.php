<?php

use yii\db\Migration;

/**
 * Class m231221_234219_insert_user_table
 */
class m231221_234219_insert_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'username' => 'admin',
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'created_at' => Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s'),
            'updated_at' => Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('user', ['username' => 'admin']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231221_234219_insert_user_table cannot be reverted.\n";

        return false;
    }
    */
}
