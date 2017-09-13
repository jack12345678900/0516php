<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gallery`.
 */
class m170911_025636_create_gallery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('gallery', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'path'=>$this->string(255)->comment('图片地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('gallery');
    }
}
