<?php
     $form=yii\bootstrap\ActiveForm::begin();
     echo $form->field($login,'username')->textInput();
      echo $form->field($login ,'password_hash')->passwordInput();
echo $form->field($login, 'remember',['inline'=>true])->checkbox();
echo $form->field($login,'code')->widget(\yii\captcha\Captcha::className(),[
'template'=>'<div class="row"><div class="col-lg-2">{image}</div><div
 class="col-lg-2">{input}</div></div>']);

echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
     yii\bootstrap\ActiveForm::end();