<?php

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'price')->input('number');
echo yii\bootstrap\Html::img($model->logo,['class'=>'img-cricle','style'=>'width:50px']);

echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'template'=>'<div class="row"><div class="col-lg-2">{image}</div><div
 class="col-lg-2">{input}</div></div>']);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
