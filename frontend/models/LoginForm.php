<?php
  namespace frontend\models;

  use yii\base\Model;
  use yii\web\ForbiddenHttpException;

  class LoginForm extends Model
  {
      public $username;
      public $password;
      public $remember;
      public $checkcode;

      public function rules()
      {
          return [
              [['username','password'],'required'],
              [['remember'],'boolean'],
              ['checkcode','captcha','message'=>'验证码错误']
          ];
  }

      public function login()
      {

          $model = Member::findOne(['username' => $this->username]);
          //if ($this->status==1) {
          if ($model) {
              //账户存在,继续验证码
              //密码加密(添加用户时)
              //验证密码
              if (\Yii::$app->security->validatePassword($this->password, $model->password_hash)) {
                 //var_dump($model);die;
                  //账户密码正确可以登录
                  // var_dump($this);exit;

                  //设置最后登录时间和ip
                  // var_dump($admins);exit;
                  $model->last_login_time = time();
                  $ip = \Yii::$app->request->userIP;
                  $model->last_login_ip = $ip;

                  $model->save(false);
                  if ($this->remember){
                      return \Yii::$app->user->login($model,120);
                  }
                  return \Yii::$app->user->login($model);

              } else {
                  //密码不正确
             //throw new ForbiddenHttpException('密码错误');
                //  \Yii::$app->session->setFlash('success', '密码错误!');
              $this->addError('password', '密码错误!');
              }
          } else {
             //throw new ForbiddenHttpException('账户不存在');
              //没有找到该账户
             // \Yii::$app->session->setFlash('success', '账户不存在!');
            $this->addError('username', '账户不存在');
          }
          return false;
      }
  }