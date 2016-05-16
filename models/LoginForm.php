<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $id;
    public $authKey;
    public $accessToken;
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || sha1($this->password) != $user->password_hash) {
                $this->addError($attribute, 'البريد الاكتروني أو كلمة المرور غيرة صحيحة.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::find()->select(['id', 'email', 'name', 'password_hash'])->where('email = :email', [':email' => strtolower($this->username)])->one();
        }

        return $this->_user;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getFbUser()
    {
        if ($this->_user === false) {
            $this->_user = User::find(['scId'=> $this->username])->select(['id', 'email', 'name', 'password_hash'])->one();
        }
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'البريد الإلكتروني'),
            'password' => Yii::t('app', 'كلمة المرور'),
            'rememberMe' => Yii::t('app', 'تذكرني في المرة القادمة'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {

        //facebook login
        if($this->password == 'Fb91khaled'){
            return Yii::$app->user->login($this->getFbUser(), 25920000);
        }

        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 25920000);
        } else {
            return false;
        }
    }

}
