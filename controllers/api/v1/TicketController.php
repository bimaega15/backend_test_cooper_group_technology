<?php

namespace app\controllers\api\v1;

use app\models\Ticket;
use app\models\TicketFile;
use app\models\User;
use app\models\UserLogin;
use app\validators\TicketValidator;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use app\controllers\api\v1\Jwt;

class TicketController extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => Jwt::class,
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);

        $errorData = TicketValidator::validateTicket($postData);
        if (empty($errorData)) {
            $ticketModel = new Ticket();
            $ticketModel->attributes = $postData['Ticket'];
            $ticketModel->save();
            $ticketId = $ticketModel->id;
            $ticketData = Ticket::findOne($ticketId);
            unset($ticketData['updated_at']);

            $ticketFileData = [];
            $baseUrl = Yii::$app->request->hostInfo;

            foreach ($postData['TicketFile'] as $file) {
                $extension = pathinfo($file['file'], PATHINFO_EXTENSION);
                $extension = $extension == '' ? 'png' : $extension;

                $fileName = Yii::$app->security->generateRandomString(16) . '.' . $extension;

                $imageData = base64_decode(str_replace('data:image/png;base64,', '', $file['file']));

                $filePath = 'upload/ticket/' . $fileName;

                FileHelper::createDirectory(dirname($filePath));
                file_put_contents($filePath, $imageData);

                $ticketDetail = new TicketFile();
                $ticketDetail->ticket_id = $ticketId;
                $ticketDetail->file = $baseUrl . '/' . $filePath;
                $ticketDetail->save();

                $ticketFileData[] = [
                    'id' => $ticketDetail->id,
                    'ticket_id' => $ticketId,
                    'file' => $baseUrl . '/' . $filePath,
                    'ext' => $extension,
                ];
            }

            return [
                'success' => true,
                "data" => [
                    "Ticket" => $ticketData,
                    "TicketFile" => $ticketFileData,
                ],
                'message' => 'Saving data Successfully',
            ];
        } else {
            \Yii::$app->response->setStatusCode(422);
            return [
                'status' => false,
                'message' => 'Please check your form',
                'result' => $errorData
            ];
        }
    }
}
