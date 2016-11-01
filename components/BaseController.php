<?php

namespace app\components;

use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class BaseController
 *
 * Base controller with few features
 *
 * @package app\components
 */
abstract class BaseController extends Controller
{
    /**
     * Return error for AJAX response
     *
     * @param $error
     *
     * @return array
     */
    public function ajaxError($error)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'result' => false,
            'error'  => $error,
        ];
    }


    /**
     * Return AJAX response
     *
     * @param $data
     *
     * @return array
     */
    public function ajaxResponse($data = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($data === null) {
            return ['result' => true];
        }

        return [
            'result' => true,
            'data'   => $data,
        ];
    }
}