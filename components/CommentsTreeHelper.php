<?php

namespace app\components;

use app\models\Comment;
use yii\helpers\Url;

class CommentsTreeHelper
{
    /**
     * Returns 1-level comment (aka roots)
     *
     * @return array
     */
    public static function getOneLevel()
    {
        $root             = self::getRoot();
        $oneLevelComments = $root->children(1)->all();

        return self::mapCommentsList($oneLevelComments);
    }

    /**
     * Returns root comment
     *
     * @return Comment
     */
    private static function getRoot()
    {
        $root = Comment::find()->roots()->one();
        if(!$root) {
            $root = new Comment(['userName' => 'root', 'text' => 'root']);
            $root->setScenario('create');
            $root->makeRoot();
        }

        return $root;
    }

    /**
     * Returns subtree of comment with id $commentId
     *
     * @param $commentId
     *
     * @return array
     */
    public static function getChildren($commentId)
    {
        /** @var Comment $comment */
        $comment = Comment::findOne($commentId);
        if ($comment === null) {
            return [];
        }

        $childrenComments = $comment->children()->all();

        return self::mapCommentsList($childrenComments);
    }


    /**
     * @param $data
     *
     * @return bool
     */
    public static function create($data)
    {
        $comment = new Comment();
        $comment->setScenario('create');

        if(!$comment->load($data)) {
            return false;
        }

        if($comment->parentCommentId == 0) {
            $comment->parentCommentId = self::getRoot()->id;
        }

        if (!$comment->validate()) {
            return false;
        }

        /** @var Comment $parentComment */
        $parentComment = Comment::findOne($comment->parentCommentId);
        if (!$parentComment) {
            return false;
        }

        $comment->appendTo($parentComment);

        return self::mapComment($comment);
    }

    /**
     * @param $id
     * @param $data
     *
     * @return array|bool
     */
    public static function update($id, $data)
    {
        /** @var Comment $comment */
        $comment = Comment::findOne($id);
        if ($comment->load($data) && $comment->validate()) {
            $comment->save();

            return self::mapComment($comment);
        }

        return false;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public static function delete($id)
    {
        /** @var Comment $comment */
        $comment = Comment::findOne($id);
        if (!$comment) {
            return false;
        }

        $comment->isDeleted = true;
        $comment->save();

        return true;
    }


    /**
     * Map comment AR to array for ajax-response
     *
     * @param Comment $comment
     *
     * @return array
     */
    private static function mapComment(Comment $comment)
    {
        $commentArray = [
            'id'              => $comment->id,
            'parentCommentId' => $comment->parentCommentId,
            'level'           => $comment->level,
            'username'        => $comment->userName,
            'text'            => !$comment->isDeleted ? $comment->text : 'Comment has been deleted',
            'deleteUrl'       => Url::to(['comments/delete', 'id' => $comment->id]),
            'updateUrl'       => Url::to(['comments/update', 'id' => $comment->id]),
            'isDeleted'       => $comment->isDeleted,
        ];

        if ($comment->level == 1) {
            $commentArray['isRoot']             = true;
            $commentArray['isRootWithChildren'] = (bool) $comment->children()->count();
        } else {
            $commentArray['isRoot']             = false;
            $commentArray['isRootWithChildren'] = false;
        }

        return $commentArray;
    }


    /**
     * Returns comment as array for tree
     *
     * @param Comment[] $comments
     *
     * @return array
     */
    private static function mapCommentsList(array $comments)
    {
        return array_map('self::mapComment', $comments);
    }
}