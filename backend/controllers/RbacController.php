<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\rbac\Permission;

class RbacController extends \yii\web\Controller
{
   //添加权限

   public function actionAddPermission(){


      $permission=new PermissionForm();

      $permission->scenario=PermissionForm::SCENARIO_ADD;
      //$permission=new PermissionForm(['scenario'=>PermissionForm::SCENARIO_ADD]);
         $request=\Yii::$app->request;
         if ($request->isPost){
             $permission->load($request->post());
             if ($permission->validate()){
                 $auth=\Yii::$app->authManager;
                 //添加权限

                 //创建权限
                 $permissions=$auth->createPermission($permission->name);
                 $permissions->description=$permission->description;
                 //保存到数据库
            $auth->add($permissions);
                 \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['permission-index']);
             }
         }
       return $this->render('permission',['permission'=>$permission]);
   }
    //修改权限
    public function actionEditPermission($name){
        $auth=\Yii::$app->authManager;
        $permissions=$auth->getPermission($name);

        $permission=new PermissionForm();
        $permission->scenario=PermissionForm::SCENARIO_EDIT;

        $permission->name=$permissions->name;
        $permission->description=$permissions->description;
        $request=\Yii::$app->request;

        if ($request->isPost){

            $permission->load($request->post());

            if ($permission->validate()){
               $auth=\Yii::$app->authManager;
                //修改权限
               // $per=$auth->getPermission($name);
                $permissions->name=$permission->name;
                $permissions->description=$permission->description;
                //保存到数据库
                $auth->update($name,$permissions);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('permission',['permission'=>$permission]);
    }
   //权限列表
    public function actionPermissionIndex(){
              $auth=\Yii::$app->authManager;
              $per=$auth->getPermissions();

              return $this->render('permission-index',['per'=>$per]);
    }

    public function actionDelPermission($name){
        $auth=\Yii::$app->authManager;
     $permissions=$auth->getPermission($name);
      $permission=new PermissionForm();
      $permission->name=$permissions->name;
        $auth->remove($auth->getPermission($name));

        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['permission-index']);
    }

    //添加角色
    public function actionAddRole(){
    $role=new RoleForm();
        $role->scenario=RoleForm::SCENARIO_ADD;

    $request=\Yii::$app->request;
    if ($request->isPost){
        $role->load($request->post());
       // var_dump($role);exit;
        if ($role->validate()){
            //保存角色
            $auth=\Yii::$app->authManager;
            //创建角色
            $roles=$auth->createRole($role->name);
            $roles->description=$role->description;
            //保存到数据库里面去
            $auth->add($roles);
            //给角色分配权限
            if ($role->permissions){
                    foreach ($role->permissions as $permissionName){
                        $permission=$auth->getPermission($permissionName);

                        $auth->addChild($roles,$permission);//角色权限
                    }
              //var_dump($role);exit;
            }
            return $this->redirect(['role-index']);

        }
    }
    //var_dump($role);exit;
    return $this->render('role',['role'=>$role]);

}
//修改角色
    public function actionEditRole($name){
        $auth=\Yii::$app->authManager;
        //显示修改表单
        //根据主键获取数据
        $roles=$auth->getRole($name);
        //实例化表单模型(活动记录)
        $role=new RoleForm();
        $role->scenario=RoleForm::SCENARIO_EDIT;
        //调用视图,分配数据
        $role->name=$roles->name;
        $role->description=$roles->description;
       // var_dump($roles->description);exit;
       //获取当前角色关联的权限
        $role->permissions=array_keys($auth->getPermissionsByRole($name));
       //var_dump($role);exit;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $role->load($request->post());
            if ($role->validate()){
                //保存角色
                $auth=\Yii::$app->authManager;
                //创建角色
                $roles->name=$role->name;
                $roles->description=$role->description;
               // var_dump($roles);exit;
                //保存到数据库里面去
              //  var_dump($roles);exit;
                $auth->update($name,$roles);
                //先清除所有关联的权限
                $auth->removeChildren($roles);
                //根据表单提交的权限关联
                if ($role->permissions){
                    foreach ($role->permissions as $permissionName){
                        $permission=$auth->getPermission($permissionName);
                        $auth->addChild($roles,$permission);//角色权限
                    }

                }
                return $this->redirect(['role-index']);

            }
        }
      //var_dump($role);exit;
        return $this->render('role',['role'=>$role]);

    }

    public function actionDelRole($name){
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRole($name);
        $role=new PermissionForm();
        $role->name=$roles->name;
        $auth->remove($auth->getRole($name));

        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['role-index']);
    }
    public function actionRoleIndex(){
        $auth=\Yii::$app->authManager;
        $role=$auth->getRoles();

        return $this->render('role-index',['role'=>$role]);
    }
    public function actionIndexxxxxxxxxxxx()
    {


        //两个用户  admin  zhangsan
        //两个角色  超级管理员  前台
        //两个权限  添加用户 用户列表
        //超级管理员[添加用户 用户列表]  前台[用户列表]
        //admin--超级管理员   zhangsan--前台

        //所有RBAC操作都不需要直接操作数据表,都是通过authManager组件提供的方法来执行
        $auth = \Yii::$app->authManager;
        //1.添加角色
        //1.1 创建新角色

        $role1 = $auth->createRole('超级管理员');
        $role2 = $auth->createRole('前台');
        //1.2 保存到数据表
        $auth->add($role1);
        $auth->add($role2);
        //2 添加权限
        //2.1  创建新权限 (权限和路由一致)
        $permission1 = $auth->createPermission('rbac/add-user');
        $permission2 = $auth->createPermission('rbac/user-index');
        //2.2  保存到数据表
        $auth->add($permission1);
        $auth->add($permission2);
        //3 给角色分配权限
        $auth->addChild($role1,$permission1);//角色  权限
        $auth->addChild($role1,$permission2);//角色  权限
        $auth->addChild($role2,$permission2);//角色  权限
        //4 给用户分配角色
        $auth->assign($role1,2);
        $auth->assign($role2,1);
        echo '设置完成';

        //补充方法
        //是否存在某角色?获取角色
        $role = $auth->getRole('超级管理员');
        //获取权限
        $permission = $auth->getPermission('rbac/add-user');
        //移除角色的权限
        $auth->removeChild($role1,$permission1);
        //取消用户的角色
        $auth->revoke($role1,2);



        return $this->render('index');


    }
}
