<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($role,'name')->textInput();
echo $form->field($role,'description')->textInput();
echo $form->field($role,'permissions')->checkboxList(backend\models\RoleForm::getPermissions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();