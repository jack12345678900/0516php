<?php

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username')->textInput();
echo $form->field($admin,'password')->input('password');
echo $form->field($admin,'password1')->input('password');
echo $form->field($admin,'password2')->input('password');
echo $form->field($admin,'code')->widget(\yii\captcha\Captcha::className(),[
    'template'=>'<div class="row"><div class="col-lg-2">{image}</div><div
 class="col-lg-2">{input}</div></div>']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
