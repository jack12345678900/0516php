<?php

namespace backend\models;

class Goods_day_count extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_day_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['day','count'], 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'day' => '日期',
            'count' => '商品数',

        ];
    }
}