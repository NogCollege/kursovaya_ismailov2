<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%schedule}}`.
 */
class m240615_163348_create_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%schedule}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'start_time' => $this->dateTime()->notNull(),
            'end_time' => $this->dateTime()->notNull(),
            'description' => $this->text(),
        ]);

        // Create index for column `user_id`
        $this->createIndex(
            '{{%idx-schedule-user_id}}',
            '{{%schedule}}',
            'user_id'
        );

        // Add foreign key for table `user`
        $this->addForeignKey(
            '{{%fk-schedule-user_id}}',
            '{{%schedule}}',
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
        // drops foreign key for table `user`
        $this->dropForeignKey(
            '{{%fk-schedule-user_id}}',
            '{{%schedule}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-schedule-user_id}}',
            '{{%schedule}}'
        );

        $this->dropTable('{{%schedule}}');
    }
}
