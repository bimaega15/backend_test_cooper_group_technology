<?php

namespace app\controllers\api\v1;

use Yii;
use yii\base\Behavior;
use yii\web\Response;

class Jwt extends Behavior
{
    public function events()
    {
        return [
            \yii\web\Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction($event)
    {

        $authorizationHeader = Yii::$app->request->getHeaders()->get('authorization');

        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            Yii::$app->response->statusCode = 401;
            Yii::$app->response->data = ['error' => 'Token not valid'];
            Yii::$app->end();
        }

        $token = substr($authorizationHeader, 7);
        try {
            $decodedToken = Yii::$app->jwt->getParser()->parse((string)$token);
            if ($decodedToken->isExpired()) {
                Yii::$app->response->statusCode = 401;
                Yii::$app->response->data = ['error' => 'Token Expired'];
                Yii::$app->end();
            }
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 401;
            Yii::$app->response->data = ['error' => 'Token not valid ' . $e->getMessage()];
            Yii::$app->end();
        }
    }
}
