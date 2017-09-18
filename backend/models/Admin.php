<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property integer $password
 * @property string $password_hash
 * @property string $email
 * @property string $last_login_time
 * @property integer $last_login_ip
 */
  class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
     const SCENARIO_ADD='add';
     const SCENARIO_EDIT='edit';
     const SCENARIO_OLD='old';
     public $password;
   public $password1;
   public $password2;
    public $code;
    public $role;
    public $description;
    public static function tableName()
    {
        return 'admin';
    }
    //保存之前要做的事
      public function beforeSave($insert)
      {
          //$insert ,bool,是否是添加
          if ($insert){
              //添加
             // $this->password_hash=Yii::$app->security->generatePasswordHash($this->password);
             // $this
              //密码加密 auth_key
              //$this->password_hash=Yii::$app->security->
          }else{

              //修改
// ($this->password_hash){
             // }

          }



          return parent::beforeSave($insert); // 必须返回父类方法,该方法必须返回true,save方法才会执行
      }

      /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         //   [['last_login_ip'], 'string'],
            ['password_hash','required','on'=>self::SCENARIO_ADD],
            [['username','email','status'],'required'],
            [['auth_key','password_reset_token','password1','password2','password'],'string'],
            [['username', 'password_hash', 'email','status'], 'string', 'max' => 255],
            ['code', 'captcha'],
            [['password_reset_token'],'unique'],
            [['role'],'required','on'=>self::SCENARIO_OLD],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password_hash' => '密码',
            'email' => 'Email',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登陆ip',
            'code' => '验证码',
            'status'=>'状态',
            'password'=>'请输入旧密码',
            'password1'=>'输入新密码',
            'password2'=>'确认新密码',
        ];
    }

      public static function getRoleItems(){
          $roles=\Yii::$app->authManager->getRoles();
          $itmes=[];
          foreach ($roles as $role){
              $itmes[$role->name]=$role->description;
          }
          return $itmes;

      }
      /**
       * Finds an identity by the given ID.
       * @param string|int $id the ID to be looked for
       * @return IdentityInterface the identity object that matches the given ID.
       * Null should be returned if such an identity cannot be found
       * or the identity is not in an active state (disabled, deleted, etc.)
       */
      public static function findIdentity($id)
      {
          return self::findOne(['id'=>$id]);
      }

      /**
       * Finds an identity by the given token.
       * @param mixed $token the token to be looked for
       * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
       * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
       * @return IdentityInterface the identity object that matches the given token.
       * Null should be returned if such an identity cannot be found
       * or the identity is not in an active state (disabled, deleted, etc.)
       */
      public static function findIdentityByAccessToken($token, $type = null)
      {
          // TODO: Implement findIdentityByAccessToken() method.
      }

      /**
       * Returns an ID that can uniquely identify a user identity.
       * @return string|int an ID that uniquely identifies a user identity.
       */
      public function getId()
      {
          return $this->id;
      }

      /**
       * Returns a key that can be used to check the validity of a given identity ID.
       *
       * The key should be unique for each individual user, and should be persistent
       * so that it can be used to check the validity of the user identity.
       *
       * The space of such keys should be big enough to defeat potential identity attacks.
       *
       * This is required if [[User::enableAutoLogin]] is enabled.
       * @return string a key that is used to check the validity of a given identity ID.
       * @see validateAuthKey()
       */
      public function getAuthKey()
      {
          return $this->auth_key;
      }

      /**
       * Validates the given auth key.
       *
       * This is required if [[User::enableAutoLogin]] is enabled.
       * @param string $authKey the given auth key
       * @return bool whether the given auth key is valid.
       * @see getAuthKey()
       */
      public function validateAuthKey($authKey)
      {
          return $authKey==$this->auth_key;
      }

  }
