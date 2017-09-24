<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property string $id
 * @property string $name
 * @property string $area
 * @property string $location
 * @property string $tel
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    ///public $member_id;
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'area', 'tel','s_province','s_city','s_county'], 'string', 'max' => 255],
           // [['member_id'],'integer']
            //[['code'],'captcha'],
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
            'area' => 'Area',
            'location' => 'Location',
            'tel' => 'Tel',
        ];
    }
}
