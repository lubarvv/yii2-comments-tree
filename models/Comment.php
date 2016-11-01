<?php

namespace app\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Comment
 *
 * @method CommentQuery children(integer $levels = null)
 * @method bool         appendTo(Comment $model)
 * @method bool         makeRoot()
 *
 * @property int    $id
 * @property int    $parentCommentId
 * @property int    $left
 * @property int    $right
 * @property int    $level
 * @property string $userName
 * @property string $text
 * @property int    $createdAt
 * @property int    $updatedAt
 * @property bool   $isDeleted
 *
 * @package app\models
 */
class Comment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'tree'      => [
                'class'          => NestedSetsBehavior::className(),
                'leftAttribute'  => 'left',
                'rightAttribute' => 'right',
                'depthAttribute' => 'level',
            ],
            'timestamp' => [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return CommentQuery
     */
    public static function find()
    {
        return new CommentQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userName', 'text'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['parentCommentId', 'safe', 'on' => 'create'],
            [['userName', 'text'], 'safe'],
            [['userName', 'text'], 'required'],
        ];
    }
}