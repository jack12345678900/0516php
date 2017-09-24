<?php

namespace backend\controllers;



use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\LoginForm;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{

   public function actionLogin()
   {




       $login = new LoginForm();

       $request = \Yii::$app->request;
       if ($request->isPost) {


           $login->load($request->post());



               if ($login->validate()) {
                   //认证

                   //\Yii::$app->security->validatePassword($login->password, $login->password_hash);
                   if ($login->login()) {


                       //var_dump(\Yii::$app->user->isGuest);die;
                       \Yii::$app->session->setFlash('success', '登录成功');

                       return $this->redirect(['index']);
                   }
               }
           }
           return $this->render('login', ['login' => $login]);
       }
    public function actionIndex()
    {

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
  // var_dump(\Yii::$app->user->isGuest);die;
        $query=Admin::find();
        $pager=new Pagination([
           'totalCount'=>$query->count(),
           'defaultPageSize'=>3
        ]);
        $admins=$query->limit($pager->limit)->offset($pager->offset)->all();

        return $this->render('index',['admins'=>$admins,'pager'=>$pager]);
    }

    public function actionAdd(){


         $admin=new Admin();
        $admin->scenario=Admin::SCENARIO_ADD;
       // $request=new Request();
         $request=\Yii::$app->request;
         if ($request->isPost) {
             $admin->load($request->post());
             if ($admin->validate()) {
                 //var_dump($admin);exit;

                 $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password_hash);
                 $admin->created_at = time();
                 $admin->auth_key = \Yii::$app->security->generateRandomString();//随机字符串
                 $admin->save(false);
                 $auth=\Yii::$app->authManager;
                //
                 //  给用户分配角色
                 if ($admin->role) {
                     //
                     foreach ($admin->role as $description) {
                         $roles = $auth->getRole($description);
                        //var_dump($roles);exit;
                         $auth->assign($roles,$admin->id);//角色权限
                     }
                 }
                 \Yii::$app->session->setFlash('success', '添加成功');


                     return $this->redirect(['admin/index']);
                 } else {
                     var_dump($admin->getErrors());
                     exit;
                 }
             }


        return $this->render('add',['admin'=>$admin]);
    }

    public function actionUedit(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }

         $id=\Yii::$app->user->identity->getId();

        $admin=Admin::findOne(['id'=>$id]);

      if ($admin==null){
          throw new NotFoundHttpException('用户不存在');
      }

        $request=\Yii::$app->request;
        if ($request->isPost){


            $admin->load($request->post());

            if ($admin->validate()){

                if($admin->password1==$admin->password2){
                    if (\Yii::$app->security->validatePassword($admin->password, $admin->password_hash)) {
                        // var_dump($admin);exit;
                        $admin->updated_at=time();//修改时间
                        $admin->password_hash=\Yii::$app->security->generatePasswordHash($admin->password_hash);
                        $admin->auth_key=\Yii::$app->security->generateRandomString();
                        $admin->save(false);
                       // $auth=\Yii::$app->authManager;


                        \Yii::$app->session->setFlash('success','修改成功');
                        return $this->redirect(['admin/index']);

                    }else{

                        \Yii::$app->session->setFlash('success','原密码错误');
                        return $this->redirect(['admin/uedit']);
                        exit;
                    }
                }else{
                     \Yii::$app->session->setFlash('success','两次密码错误');
                     //var_dump($admin);exit;

                    return $this->redirect(['admin/uedit']);
                    exit;
                }

            }else{
                var_dump($admin->getErrors());
                exit;
            }
        }
        $admin->password_hash=\Yii::$app->security->passwordHashStrategy;
        return $this->render('uedit',['admin'=>$admin]);
    }

    public function actionEdit($id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }

        $admin=Admin::findOne(['id'=>$id]);
        $admin->scenario=Admin::SCENARIO_OLD;
        if ($admin==null){
            throw new NotFoundHttpException('用户不存在');
        }
        $auth=\Yii::$app->authManager;
        $admins=$auth->getRolesByUser($id);
        $admin->role=array_keys($admins);
        $request=\Yii::$app->request;
        if ($request->isPost) {


            $admin->load($request->post());

            if ($admin->validate()) {

                // var_dump($admin);exit;
                $admin->updated_at = time();//修改时间
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password_hash);
                $admin->auth_key = \Yii::$app->security->generateRandomString();
                $admin->save(false);

                $auth->revokeAll($id);
                //$admins=$auth->createRole($admin->name);

                //  给用户分配角色
                if ($admin->role) {
                    foreach ($admin->role as $roleName) {
                        $admins = $auth->getRole($roleName);
                        $auth->assign($admins, $admin->id);//角色权限
                    }
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);


            } else {
                var_dump($admin->getErrors());
                exit;
            }
        }
        $admin->password_hash=\Yii::$app->security->passwordHashStrategy;
        return $this->render('add',['admin'=>$admin]);

    }


    public function actionDelete($id){

    if(\Yii::$app->user->isGuest){
        return $this->redirect(['admin/login']);
    }
    Admin::deleteAll(['id'=>$id]);
    \Yii::$app->session->setFlash('success', '删除成功');
    return $this->redirect(['admin/index']);
}
    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['admin/login']);
    }

  public function behaviors()
  {
      return [
          'rbac'=>[
              'class'=>RbacFilter::className(),
               'except'=>['logout','login','captcha','error']
          ]
      ];
  }


}
