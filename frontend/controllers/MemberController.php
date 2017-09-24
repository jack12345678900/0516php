<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\SmsDemo;

class MemberController extends \yii\web\Controller
{
//public function actionRediss(){
//    $redis=new \Redis();
//    $redis->connect('127.0.0.1');
//    $redis->set('name','杰克');
//    echo 'ok';
//}
//    public function actionRedis(){
//       phpinfo();
//
//    }
    public function actionRegister()
    {
        $model = new Member();
        $request = \Yii::$app->request;
        //
        if ($request->isPost) {
            $model->load($request->post(),'');
          // var_dump($model);exit;
            if ($model->validate()) {
                // if ($model->password1==$model->password_hash){
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                $model->created_at = time();
                $model->auth_key = \Yii::$app->security->generateRandomString();//随机字符


                $model->save(false);
                //注册成功
                \Yii::$app->session->setFlash('success', '注册成功');
                        return $this->redirect(['login']);

            }
            //显示视图不加载布局文件

        }
        return $this->renderPartial('register',['model'=>$model]);
    }
    public function actionValidateUser($username){
        //可以注册
        $member=Member::findOne(['username'=>$username]);
        if ($member){
            return 'false';
        }else{
            return 'true';
        }

    }
    public function actionIndex(){
        $model=Member::find()->all();
        return $this->renderPartial('index',['model'=>$model]);
    }

 public function actionLogin(){
      $login=new LoginForm();
      $request=\Yii::$app->request;
      if ($request->isPost){

          $login->load($request->post(),'');
          //
          if ($login->validate()){
              if ($login->login()){
             //     var_dump(1);exit;

                  \Yii::$app->session->setFlash('success', '登录成功');
                  return $this->redirect(['/shop/index']);
              }
          }
      }
     return $this->renderPartial('login', ['login' => $login]);
 }
 //测试发送短信

   public function actionSms(){

       $redis=new \Redis();
     $redis->connect('127.0.0.1');

     $phone=\Yii::$app->request->post('phone');
     $code=rand(1000,9999);
       //var_dump($code);exit;
    $redis->set('code_'.$phone,$code);
       //var_dump($code);exit;
       $demo = new SmsDemo(
           "LTAI7MHMB0aeSEzs",
           "zmkUQ57sccZgXWqjiLCyVOy3Zko93Z"
       );

       echo "SmsDemo::sendSms\n";
       $response = $demo->sendSms(
           "南美蝶", // 短信签名
           "SMS_97800015", // 短信模板编号
           $phone,// 短信接收者
           Array(  // 短信模板中字段的值
               "code"=>$code,
              // "product"=>"dsd"
           )
          // "123"
       );
       print_r($response);

      // echo $code;

   }
    public function actionValidateSms($phone,$sms){

          $redis=new \Redis();
          $redis->connect('127.0.0.1');
         // var_dump($redis);exit;
       $code=$redis->get('code_'.$phone);
       if ($code==null || $code!=$sms){
           return 'false';
       }

        return 'true';
    }
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect('login.html');
    }

}
