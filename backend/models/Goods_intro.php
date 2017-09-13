<?php

namespace backend\models;

class Goods_intro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_intro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'content' => '商品描述',

        ];
    }
}