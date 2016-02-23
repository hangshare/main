<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\models\EmailTemplate;
use app\models\User;
use app\models\UserEmail;

require Yii::$app->vendorPath . '/autoload.php';

use Aws\Ses\SesClient;

class AwsEmail extends Component {

    private static $__accessKey = 'AKIAJ3JZA2TENDIDQTBQ';
    private static $__secretKey = '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';

    public static function SendMail($to, $subject, $body, $from = 'info@hangshare.com') {
        if(! YII_DEBUG) {
            $client = SesClient::factory(array(
                'credentials' => array(
                    'key' => self::$__accessKey,
                    'secret' => self::$__secretKey,
                ),
                'region' => 'us-east-1',
                'version' => '2010-12-01'
            ));
            try {
                $client->sendEmail(array(
                    'Source' => "HangShare.com <info@hangshare.com>",
                    'Destination' => array(
                        'ToAddresses' => array($to)
                    ),
                    'Message' => array(
                        'Subject' => array(
                            'Data' => $subject,
                            'Charset' => 'UTF-8',
                        ),
                        'Body' => array(
                            'Text' => array(
                                'Data' => $body,
                                'Charset' => 'UTF-8',
                            ),
                            'Html' => array(
                                'Data' => $body,
                                'Charset' => 'UTF-8',
                            ),
                        ),
                    ),
                    'ReplyToAddresses' => array($from),
                    'ReturnPath' => 'info@hangshare.com'
                ));
            } catch (Exception $exc) {
                print $exc->getTraceAsString() . chr(10);
            }
        }
    }

    public static function queueUser($userId, $type, $params = []) {
        $email = EmailTemplate::find()->where("id = {$type}")->one();
        if ($userId != 0) {
            $user = User::find()->where("id = {$userId}")->one();
            $name = $user->name;
            $email_to = $user->email;
        } else {
            $name = '';
            $email_to = 'info@hangshare.com';
        }
        if (filter_var($email_to, FILTER_VALIDATE_EMAIL)) {
            $key = md5(microtime() . rand());
            $userEmail = new UserEmail;
            $userEmail->userId = $userId;
            $userEmail->emailId = $type;
            $userEmail->key = $key;
            $userEmail->opened_at = 0;
            $userEmail->save();
            $params['__user_name__ '] = $name;
            $body = strtr($email->body, $params);
            $body.="<img src='http://www.hangshare.com/site/email/?id={$key}' width='1' height='1' />";
            self::SendMail($email_to, $email->subject, $body);
        }
    }

}
