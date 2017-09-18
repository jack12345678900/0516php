<?php

namespace backend\controllers;



use backend\models\Menu;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $menu=Menu::find()->all();
        return $this->render('index',['menu'=>$menu]);
    }
    public function actionAdd()
    {

        $model = new Menu();
     $menu = Menu::find()->where(['Previous_Menu'=>0])->all();

        $request = \Yii::$app->request;
        $auth = \Yii::$app->authManager;
        $lu=$auth->getPermissions();
        if ($request->isPost) {

            $model->load($request->post());
           // if ($model->validate()) {

             $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['menu/index']);

        }
        //$menu = Menu::find()->all();
            return $this->render('add', ['model' => $model,'menu'=>$menu,'lu'=>$lu]);
        }

    public function actionEdit($id)
    {

        $model =Menu::findOne(['id'=>$id]);
        $menu =Menu::find()->where(['Previous_Menu'=>0])->all();

        $request = \Yii::$app->request;
        $auth = \Yii::$app->authManager;
        $lu=$auth->getPermissions();
        if ($request->isPost) {

            $model->load($request->post());
            // if ($model->validate()) {

            $model->update();
            \Yii::$app->session->setFlash('success', '修改成功');
            return $this->redirect(['menu/index']);

        }
        //$menu = Menu::find()->all();
        return $this->render('add', ['model' => $model,'menu'=>$menu,'lu'=>$lu]);
    }

    public function actionDel($id){

        $model=Menu::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['menu/index']);

    }
}
