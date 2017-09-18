<?php

    namespace backend\models;

    use yii\base\Model;

    class RoleForm extends Model{
        public $name;
        public $description;
        public $permissions;
        const SCENARIO_ADD='add';
        const SCENARIO_EDIT='edit';
        public function rules()
        {
            return[
                [['name','description'],'required'],
                ['permissions','safe'],
                ['name','validateName','on'=>self::SCENARIO_ADD],
                ['name','validateEditName','on'=>self::SCENARIO_EDIT],
            ];

        }
//        public function scenarios()
//        {
//            return [
//                //如果定义的场景在rules中没有配置,需要通过scenarios方法申明,否则场景不存在
//                self::SCENARIO_EDIT=>[],
//            ];
//        }


        //验证权限名称唯一
        public function validateName(){
            //只管问题
            $auth=\Yii::$app->authManager;


                if($auth->getRole($this->name)){
                    $this->addError('name','角色已存在');
                }


        }
         public function validateEditName(){
             //只管问题
             $auth=\Yii::$app->authManager;

           //没有修改名称(主键)

             //修改了名称 新名称不能存在
                 //怎么判断名称修改没有,通过get方式获取
                 if(\Yii::$app->request->get('name')!=$this->name){
                     if($auth->getRole($this->name)){
                         $this->addError('name','角色已存在');
                     }
                 }


         }


//静态方法获取权限选项
        public static function getPermissions(){
            $permissions=\Yii::$app->authManager->getPermissions();
            $itmes=[];
            foreach ($permissions as $permission){
                       $itmes[$permission->name]=$permission->description;
            }
            return $itmes;

        }
    }