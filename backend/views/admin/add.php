<?php

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username')->textInput();
echo $form->field($admin,'password_hash')->input('password');
echo $form->field($admin,'email')->input('Email');
echo $form->field($admin, 'status',['inline'=>true])->radioList(['1'=>'正常','0'=>'禁用']);
echo $form->field($admin,'role')->checkboxList(backend\models\Admin::getRoleItems());
//echo $form->field($role,'permissions')->checkboxList(backend\models\RoleForm::getPermissions());
echo $form->field($admin,'code')->widget(\yii\captcha\Captcha::className(),[
    'template'=>'<div class="row"><div class="col-lg-2">{image}</div><div
 class="col-lg-2">{input}</div></div>']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
