<?php

use yii\db\Migration;

/**
 * Class m230621_143344_book_author
 */
class m230621_143344_book_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('book_author', true) === null) {
            $this->createTable('book_author', [
                'id' => $this->primaryKey(),
                'book_id' => $this->integer()->notNull(),
                'author_id' => $this->integer()->notNull(),
            ]);

            $this->addForeignKey(
                'fk-book_author-book_id',
                'book_author',
                'book_id',
                'book',
                'id',
                'CASCADE'
            );

            $this->addForeignKey(
                'fk-book-author_id',
                'book_author',
                'author_id',
                'author',
                'id',
                'CASCADE'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->getTableSchema('book_author', true) !== null) {
            $this->dropTable('book_author');
        }
    }
}
