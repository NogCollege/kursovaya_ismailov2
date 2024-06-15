<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m240615_163309_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'due_date' => $this->dateTime()->notNull(),
            'status' => "ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending'",
            'assigned_to' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'department_id' => $this->integer()->notNull(),
        ]);

        // Create index for column `assigned_to`
        $this->createIndex(
            '{{%idx-task-assigned_to}}',
            '{{%task}}',
            'assigned_to'
        );

        // Add foreign key for table `user` (assigned_to)
        $this->addForeignKey(
            '{{%fk-task-assigned_to}}',
            '{{%task}}',
            'assigned_to',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Create index for column `created_by`
        $this->createIndex(
            '{{%idx-task-created_by}}',
            '{{%task}}',
            'created_by'
        );

        // Add foreign key for table `user` (created_by)
        $this->addForeignKey(
            '{{%fk-task-created_by}}',
            '{{%task}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Create index for column `department_id`
        $this->createIndex(
            '{{%idx-task-department_id}}',
            '{{%task}}',
            'department_id'
        );

        // Add foreign key for table `department`
        $this->addForeignKey(
            '{{%fk-task-department_id}}',
            '{{%task}}',
            'department_id',
            '{{%department}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user` (assigned_to)
        $this->dropForeignKey(
            '{{%fk-task-assigned_to}}',
            '{{%task}}'
        );

        // drops index for column `assigned_to`
        $this->dropIndex(
            '{{%idx-task-assigned_to}}',
            '{{%task}}'
        );

        // drops foreign key for table `user` (created_by)
        $this->dropForeignKey(
            '{{%fk-task-created_by}}',
            '{{%task}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-task-created_by}}',
            '{{%task}}'
        );

        // drops foreign key for table `department`
        $this->dropForeignKey(
            '{{%fk-task-department_id}}',
            '{{%task}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-task-department_id}}',
            '{{%task}}'
        );

        $this->dropTable('{{%task}}');
    }
}
