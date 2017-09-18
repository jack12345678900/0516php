<?php
  namespace frontend\controllers;

  use frontend\models\Member;
  use yii\web\Controller;

  class MemberController extends Controller{
      //用户注册
      public function actionRegister(){
          $model=new Member();
          $request=\Yii::$app->request;
          if ($request->isPost){
              $model->load($request->post());
              if ($model->validate()){
                  $model->save(false);

                  return $this->redirect(['login']);
              }
          }
          //显示视图不加载布局文件
          return $this->renderPartial('register');
      }
      public function actionIndex(){
          return $this->render('index');
      }

  }