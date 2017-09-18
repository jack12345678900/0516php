<?php
  namespace backend\models;

  use yii\base\Model;

  class PermissionForm extends Model{
      public $name;//权限名称
      public $description;//权限描述
     const SCENARIO_ADD='add';
      const SCENARIO_EDIT='edit';
      public function rules()
      {
          return [
              [['name','description'],'required'],
              ['name','validateName','on'=>self::SCENARIO_ADD],
              ['name','validateEditName','on'=>self::SCENARIO_EDIT],
          ];
      }
      //验证权限名称唯一
      public function validateName(){
          //只管问题
          if(\Yii::$app->authManager->getPermission($this->name)){
              $this->addError('name','权限已存在');
          }
      }
      public function validateEditName(){
          //只管问题
          $auth=\Yii::$app->authManager;

          //没有修改名称(主键)

          //修改了名称 新名称不能存在
          //怎么判断名称修改没有,通过get方式获取
          if(\Yii::$app->request->get('name')!=$this->name){
              if($auth->getPermission($this->name)){
                  $this->addError('name','角色已存在');
              }
          }


      }

      public function attributeLabels()
      {
          return [
            'name'=>'权限名称',
              'description'=>'权限描述',
          ];
      }
  }