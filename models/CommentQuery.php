<?php

namespace app\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Class MenuQuery
 *
 * @method CommentQuery roots()
 * @method CommentQuery leaves()
 * @method Comment      one($db = null)
 * @method Comment[]    all($db = null)
 *
 * @package app\models
 */
class CommentQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}