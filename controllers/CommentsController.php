<?php

namespace app\controllers;

use Yii;
use app\components\BaseController;
use app\components\CommentsTreeHelper;

/**
 * Class CommentsController
 *
 * @package app\controllers
 */
class CommentsController extends BaseController
{
    /**
     * @return array
     */
    public function actionRoots()
    {
        return $this->ajaxResponse(['comments' => CommentsTreeHelper::getOneLevel()]);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function actionChildren($id)
    {
        return $this->ajaxResponse(['comments' => CommentsTreeHelper::getChildren($id)]);
    }

    /**
     * Add comment to tree
     */
    public function actionCreate()
    {
        $result = CommentsTreeHelper::create(Yii::$app->request->post());
        if ($result === false) {
            return $this->ajaxError('Fill the form');
        }

        return $this->ajaxResponse(['comments' => [$result]]);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function actionUpdate($id)
    {
        $result = CommentsTreeHelper::update($id, Yii::$app->request->post());
        if ($result === false) {
            return $this->ajaxError('Fill the form');
        }

        return $this->ajaxResponse(['comment' => $result]);
    }

    /**
     * Delete comment
     *
     * @param int $id Comment id
     *
     * @return array
     */
    public function actionDelete($id)
    {
        if (!CommentsTreeHelper::delete($id)) {
            return $this->ajaxError('Comment not found');
        }

        return $this->ajaxResponse();
    }
}