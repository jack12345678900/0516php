<?php
  namespace backend\filters;

  use yii\base\ActionFilter;
  use yii\web\ForbiddenHttpException;

  class RbacFilter extends ActionFilter{
      public function beforeAction($action)
      {
          //当前访问的路由
        if (!  \Yii::$app->user->can($action->uniqueId)){
            //判断,如果用户没有登录,则引导用户调到登录页面
            if (\Yii::$app->user->isGuest){
                return $action->controller->redirect(['admin/login'])->send();
                //
            }
            //如果没有权限,则显示提示信息页面
            throw new ForbiddenHttpException('都不好意思,您没有资格操作或者没有权限');
        };
       //  true;//放行
//          return false;//拦截
          return parent::beforeAction($action);
      }
  }