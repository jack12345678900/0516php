
<table class="table table-bordered table-responsive">
    <a href="<?=\yii\helpers\Url::to(['article_category/add'])?>" class="btn btn-link"><span class="glyphicon glyphicon-plus"></span></a>
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>简介</th>

        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($Article as $model):?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>

            <td><?php if($model->status
                    ==1){
                    echo '正常';
                }elseif($model->status==0){
                    echo '隐藏';
                }else{
                    echo '已删除';
                }

                ?></td>




            <td> <a href="<?=\yii\helpers\Url::to(['article_category/edit','id'=>$model->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                <a href="javascript:;" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
        </tr>
    <?php endforeach;?>
</table>

<?php
/*
  * @var $this\yii\web\viw
  */
//注册js代码
$del_url=\yii\helpers\Url::to(['article_category/del']);
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
