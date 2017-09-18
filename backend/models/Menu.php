<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $Previous Menu
 * @property string $route
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //public $name;
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','Previous_Menu','url'],'required'],
            [['Previous_Menu', 'sort'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'Previous_Menu' => 'Previous_Menu',
            'url' => 'url',
            'sort' => 'Sort',
        ];
}

    public static function getMenus(){
 // return ['label'=>1];
            ///获取所有的一级菜单
        $menuItems=[];
            $menus=Menu::find()->where(['Previous_Menu'=>0])->all();
            foreach ($menus as $menu){

                //获取该一级菜单的所有子菜单
                $children=Menu::find()->where(['Previous_Menu'=>$menu->id])->all();
                $items=[];
                foreach ($children as $child){
                    //判断当前用户是否有权限
                    if (Yii::$app->user->can($child->url )){
                        $items[]=['label'=>$child->name,'url'=>[$child->url]];
                    }
                }
//                $items=[
//                    ['lable'=>'添加用户','url'=>['admin/add']],
//                    ['lable'=>'用户列表','url'=>['admin/index']],
//                ];
               $menuItems[]=['label' =>$menu->name,'items'=>$items];
            }
             return $menuItems;
//        return  [
//            ['label' => '管理员','items'=>[
//                ['lable'=>'添加用户','url'=>['admin/add']],
//                ['lable'=>'用户列表','url'=>['admin/index']],
//            ]],
//        ];
    }
}

