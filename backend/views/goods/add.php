<?php



use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name')->textInput();

//echo $form->field($goods,'sn')->textInput();

echo $form->field($goods,'goods_category_id')->dropDownList(yii\helpers\ArrayHelper::map($Goodscategory,'id','name'));;
//var_dump($goods);exit;
echo $form->field($goods,'brand_id')->dropDownList(yii\helpers\ArrayHelper::map($brand,'id','name'));;

echo $form->field($goods,'market_price')->input('number');
echo $form->field($goods,'shop_price')->input('number');
echo $form->field($goods,'stock')->input('number');

echo $form->field($goods, 'is_on_sale',['inline'=>true])->radioList(['1'=>'销售','0'=>'下架']);
echo $form->field($goods, 'status',['inline'=>true])->radioList(['1'=>'正常','0'=>'回收站']);

echo $form->field($goods,'sort')->input('number');
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[]);


//上传文件框
echo $form->field($goods,'logo')->hiddenInput();







//var_dump($goods);exit;
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        $("#goods-logo").val(data.fileUrl);
        $("#img").attr("src",data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//var_dump(555555);exit;
echo yii\bootstrap\Html::img($goods->file,['id'=>'img']);
echo yii\bootstrap\Html::img($goods->logo,['class'=>'img-cricle','style'=>'width:200px']);


echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
