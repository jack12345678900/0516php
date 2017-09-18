<?php
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css

')
?>
<a href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>"  class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></a>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
      <?php foreach ($per as $pers):?>
          <tr>
              <td><?=$pers->name ?></td>
              <td><?=$pers->description?></td>
              <td>
                  <a><a href="<?=\yii\helpers\Url::to(['rbac/edit-permission','name'=>$pers->name])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></a>/
                  <a><a href="<?=\yii\helpers\Url::to(['rbac/del-permission','name'=>$pers->name])?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></a>
              </td>
          </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js 

',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $(document).ready( function () {
     $('#table_id_example').DataTable();
    } );
JS
));



