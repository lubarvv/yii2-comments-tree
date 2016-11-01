<?php

use app\models\Comment;
use yii\db\Migration;

/**
 * Handles the creation for table `comments`.
 */
class m161011_125928_create_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(
            'comments',
            [
                'id'              => $this->primaryKey(),
                'parentCommentId' => $this->integer(),
                'left'            => $this->integer()->notNull(),
                'right'           => $this->integer()->notNull(),
                'level'           => $this->integer()->notNull(),
                'userName'        => $this->string()->notNull(),
                'text'            => $this->text()->notNull(),
                'createdAt'       => $this->integer()->notNull(),
                'updatedAt'       => $this->integer()->notNull(),
                'isDeleted'       => $this->boolean()->notNull()->defaultValue(false),
            ]
        );

        $this->createIndex('comments_left_right_index', 'comments', ['left', 'right']);
        $this->addForeignKey('fk_comments_parent_comment', 'comments', 'parentCommentId', 'comments', 'id');
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('comments_left_right_index', 'comments');
        $this->dropForeignKey('fk_comments_parent_comment', 'comments');
        $this->dropTable('comments');
    }
}
