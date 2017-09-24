<?php
  namespace frontend\controllers;


  use backend\models\Goods;
  use frontend\models\Address;
  use frontend\models\Cart;
  use frontend\models\Order;
  use frontend\models\OrderGoods;
  use yii\db\Exception;
  use yii\web\Controller;

  class OrderController extends Controller{

      public $enableCsrfValidation = false;
      public function actionOrder(){
           //判断必须是登录状态
          if(!\Yii::$app->user->isGuest){//已登录
              //1  显示订单表单
              $member_id=\Yii::$app->user->id;
              $ares=Address::find()->where(['member_id'=>$member_id])->all();
              $carts=Cart::find()->where(['member_id'=>$member_id])->all();
              $deliverys=order::$deliveries;
              $payments=order::$payments;
          //提交表单
              $request=\Yii::$app->request;
              if ($request->isPost){
                 //var_dump(1);exit;
                  $order=new Order();
                  $order->member_id=\Yii::$app->user->id;
                  //$data=\Yii::$app->request->post();
                  $address_id=$request->post(); //地址id
                  $dress=Address::findOne(['id'=>$address_id['address_id'],'member_id'=>$order->member_id]);
                 //var_dump($address_id);exit;
                  $order->name=$dress->name;
                  $order->province=$dress->s_province;
                  $order->city=$dress->s_city;
                  $order->area=$dress->s_county;
                  $order->address=$dress->area;
                  $order->tel=$dress->tel;
                 // $order->total=$order['shop_price']*$order['amount'];
                  $order->delivery_id=$address_id['delivery'];
                  $order->delivery_name=$deliverys[$address_id['delivery']][0];
                  $order->delivery_price=$deliverys[$address_id['delivery']][1];

                  $order->payment_id=$address_id['pay'];
                  // var_dump($model['pay']);
                  $order->payment_name=$payments[$address_id['pay']][0];
                  $order->create_time=time();
                  $order->trade_no=uniqid();
                  $amount='';
                 // $carts=[];
                  foreach ($carts as $cart){
                      $carts[$cart['goods_id']]=$cart['amount'];
                  }
                  $goods=Goods::find()->where(['in','id',array_keys($carts)])->all();
                  //计算价格
                  foreach ($goods as $good){
                      foreach ($carts as $k=>$cart){
                          if ($k==$good->id){
                              $amount+=$good['shop_price']*$cart;
                          }

                      }
                  }
                  //var_dump($amount);exit;
                  $total=intval($deliverys[$address_id['delivery']][1]+$amount);
                  $order->total=$total;
                  $order->status=1;
                  //开启事务

         // var_dump($order);exit;
          $affair=\Yii::$app->db->beginTransaction();
          try{

            //  var_dump($order);exit;
              $order->save();
   // var_dump($order->getErrors());exit;
                  //订单商品详情表
                    foreach ($carts as $cartt){
                        //检查库存
                        if($cartt->amount>$cartt->goods->stock){
                            //库存不足,无法下单
                            throw new Exception($cartt->goods->name.'库存不足,无法下单');
                        }
                        $orders=new OrderGoods();
                        $orders->order_id=$order->id;
                        $orders->goods_id=$cartt->goods_id;
                        $orders->goods_name=$cartt->goods->name;
                        $orders->amount=$cartt->amount;
                        $orders->price=$cartt->goods->shop_price;
                        $orders->logo=$cartt->goods->logo;

                        $total=intval($deliverys[$address_id['delivery']][1]+$amount);
                        $orders->total=$total;
                        //$order->total=1;

                        $orders->save();
                        //提交事务
                        $affair->commit();
                        return $this->redirect(['order/order2']);
                    }
                }catch (Exception $e){
                  //回滚
                  $affair->rollBack();
              }
            }
           // var_dump('1');exit;
              return $this->renderPartial('order',['ares'=>$ares,'deliverys'=>$deliverys,
                  'payments'=>$payments,'carts'=>$carts]);
          }else{  //未登录,调到登录页面
              return $this->redirect(['member/login']);
          }

      }
      public function actionOrder2(){
          return $this->renderPartial('order2');
      }
      public function actionOrder3(){

          $order3=Order::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();

          //var_dump($models);exit;
          return $this->renderPartial('order3',['order3'=>$order3]);
      }
  }