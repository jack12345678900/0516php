<?php

namespace backend\controllers;

use backend\models\Brand;
use Codeception\Module\Yii1;
use yii\data\Pagination;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller{

     public $enableCsrfValidation=false;
    public function actionIndex(){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $query=Brand::find();
        $pager = new Pagination([
            'totalCount' => $query->where(['>','status','-1'])->count(),
            'defaultPageSize' => 2
        ]);

        $brands= $query->where(['>','status','-1'])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['brands'=>$brands, 'pager' => $pager]);
    }

    public function actionAdd(){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $model=new Brand();
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());

            //处理上传文件 实例化上传文件
           // $model->file = UploadedFile::getInstance($model, 'file');


            if ($model->validate()) {//验证

               // $file = '/upload/' . uniqid() . '.' . $model->file->getExtension();//文件名(包含;路径)
                //保存文件另存为
               // $model->file->saveAs(\Yii::getAlias('@webroot') . $file, false);
                //$model->logo = $file;//上传文件的地址赋值给头像的字段

                //移动文件

                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                exit;//失败就提示错误信息并且结束后面代码的执行
            }
        }

        return $this->render('add', ['model' => $model]);
    }


    public function actionEdit($id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());

            //处理上传文件 实例化上传文件
           // $model->file = UploadedFile::getInstance($model, 'file');


            if ($model->validate()) {//验证

             //  $file = '/upload/' . uniqid() . '.' . $model->file->getExtension();//文件名(包含;路径)
                //保存文件另存为
              //  $model->file->saveAs(\Yii::getAlias('@webroot') . $file, false);
               // $model->logo = $file;//上传文件的地址赋值给头像的head字段

                //移动文件

                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                exit;//失败就提示错误信息并且结束后面代码的执行
            }
        }

        return $this->render('add', ['model' => $model]);
    }
   public function actionDel(){

       if(\Yii::$app->user->isGuest){
           return $this->redirect(['admin/login']);
       }
        $id=\Yii::$app->request->post('id');

        $brand=Brand::findOne(['id'=>$id]);
        if ($brand){
            $brand->status=-1;
            $brand->save(false);
           \Yii::$app->session->setFlash('success','删除成功');
        }
       $this->redirect(['brand/index']);
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
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
               // 'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
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
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                   // $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云,并且返回七牛云的图片地址


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

    //测试七牛云
//
//    public function actionQiu(){
//
//
//        $config = [
//            'accessKey'=>'3IvRW-xuFs0vyyr8j-JtYdsSRsdQRgU484voZypH',
//            'secretKey'=>'NY4fIhRrVvd9BHnCIXIbHLFWSvtbGpGbN8YVmGhN',
//            'domain'=>'http://ow0l0w3r4.bkt.clouddn.com/',
//            'bucket'=>'0516php',
//            'area'=>Qiniu::AREA_HUABEI
//        ];
//
//        $qiniu = new Qiniu($config);
//        $key = '1.jpg';
//        $file=\Yii::getAlias('@webroot/upload/1.jpg');
//        $qiniu->uploadFile($file,$key);
//        $url = $qiniu->getLink($key);
//        var_dump($url);
//    }
}