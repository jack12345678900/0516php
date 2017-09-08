
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
        <tr>
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
                <a href="<?=\yii\helpers\Url::to(['article_category/delete','id'=>$model->id])?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
        </tr>
    <?php endforeach;?>
</table>
<!--<!--<script type="text/javascript">-->
<!--<!--    function  del() {-->
<!--        $.getJSON(-->
<!--            '/article-category/delete?id='+-->
<!--//        );-->
<!--//    }-->
<!--<!--//</script>-->

<?php
echo  \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

])?>
