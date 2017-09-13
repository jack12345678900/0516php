<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{

    public $content;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {

        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['intro','content'], 'string'],
            [['article_category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类id',
            'sort' => '排序',
            'status' => '状态',
            'content'=>'内容',
            'create_time' => '创建时间',
        ];
    }

    public function getArticle_category(){
        return $this->hasOne(Article_category::className(),['id'=>'article_category_id']);
    }
}
