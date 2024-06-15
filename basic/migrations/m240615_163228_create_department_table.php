<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%department}}`.
 */
class m240615_163228_create_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        // Add department_id column to user table
        $this->addColumn('{{%user}}', 'department_id', $this->integer());

        // Create index for column `department_id`
        $this->createIndex(
            '{{%idx-user-department_id}}',
            '{{%user}}',
            'department_id'
        );

        // Add foreign key for table `department`
        $this->addForeignKey(
            '{{%fk-user-department_id}}',
            '{{%user}}',
            'department_id',
            '{{%department}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `department`
        $this->dropForeignKey(
            '{{%fk-user-department_id}}',
            '{{%user}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-user-department_id}}',
            '{{%user}}'
        );

        $this->dropColumn('{{%user}}', 'department_id');

        $this->dropTable('{{%department}}');
    }
}
