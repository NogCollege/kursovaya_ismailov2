<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%person}}`.
 */
class m240615_152414_create_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%person}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'full_name' => $this->string(255)->notNull(),
            'passport' => $this->string(255)->notNull(),
            'department' => $this->string(255)->notNull(),
            'position' => $this->string(255)->notNull(),
            'duties' => $this->text()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-person-user_id}}',
            '{{%person}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-person-user_id}}',
            '{{%person}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-person-user_id}}',
            '{{%person}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-person-user_id}}',
            '{{%person}}'
        );

        $this->dropTable('{{%person}}');
    }
}
