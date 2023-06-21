<?php

use yii\db\Migration;

/**
 * Class m230621_133854_book
 */
class m230621_133854_book extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('book', true) === null) {
            $this->createTable('book', [
                'id' => $this->primaryKey(),
                'title' => $this->string(255)->notNull()->unique(),
                'year' => $this->integer(4)->notNull(),
                'isbn' => $this->string(20)->notNull(),
                'cover_picture' => $this->string(255),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->getTableSchema('book', true) !== null) {
            $this->dropTable('book');
        }
    }
}
