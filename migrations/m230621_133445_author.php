<?php

use yii\db\Migration;

/**
 * Class m230621_133445_author
 */
class m230621_133445_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('author', true) === null) {
            $this->createTable('author', [
                'id' => $this->primaryKey(),
                'full_name' => $this->string(255)->notNull(),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->getTableSchema('author', true) !== null) {
            $this->dropTable('author');
        }
    }
}
