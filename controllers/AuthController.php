<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PosttagController implements the CRUD actions for PostTag model.
 */
class AuthController extends Controller {

       public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::classname(),
                'only' => ['Facebook'],
                'rules' => [[
                'allow' => true,
                'roles' => ['@']
                    ]]
            ],
        ];
    }

    public function actionFacebook() {
        die('asd');
    }

}
