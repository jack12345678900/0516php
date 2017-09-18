<?php
 namespace backend\models;




 use yii\base\Model;

 class LoginForm extends Model{
      public $username;
      public $password_hash;
     public $code;
     public $status;
     public $remember;
      public function rules()
      {
          return [
              [['username','password_hash'],'required'],
           // [['last_login_ip','last_login_time'],'string'],
              ['code','captcha'],
              [['remember'],'boolean']
          ];
      }

      public function attributeLabels()
      {
          return [
              'username'=>'用户名',
              'password_hash'=>'密码',
              'code'=>'验证码'
          ];
      }

      public function login()
      {

          $admins = Admin::findOne(['username' => $this->username]);

          //if ($this->status==1) {
          if ($admins) {
              //账户存在,继续验证码
              //密码加密(添加用户时)
                  //验证密码
                  if (\Yii::$app->security->validatePassword($this->password_hash, $admins->password_hash)) {
                      //账户密码正确可以登录
                      // var_dump($this);exit;

                      //设置最后登录时间和ip
                      // var_dump($admins);exit;
                      $admins->last_login_time = time();
                      $ip = \Yii::$app->request->userIP;
                      $admins->last_login_ip = $ip;

                      $admins->save(false);
                     if ($this->remember){
                         return \Yii::$app->user->login($admins,120);
                     }
                      return \Yii::$app->user->login($admins);

                  } else {
                      //密码不正确
                      $this->addError('password_hash', '密码错误!');
                  }
              } else {
                  //没有找到该账户
                  $this->addError('username', '账户不存在');
              }
              return false;
 }
 }