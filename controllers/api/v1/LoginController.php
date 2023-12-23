<?php

namespace app\controllers\api\v1;

use app\models\User;
use app\models\UserLogin;
use sizeg\jwt\JwtHttpBearerAuth;

class LoginController extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'index',
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $model = new UserLogin();
        $postData = \Yii::$app->request->post();
        $model->attributes = $postData;
        if ($model->validate()) {
            $user = User::findOne([
                'username' => $postData['username']
            ]);

            if ($user && $user->validatePassword($postData['password_hash'])) {
                $jwt = \Yii::$app->jwt->getBuilder()
                    ->setIssuer('bimasakti')
                    ->setAudience('bimasakti')
                    ->setId($user->id, true)
                    ->setIssuedAt(time())
                    ->setExpiration(time() + 3600)
                    ->set('uid', $user->id)
                    ->getToken();

                return [
                    'status' => 'success',
                    'message' => 'Successfully login',
                    'token' => (string)$jwt,
                ];
            } else {
                \Yii::$app->response->setStatusCode(422);
                return ['error' => 'Invalid username or password'];
            }
        } else {
            \Yii::$app->response->setStatusCode(422);
            $errors = $model->errors;
            return [
                'status' => 'error',
                'message' => 'Invalid credentials',
                'errors' => $errors
            ];
        }
    }
}
