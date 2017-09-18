<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/2
 * Time: 14:40
 */
?>
    <table class="table table-bordered table-responsive">
        <a href="<?=\yii\helpers\Url::to(['admin/add'])?>" class="btn btn-link"><span class="glyphicon glyphicon-plus"></span></a>
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>Email</th>
            <th>状态</th>
            <th>最后登录时间</th>
            <th>最后登录ip</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach($admins as $admin):?>
            <tr>
                <td><?=$admin->id?></td>
                <td><?=$admin->username?></td>
                <td><?=$admin->email?></td>
                <td><?php if($admin->status
                        ==1){
                        echo '正常';
                    }elseif($admin->status==0){
                        echo '禁用';
                    }

                    ?></td>
                <td><?=date('Y-m-d H:i:s',$admin->last_login_time)?></td>
                <td><?=$admin->last_login_ip?></td>
                <td><?=date('Y-m-d H:i:s',$admin->created_at)?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$admin->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="<?=\yii\helpers\Url::to(['admin/delete','id'=>$admin->id])?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
            </tr>
        <?php endforeach;?>
    </table>


<?php
echo  \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

])
?>