<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\Brand;
use backend\models\Goods_day_count;
use backend\models\Goods_intro;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsSearch;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use \kucha\ueditor\UEditor;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }



        $search=new GoodsSearch();
        $query=Goods::find();
        $search->search($query);
        //$query=Goods::find();
      //  $search=new GoodsSearch();


         //
        $pager = new Pagination([
            'totalCount' => $query->andWhere(['>','status','0'])->count(),
            'defaultPageSize' => 2
        ]);

        $good= $query->limit($pager->limit)->offset($pager->offset)->all();



        return $this->render('index',['good'=>$good,'pager'=>$pager,'search'=>$search]);
        //var_dump($query);exit;
    }
// public function actionSearch(){
//
//
//     $search=new GoodsSearch();
//     $query=Goods::find();
//     $search->search($query);
//
//     return $this->render('shop',['search'=>$search]);
// }



    public function actionAdd(){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $goods=new Goods();
        $goods_count=new Goods_day_count();
        $goods_intro=new Goods_intro();
        //$Goodscategory=new GoodsCategory();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $goods->load($request->post());
            $goods_count->load($request->post());
            $goods_intro->load($request->post());
            if ($goods->validate() && $goods_intro->validate()){
                $goods_intro->goods_id=$goods->id;
                $goods_intro->save();
                $goods->create_time=time();
                $date=date('Ymd',time());
                $time=Goods_day_count::find()->where(['day'=>$date])->orderBy(['count'=>SORT_DESC])->asArray()->one();

                 if ($time){
                     if ($time['count']<10){
                         $goods->sn=$date.'000'.$time['count'];
                     }elseif ($time['count']<100){
                         $goods->sn=$date.'00'.$time['count'];
                     }elseif ($time['count']<1000){
                         $goods->sn=$date.'0'.$time['count'];
                     }
                     $goods_count->count=$time['count']+1;
                 }

                     else{

                         $goods->sn='0001';
                         $goods_count->count=1;
                     }

                $goods->save();
                $goods_count->day=date('Ymd',time());
                $goods_count->save();
                \Yii::$app->session->setFlash('success','添加成功');

                //var_dump($goods);exit;
                return $this->redirect(['goods/index']);
            }else{
                var_dump($goods->getErrors());
                exit;
            }

        }
        $brand=Brand::find()->all();
        //$Goodscategory=GoodsCategory::find()->all();
        return $this->render('add',['goods'=>$goods,'brand'=>$brand,'goods_intro'=>$goods_intro]);

    }

    public function actionEdit($id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $goods=Goods::findOne(['id'=>$id]);
        $goods_intro=Goods_intro::findOne(['goods_id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $goods->load($request->post());
            $goods_intro->load($request->post());
            if ($goods->validate() && $goods_intro->validate()){
                $goods->save();
                $goods_intro->goods_id=$goods->id;
                $goods_intro->save();


                \Yii::$app->session->setFlash('success','修改成功');

                //var_dump($goods);exit;
                return $this->redirect(['goods/index']);
            }else{
                var_dump($goods->getErrors());
                exit;
            }

        }
        $brand=Brand::find()->all();
        $Goodscategory=GoodsCategory::find()->all();
        return $this->render('add',['goods'=>$goods,'brand'=>$brand,'Goodscategory'=>$Goodscategory,'goods_intro'=>$goods_intro]);

    }
    public function actionDel(){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $id=\Yii::$app->request->post('id');
        $goods=Goods::findOne(['id'=>$id]);

        if ($goods){
            $goods->status=0;
            $goods->save();
            \Yii::$app->session->setFlash('success','删除成功!');
        }
        $this->redirect(['goods/index']);
    }


    public function actionShow($goods_id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $goods_id=Goods_intro::findOne(['goods_id'=>$goods_id]);
        return $this->render('show',['goods_id'=>$goods_id]);
    }

    //商品相册
    public function actionGallery($id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $goods=Goods::findOne(['id'=>$id]);

        $gallerys=GoodsGallery::find()->all();
        return $this->render('gallery',['goods'=>$goods,'gallerys'=>$gallerys]);
    }

    //删除相册
    public function actionDelGallery(){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $id=\Yii::$app->request->post('id');
        $model=GoodsGallery::findOne(['id'=>$id]);
        if($model&&$model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }


    public function actions() {
        return [

            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],


            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true,
                'postFieldName' => 'Filedata',

                'overwriteIfExist' => true,

                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {

                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {



                    $model=new GoodsGallery();
                    $model->goods_id=\Yii::$app->request->post('goods_id');
                    $model->path = $action->getWebUrl();
                    $model->save();

                    $action->output['fileUrl'] = $model->path;

                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);
                    $key = $action->getWebUrl();
                    $file=$action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;
                },
            ],
        ];
    }
}
