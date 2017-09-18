<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'Previous_Menu')->dropDownList(\yii\helpers\ArrayHelper::map($menu,'id','name'),['prompt'=>'--请选择上级菜单--']);

//->dropDownList(yii\helpers\ArrayHelper::map($menu,'id','name'));;

echo $form->field($model, 'url')->dropDownList(yii\helpers\ArrayHelper::map($lu,'name','name'),['prompt'=>'--请选择路由--']);
echo $form->field($model,'sort')->input('number');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();



