<?php



use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name')->textInput();

//echo $form->field($goods,'sn')->textInput();

echo $form->field($goods,'goods_category_id')->hiddenInput();

echo '    <ul id="treeDemo" class="ztree"></ul>';


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

/**
 * @var $this \yii\web\View
 */

//注册css文件
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
//注册js(需要在js后面加载)
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//注册ztree静态资源
$goodscategories=json_encode(\backend\models\Goods::getZNodes());
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
 var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
	    	onClick:function(event, treeId, treeNode) {
	    	        console.log(treeNode);
	    	        $('#goods-goods_category_id').val(treeNode.id);
	    	    }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$goodscategories};
        
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //展开全部节点
            zTreeObj.expandAll(true);
            //修改, 根据当前分类id的parent_id来选中节点
            var node=zTreeObj.getNodeByParam('id',"{$goods->goods_category_id}",null);
            zTreeObj.selectNode();
JS

));