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
                'message' => Yii::t('app','Password.email.exist.message')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app','Password.email'),
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
                AwsEmail::queueUser($user->id, 'password_rest_sc');
            } else {
                AwsEmail::queueUser($user->id, 'password_rest', [
                    '__link__' => Yii::$app->urlManager->createAbsoluteUrl('//reset-password/?token=' . $user->password_reset_token)
                ]);
            }
            return TRUE;
        }
        return false;
    }

}
