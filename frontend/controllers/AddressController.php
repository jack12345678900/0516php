<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
    public function actionAddress(){

        $address=new Address();
        $request=\Yii::$app->request;//
        if ($request->isPost){
            $address->load($request->post(),'');
            if($address->validate()){
                //var_dump($address);die;

                $address->member_id=\Yii::$app->user->id;
                //var_dump($address->member_id);exit;
                $address->save(false);
                \Yii::$app->session->setFlash('success', '保存成功!');
                return $this->redirect(['address/address']);
            }
        }
        $addresss=Address::find()->all();
        return $this->renderPartial('address',['address'=>$address,'addresss'=>$addresss]);
    }

    public function actionEdit($id){
        $address=Address::findOne($id);
        $request=\Yii::$app->request;//
//     var_dump($request);exit;

        if ($request->isPost){
           // var_dump($addre);die;
            //var_dump($request->isPost);exit;
            $address->load($request->post());


            if($address->validate()){
                //
                //$address->s_province=$address['s_province'];

                $address->save(false);
                \Yii::$app->session->setFlash('success', '修改成功!');
                return $this->redirect(['address/address']);
            }
        }
        $addresss=Address::find()->all();
        return $this->renderPartial('address',['address'=>$address,'addresss'=>$addresss]);
    }
    public function actionDel($id){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        Address::deleteAll(['id'=>$id]);
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['address/address']);
    }
}
