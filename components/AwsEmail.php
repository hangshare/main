<?php

namespace app\components;

use app\models\EmailTemplate;
use app\models\User;
use app\models\UserEmail;
use Aws\Ses\SesClient;
use Yii;
use yii\base\Component;

require Yii::$app->vendorPath . '/autoload.php';

class AwsEmail extends Component
{

    private static $__accessKey = 'AKIAJ3JZA2TENDIDQTBQ';
    private static $__secretKey = '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';

    public static function queueUser($userId, $type, $params = [])
    {
        $lang = Yii::$app->language;
        $email = EmailTemplate::find()->where("code = '{$type}' AND lang = '{$lang}'")->one();
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
            $userEmail->emailId = $email->id;
            $userEmail->key = $key;
            $userEmail->opened_at = 0;
            $userEmail->save();
            $params['__user_name__ '] = $name;
            $body = strtr($email->body, $params);
            $body .= "<img src='https://www.hangshare.com/site/email/{$key}/' width='1' height='1' />";
            self::SendMail($email_to, $email->subject, $body);
        }
    }

    public static function getDomainFromEmail($email)
    {
        $domain = substr(strrchr($email, "@"), 1);
        return strtolower($domain);
    }

    public static function SendMail($to, $subject, $body, $from = 'info@hangshare.com')
    {
        if (!YII_DEBUG || $to = 'hasania.khaled@gmail.com') {
            $client = SesClient::factory(array(
                'credentials' => array(
                    'key' => self::$__accessKey,
                    'secret' => self::$__secretKey,
                ),
                'region' => 'us-east-1',
                'version' => '2010-12-01'
            ));
            $allowed_domians = ['hotmail.com', 'yahoo.com', 'gmail.com', 'outlook.com', 'live.com', 'hangshare.com'];
            try {
                if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !in_array(self::getDomainFromEmail($to), $allowed_domians)) {
                    return false;
                }
                $client->sendEmail(array(
                    'Source' => "HangShare.com <no-reply@hangshare.com>",
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
                    'ReplyToAddresses' => array('info@hangshare.com'),
                    'ReturnPath' => 'info@hangshare.com'
                ));
            } catch (Exception $exc) {
                print $exc->getTraceAsString() . chr(10);
            }
        }
    }

}
