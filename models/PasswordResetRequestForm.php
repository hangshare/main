<?php

namespace app\models;

use app\components\AwsEmail;
use Yii;
use app\models\User;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{

    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'message' => 'البريد الاكتروني غير مستخدم'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'البريد الالكتروني'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'email' => $this->email,
        ]);
        if ($user) {
            $user->generatePasswordResetToken();
            $user->save(false);

            if (!empty($user->scId)) {
                AwsEmail::queueUser($user->id, 8);
            } else {
                AwsEmail::queueUser($user->id, 7, [
                    '__link__' => Yii::$app->urlManager->createAbsoluteUrl('//reset-password/?token=' . $user->password_reset_token)
                ]);
            }
            return TRUE;
        }
        return false;
    }

}
