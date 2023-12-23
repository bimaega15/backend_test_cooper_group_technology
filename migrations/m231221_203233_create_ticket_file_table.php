<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_file}}`.
 */
class m231221_203233_create_ticket_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_file}}', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer(),
            'file' => $this->text()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addForeignKey(
            'fk-ticket_file-ticket_id',
            'ticket_file',
            'ticket_id',
            'ticket',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ticket_file-ticket_id', 'ticket_file');
        $this->dropTable('{{%ticket_file}}');
    }
}
