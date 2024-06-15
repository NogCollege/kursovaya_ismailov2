<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%userr}}`.
 */
class m240615_142046_create_userr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%userr}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%userr}}');
    }
}
