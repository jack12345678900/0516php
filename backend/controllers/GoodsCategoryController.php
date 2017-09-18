<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use creocoder\nestedsets\NestedSetsBehavior;
class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        //1获取所有的用户数据
        $request =GoodsCategory::find();
        //每页多少条,总条数
        //实例化分页工具条
        $pager = new Pagination([
            'totalCount' =>$request->count(),
            'defaultPageSize' => 2,
        ]);
//       // $goodscategorys=$request->limit()->offset()->all();
 $goodscategorys = $request->limit($pager->limit)->offset($pager->offset)->all();
        //2 分配数据,调用视图
        return $this->render('index', ['pager' => $pager,'goodscategorys'=>$goodscategorys]);

    }
    public function actionAdd()
    {

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $model = new GoodsCategory();
        $requset = \Yii::$app->request;
        if ($requset->isPost) {
            //接收数据
            $model->load($requset->post());
            if ($model->validate()) {
                if($model->parent_id){
                    //非顶级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
               // $model->save(false);
                \Yii::$app->session->setFlash('success', '商品添加成功!');
                return $this->redirect(['index']);
            } else {
                //验证失败
                var_dump($model->getErrors());
                exit;
            }
        }
       return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $model = GoodsCategory::findOne(['id'=>$id]);
        $requset = \Yii::$app->request;
        if ($requset->isPost) {
            //接收数据
            $model->load($requset->post());
            if ($model->validate()) {
                if($model->parent_id){
                    //非顶级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类

                    //修改顶级分类,不改变层级
                    //判断旧的属性parent_id是否为0
                    //1.查询数据表,获取旧的parent_id
                    //2.直接获取当前对象的旧属性
                    if ($model->getOldAttribute('parent_id')==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }

                }
               // $model->save(false);
                \Yii::$app->session->setFlash('success', '商品修改成功!');
                return $this->redirect(['index']);
            } else {
                //验证失败
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add', ['model' => $model]);
    }
   // ztree测试
//    public function actionZtree(){
//        $goodscategories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//     //   var_dump($goodscategories);
//        return $this->renderPartial('ztree' ,['goodscategories'=>$goodscategories]);
//    }

    //删除
    //bug 删除根节点报错
    public function actionDelete($id)
    {


        $model=GoodsCategory::findOne(['id'=>\Yii::$app->request->post('id')]);

        //判断是否有子节点
        if ($model->isLeaf()){
            //是否是叶子子节点(是否有子节点)

        }

       $model = GoodsCategory::findOne(['id' => $id]);
        if ($model->parent_id) {
            $child = GoodsCategory::findOne(['parent_id' => $model->id]);
            // var_dump($child);exit;

           // if ($model->isL)
            if ($child) {
                \Yii::$app->session->setFlash('success', '有子分类不能删除!');
            } else {
                //    $model = GoodsCategory::findOne(['id' => $id]);
                $model->delete();
            }
        }else{

        }
        return $this->redirect(['goods-category/index']);
    }

}
