<?php
  namespace frontend\controllers;

  use backend\models\Goods;
  use backend\models\Goods_day_count;
  use backend\models\Goods_intro;
  use backend\models\GoodsCategory;
  use backend\models\GoodsGallery;
  use Behat\Gherkin\Loader\YamlFileLoader;
  use frontend\models\Cart;
  use frontend\models\Shop;
  use yii\base\Controller;
  use yii\data\Pagination;
  use yii\web\Cookie;

  class ShopController extends \yii\web\Controller
  {
      public $enableCsrfValidation = false;

      public function actionIndex()
      {
          //获取所有一级分类
          $shop = GoodsCategory::find()->where(['parent_id' => 0])->all();
          return $this->renderPartial('index', ['shop' => $shop]);

      }

      //商品列表
      public function actionList($category_id)
      {
          $category = GoodsCategory::findOne(['id' => $category_id]);
          $query = Goods::find();
          if ($category->depth == 2) {
              //三级分类

              $query->andWhere(['goods_category_id' => $category_id]);
          } elseif ($category->depth == 1) {
//              $ids=[];
//              foreach ($category->children(2)->all() as $category3){
//                  $ids[]=$category3->id;
//              }
              $ids = $category->children()->select('id')->andwhere(['depth' => 2])->column();
              $query->andWhere(['in', 'goods_category_id', $ids]);
          }
          //三种情况,一级分类.二级,三级


          $pager = new Pagination();
          $pager->totalCount = $query->count();
          $pager->defaultPageSize = 20;
          $pager = new Pagination([
              'totalCount' => $query->count(),
              'defaultPageSize' => 4
          ]);
          $models = $query->limit($pager->limit)->offset($pager->offset)->all();
          return $this->renderPartial('list', ['models' => $models, 'pager' => $pager]);
      }

      //详情
      public function actionGoods($id)
      {
          $goods = Goods::findOne(['id' => $id]);
          $good = GoodsGallery::find()->where(['goods_id' => $id])->all();
          return $this->renderPartial('goods', ['goods' => $goods, 'good' => $good]);
      }

      //添加到购物车里面 完成添加到购物车的操作
      public function actionAddtocart($goods_id, $amount)
      {
          //直接跳转到购物车

          if (\Yii::$app->user->isGuest) {
              //未登录购物车保存cookie

              //写入cookie
              $cookies = \Yii::$app->request->cookies;
              $value = $cookies->getValue('carts');
              if ($value) {
                  $carts = unserialize($value);
              } else {
                  $carts = [];
              }
              //检查购物车是否存在要添加的商品
              if (array_key_exists($goods_id, $carts)) {
                  $carts[$goods_id] += $amount;
              } else {
                  $carts[$goods_id] = intval($amount);
              }
              $cookies = \Yii::$app->response->cookies;
              $cookie = new Cookie();
              $cookie->name = 'carts';
              $cookie->value = serialize($carts);
              $cookie->expire = time() + 1200;//过期时间
              $cookies->add($cookie);
//var_dump($cookie);exit;
          } else {
              $member_id=\Yii::$app->user->id;
              $value=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
              if ($value) {
                  $value->amount += $amount;
                  $value->save();
              } else {
                  $model = new Cart();
                  $model->amount = $amount;
                  $model->goods_id=$goods_id;
                  $model->member_id=\Yii::$app->user->id;
                  $model->save(false);
              }
          }
          return $this->redirect(['cart']);
      }

      //购物车页面
      public function actionCart()
      {

          if (\Yii::$app->user->isGuest) {

              $cookies = \Yii::$app->request->cookies;
              $value = $cookies->getValue('carts');
              //var_dump($cookies);exit;
              if ($value) {
                  $carts = unserialize($value);
              } else {
                  $carts = [];
              }
              //var_dump($carts);exit;
              $models = Goods::find()->where(['in', 'id', array_keys($carts)])->all();

          } else {
              //登录的情况下

              $request = \Yii::$app->request;
              $cookies = $request->cookies;
              $value = $cookies->getValue('carts');

              if ($value) {
                  $carts = unserialize($value);

                  //遍历数据
                  foreach ($carts as $k => $amount) {
                      $member_id = \Yii::$app->user->id;
                      $value = Cart::findOne(['goods_id' => $k, 'member_id' => $member_id]);

                      if ($value) {
                          $value->amount += $amount;
                          $value->save(false);
                      } else {
                          $model = new Cart();
                          $model->amount = $amount;
                          $model->goods_id = $k;
                          $model->member_id = \Yii::$app->user->id;
                          $model->save(false);
                      }
                  }
                  //清除cookie
                  if (\Yii::$app->request->cookies->get('carts')) {
                      \yii::$app->response->cookies->remove('carts');
                  }
                  }else{
                      $id = \Yii::$app->user->getId();
                      $carts = Cart::find()->where(['member_id' => $id])->all();
                      //var_dump($carts);exit;
                      // $models = '';
                      foreach ($carts as $cart) {
                          $carts[$cart['goods_id']]=$cart['amount'];
                      }
                  }
                  $models = Goods::find()->andwhere(['in', 'id', array_keys($carts)])->all();
              }


              // $models = Goods::find()->where([ $carts['goods_id']])->all()var_dump($models);exit;
          return $this->renderPartial('cart', ['models' => $models, 'carts' => $carts]);
      }

      //ajax添加减少
      public function actionAjax(){
          //      throw new ForbiddenHttpException('试试');
          $requst=\Yii::$app->request;
          $goods_id=$requst->post('goods_id');
          $amount=$requst->post('amount');
          if(\Yii::$app->user->isGuest) {
              $cookies = \Yii::$app->request->cookies;
              $value = $cookies->getValue('carts');
              if ($value) {
                  $carts = unserialize($value);

                  } else {
                      $carts = [];
                  }
                  //检查是否存在商品
                  if (array_key_exists($goods_id, $carts)) {
                      $carts[$goods_id] = $amount;
                  }
                  $cookies = \Yii::$app->response->cookies;
                  $cookie = new Cookie();
                  $cookie->name = 'carts';
                  $cookie->value = serialize($carts);
                  $cookie->expire = time() + 7 * 24 * 3600;//过期时间
                  $cookies->add($cookie);
              } else {
              $id=\Yii::$app->user->identity->getId();
              $model=Cart::findOne(['member_id'=>$id]);
              $model->amount=$amount;
              $model->save(false);
              if (\Yii::$app->request->post('goods1_id')){
                  $id=\Yii::$app->user->identity->getId();
                  $model=Cart::findOne(['member_id'=>$id,'goods_id'=>\Yii::$app->request->post('goods1_id')]);
                 $model->delete();
                  return 'success';
              }
              }
          }
//      public function actionLogout()
//      {
//          \Yii::$app->user->logout();
//          return $this->goHome();
//      }


  }

