<?php

namespace backend\controllers;

use app\models\Article;
use app\models\Article_category;
use app\models\Article_Detail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $query=Article::find();

        $pager = new Pagination([
            'totalCount' => $query->where(['>','status','-1'])->count(),
            'defaultPageSize' => 2
        ]);
        $Article= $query->where(['>','status','-1'])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['Article'=>$Article,'pager'=>$pager]);
    }
  public function actionAdd(){

      if(\Yii::$app->user->isGuest){
          return $this->redirect(['admin/login']);
      }
        $model=new Article();
      $models=new Article_Detail();
        $request=\Yii::$app->request;

        if ($request->isPost) {
            $model->load($request->post());
            $models->load($request->post());
            if ($model->validate()) {
                //验证
                $model->create_time = time();
                $model->save();
                $models->article_id = $model->id;
                $models->save();
                \Yii::$app->session->setFlash('success', ';添加成功');
                    return $this->redirect(['article/index']);
                } else {
                    var_dump($model->getErrors());
                    exit;
                }
            }


        $gory=Article_category::find()->all();
       return $this->render('add',['model'=>$model,'gory'=>$gory,'models'=>$models]);
  }

    public function actionEdit($id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $model=Article::findOne(['id'=>$id]);
        $models=Article_Detail::findOne(['article_id'=>$id]);
        $request=\Yii::$app->request;

        if ($request->isPost) {
            $model->load($request->post());
            $models->load($request->post());
            if ($model->validate()) {
                //验证
            //    $model->create_time = time();
                $model->save();
                $models->article_id=$model->id;
                $models->save();
                //  return $this->redirect(['article/index']);
                \Yii::$app->session->setFlash('success', ';修改成功');
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }

        $gory=Article_category::find()->all();
        return $this->render('add',['model'=>$model,'gory'=>$gory,'models'=>$models]);
    }
    public function actionDel(){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $id=\Yii::$app->request->post('id');
        //$article_id=\Yii::$app->request->post('article_id');
      //  $article_detail=Article_Detail::findOne(['article_id'=>$article_id]);
        $article=Article::findOne(['id'=>$id]);
//        var_dump($id);exit;
//        var_dump($article);exit;
        if ($article){
            $article->status=-1;
            $article->save(false);
            \Yii::$app->session->setFlash('success','删除成功');
        }
         return $this->redirect(['article/index']);
    }

    public function actionShow($article_id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $model=Article_Detail::findOne(['article_id'=>$article_id]);
        //var_dump($model);exit;
        return $this->render('show',['model'=>$model]);

    }
}
