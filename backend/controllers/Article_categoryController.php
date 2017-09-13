<?php

namespace backend\controllers;

use app\models\Article_category;
use yii\data\Pagination;
use yii\web\UploadedFile;

class Article_categoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
         $query=Article_category::find();
        $pager = new Pagination([
            'totalCount' => $query->where(['>','status','-1'])
->count(),
            'defaultPageSize' => 2
        ]);

        $Article= $query->where(['>','status','-1'])
->limit($pager->limit)->offset($pager->offset)->all();

        return $this->render('index',['Article'=>$Article, 'pager' => $pager]);
    }

    public function actionAdd(){
        $model=new Article_category();
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());




            if ($model->validate()) {//验证


                $model->save(false);
                \Yii::$app->session->setFlash('success', ';添加成功');
                return $this->redirect(['article_category/index']);
            } else {
                var_dump($model->getErrors());
                exit;//失败就提示错误信息并且结束后面代码的执行
            }
        }

        return $this->render('add', ['model' => $model]);
    }

    public function actionEdit($id){
        $model=Article_category::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());

            if ($model->validate()) {//验证

                $model->save(false);
                \Yii::$app->session->setFlash('success', ';修改成功');
                return $this->redirect(['article_category/index']);
            } else {
                var_dump($model->getErrors());
                exit;//失败就提示错误信息并且结束后面代码的执行
            }
        }

        return $this->render('add', ['model' => $model]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id');

        $article_category=Article_category::findOne(['id'=>$id]);
        if ($article_category){
            $article_category->status=-1;
            $article_category->save(false);
            \Yii::$app->session->setFlash('success','删除成功');
        }
        $this->redirect(['article_category/index']);
    }
}
