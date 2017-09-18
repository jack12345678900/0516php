<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/2
 * Time: 14:40
 */
?>

  <h1>菜单列表</h1>
<table class="table table-bordered table-responsive">
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($menu as $admin):?>
        <tr>
            <td><?=$admin->name?></td>
            <td><?=$admin->url?></td>
            <td><?=$admin->Previous_Menu?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$admin->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                <a href="<?=\yii\helpers\Url::to(['menu/del','id'=>$admin->id])?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
        </tr>
    <?php endforeach;?>
</table>

