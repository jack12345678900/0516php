<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article_detail".
 *
 * @property integer $article_id
 * @property string $content
 */
class Article_Detail extends \yii\db\ActiveRecord
{
    public $id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['content'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章',
            'content' => '内容',
        ];
    }
}
