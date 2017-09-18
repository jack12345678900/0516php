<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/3
 * Time: 10:03
 */

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearch extends Model
{
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;

    public function rules()
    {
        return [
            ['name','string','max'=>50],
            ['sn','string'],
            ['minPrice','double'],
            ['maxPrice','double'],

        ];
    }

    public function search(ActiveQuery $params)
    {

        //加载表单提交的数据
        $this->load(\Yii::$app->request->get());
        if($this->name){
            $params->Where(['like','name',$this->name]);
        }
        if($this->sn){
            $params->andWhere(['like','sn',$this->sn]);
        }
        if($this->maxPrice){
            $params->andWhere(['<=','shop_price',$this->maxPrice]);
        }
        if($this->minPrice){
            $params->andWhere(['>=','shop_price',$this->minPrice]);
//            var_dump($query);exit;
        }
        //return $params;
    }
}