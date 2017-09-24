<?php
          namespace frontend\models;
          //use yii\db\ActiveRecord;

          class Shop extends \yii\db\ActiveRecord{

              public function rules()
              {
                  return[
                      [['goods_category_id'], 'integer'],
                      [['name'], 'string', 'max' => 20],
                      [['logo'], 'string', 'max' => 255],
                      [[ 'name', 'parent_id'], 'required'],
                  ];
              }
          }

