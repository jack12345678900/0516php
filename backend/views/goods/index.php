<?php
/**
id	primaryKey
name	varchar(50)	名称
intro	text	简介
logo	varchar(255)	LOGO图片
sort	int(11)	排序
status	int(2)	状态(-1删除 0隐藏 1正常)
 */
?>

<div>
    <a href="<?=\yii\helpers\Url::to(['goods/add'])?>"  class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></a>
</div><br/>
<?php
$form=yii\bootstrap\ActiveForm::begin([
        'method'=>'get',
    //get方式提交,需要显示指定action
    'action'=>yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($search,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($search,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($search,'minPrice')->textInput(['placeholder'=>'最低$'])->label(false);
echo $form->field($search,'maxPrice')->textInput(['placeholder'=>'最高$'])->label(false);

echo yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);

$form= yii\bootstrap\ActiveForm::end();
?>
<table class="table table-bordered table-responsive table table-hover">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>图片</th>
        <th>商品分类</th>
        <th>品牌分类</th>
<!--        <th>市场价格</th>-->
        <th>商品价格</th>
<!--        <th>库存</th>-->
        <th>是否在销售</th>
<!--       <th>状态</th>-->
<!--        <th>排序</th>-->
        <th>添加时间</th>

        <th>操作</th>
    </tr>
    <?php foreach ($good as $goods):?>
        <tr data-id="<?=$goods->id?>">
            <td><?=$goods->id?></td>
            <td><?=$goods->name?></td>
            <td><?=$goods->sn?></td>
            <td>

                <?php if($goods->logo){
                    echo '<img src="';echo $goods->logo;echo '" width="50">';
                }else{
                    echo '<img src="/upload/1.jpg" width="50">';
                }?>



            </td>
            <td><?=$goods->goods_category_id?></td>
            <td><?=$goods->brand->name?></td>

            <td><?=$goods->shop_price?></td>

            <td>

                <?php if($goods->is_on_sale
                    ==1){
                    echo '在销售';
                }elseif($goods->is_on_sale==0){
                    echo '已下架';
                }

                ?>

            </td>


<!--          if($goods->status-->
<!--                  echo '正常';-->
<!--             }elseif($goods->status==0){-->
<!--                  echo '回收站';-->
<!--               }-->





            <td><?=date('Y-m-d H:i:s', $goods->create_time) ?></td>

            <td>
                <a href="<?=\yii\helpers\Url::to(['goods/show','goods_id'=>$goods->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/gallery','id'=>$goods->id])?>" class="btn btn-default">
                    <span class="glyphicon glyphicon-book"></span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$goods->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="javascript:;" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>



<?php
/*
  * @var $this\yii\web\viw
  */
//注册js代码
$del_url=\yii\helpers\Url::to(['goods/del']);
$this->registerJs(new yii\web\JsExpression(
    <<<JS
$(".del_btn").click(function() {
  if (confirm('确定要删除吗?')){
      var tr=$(this).closest('tr');
      var id=tr.attr("data-id");
      $.post("{$del_url}",{id:id},function(data){
          if (data=='success'){
              alert('删除成功!');
              tr.hide('slow');
          }else{
              alert('删除失败!');
          }
      
      
        
      });
  }
});
JS

));

?>
<?php
echo  \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

])?>
