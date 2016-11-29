<?php

namespace app\components;

use Yii;
use yii\base\Component;
use Aws\Sns\SnsClient;

class Sendsms extends Component
{
    private static $__accessKey = 'AKIAJ3JZA2TENDIDQTBQ';
    private static $__secretKey = '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';

    public function send($message, $phone)
    {

        $client = SnsClient::factory(array(
            'credentials' => array(
                'key' => self::$__accessKey,
                'secret' => self::$__secretKey,
            ),
//            'profile' => 'SMS_Test',
            'region' => 'us-east-1',
            'version' => '2010-03-31',
        ));

        $payload = array(
            'TopicArn' => 'arn:aws:sns:us-east-1:754841271985:SMS_Test',
            'Message' => $message,
            'MessageStructure' => 'string',
        );

        try {
            $client->publish($payload);
        } catch (Exception $e) {
            echo "Send Failed!\n" . $e->getMessage();
        }
    }
}
