<?php

namespace app\models;

use Yii;
use app\models\User;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
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
    public function attributeLabels() {
        return [
            'email' => Yii::t('app', 'البريد الالكتروني'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail() {
        /* @var $user User */
        $user = User::findOne([
                    'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                $message = '<table>'
                        . '<tr><td><b>لاعادة تعيين كلمة المرور لهذا الحساب على موقع هانج شير يرجة الضغط على الرابط التالي :</b></td></tr>'
                        . '<tr><td><a href="' . Yii::$app->urlManager->createAbsoluteUrl('site/reset-password?token=' . $user->password_reset_token) . '">' .
                        Yii::$app->urlManager->createAbsoluteUrl('site/reset-password?token=' . $user->password_reset_token)
                        . '</a></td></tr>'
                        . '</table>';
                $headers = "From: <info@hangshare.com> \r\n";
                $headers .= "Reply-To: <info@hangshare.com> \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                mail($user->email, 'اعادة تعيين كلمة المورور', $message, $headers);
            }
            return TRUE;
        }

        return false;
    }

}
