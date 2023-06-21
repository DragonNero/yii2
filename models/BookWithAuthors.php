<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property array $author_ids
 */
class BookWithAuthors extends Book
{
    /**
     * @var array IDs of the authors
     */
    public $author_ids = [];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['author_ids', 'each', 'rule' => [
                    'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'
                ]
            ],
        ]);
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'author_ids' => 'Authors',
        ]);
    }

    public function loadAuthors()
    {
        $this->author_ids = [];
        if (!empty($this->id)) {
            $rows = BookAuthor::find()
                ->select(['author_id'])
                ->where(['book_id' => $this->id])
                ->asArray()
                ->all();
            foreach ($rows as $row) {
                $this->author_ids[] = $row['author_id'];
            }
        }
    }

    public function getSelectedAuthorsList()
    {
        if (empty($this->author_ids)) {
            $this->loadAuthors();
        }
        $authors = Author::find()
            ->where(['id' => $this->author_ids])
            ->all();

        return implode(', ', array_column($authors, 'full_name'));
    }

    public function saveAuthors()
    {
        BookAuthor::deleteAll(['book_id' => $this->id]);
        if (is_array($this->author_ids)) {
            foreach ($this->author_ids as $author_id) {
                $pc = new BookAuthor();
                $pc->book_id = $this->id;
                $pc->author_id = $author_id;
                $pc->save();
            }
        }
    }

    public function saveImage()
    {
        $this->cover_picture = UploadedFile::getInstance($this, 'cover_picture');
        if ($this->validate() && $this->cover_picture) {
            $this->cover_picture->saveAs(Yii::getAlias('@webroot') . '/uploads/' . $this->cover_picture->baseName . '.' . $this->cover_picture->extension);
            return true;
        } else {
            return false;
        }
    }
}
