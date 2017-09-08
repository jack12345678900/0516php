<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord{


    public $file;
    public $code;

    public function rules(){
        return[
            [['name','intro','logo'],'required'],
            [['sort','status'],'integer'],

            //['file', 'file', 'extensions' => ['jpg', 'png', 'gif'], 'skipOnEmpty' => false],
            //验证码的验证规则
            //['code', 'captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => '品牌名称',
            'intro'=>'简介',
            'sort' => '排序',
            'logo' => '图片',
            //'code' => '验证码',
            'status'=>'状态',
        ];
    }
}