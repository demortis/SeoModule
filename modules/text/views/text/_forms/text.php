<?php
/**
 * @author: Eugene
 * @date: 13.04.16
 * @time: 9:21
 */

\digitalmonk\modules\seo\assets\SeoAssets::register($this);

use yii\helpers\Html;

?>
<?php
\yii\bootstrap\Modal::begin([
    'id' => 'template-modal',
    'header' => 'Новый шаблон',
    'size' => \yii\bootstrap\Modal::SIZE_LARGE
]);

\yii\bootstrap\Modal::end();

$hash = uniqid('temp_');
?>
<?=Html::activeHiddenInput($model, 'tempHash', ['value' => $hash]);?>
<div class="row">
    <div class="col-md-6">
        <?=Html::activeLabel($model, 'url')?>
        <?=Html::activeTextInput($model, 'url', ['class' => 'form-control']);?>
        <?=Html::error($model, 'url', ['class' => 'help-block help-block-error'])?>
    </div>
    <div class="col-md-2">
        <?=Html::activeLabel($model, 'position')?>
        <?=Html::activeTextInput($model, 'position', ['class' => 'form-control']);?>
        <?=Html::error($model, 'position', ['class' => 'help-block help-block-error'])?>
    </div>
    <div class="col-md-4">
        <?=Html::activeLabel($model, 'inheritable')?>
        <?=Html::activeDropDownList($model, 'inheritable', ['Нет', 'Да'], ['class' => 'form-control'])?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?=Html::activeLabel($model, 'text')?>
        <?=\dosamigos\ckeditor\CKEditor::widget([
            'model' => $model,
            'attribute' => 'text',
            'clientOptions' => [
                'filebrowserUploadUrl' => 'image-upload?source=text&tempHash='.$hash,
                'allowedContent' => true,
                'on' => [
                    'insertElement' => new \yii\web\JsExpression('function(e) {
                        var hostField = $("#seotext-origin_id"); 
                        if(hostField.length > 0)
                        {
                            var imgSrc = e.data.getAttribute("src"),
                                host = hostField.find("option:selected").text().replace("http://", "");
                                
                            if(imgSrc !== null)
                            {
                                var url = new URL(imgSrc);   
                                url.host = "'.\Yii::$app->getModule('seo')->subDomain.'" + host;
                                e.data.setAttribute("src", url);
                                e.data.setAttribute("data-cke-saved-src", url);
                            }
                        }
                    }')
                ]
            ]
        ]);?>
    </div>
</div>
<div class="row margin-top10">
        <div class="col-md-8">
            <?=\yii\helpers\Html::activeLabel($model, 'template_id')?>
            <div class="row template-control">
                <div class="col-md-8">
                    <?=Html::activeDropDownList($model, 'template_id', \yii\helpers\ArrayHelper::map(\digitalmonk\modules\seo\modules\text\models\SeoTextTemplate::find()->all(), 'id', 'shortText'), ['class' => 'form-control', 'prompt' => '-'])?>
                </div>
                <div class="col-md-2">
                    <div class="row">
                        <?=\yii\helpers\Html::a('Создать', '#', [
                            'id' => 'new-template-modal-but',
                            'class' => 'btn btn-block btn-primary',
                            'title' => 'Создать шаблон',
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#template-modal'
                            ]
                        ])?>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="row">
                        <?=\yii\helpers\Html::a(\yii\bootstrap\Html::icon('pencil'), '#', ['id' => 'template-edit-but', 'class' => 'btn btn-block btn-warning', 'title' => 'Редактировать шаблон', 'data' => [
                            'template-id' => $model->template_id
                        ]])?>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="row">
                        <?=\yii\helpers\Html::a(\yii\bootstrap\Html::icon('remove'), '#', ['id' => 'template-remove-but', 'class' => 'btn btn-block btn-danger', 'title' => 'Удалить шаблон', 'data' => [
                            'template-id' => $model->template_id
                        ]])?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="form-group seo-text-update-template-block">
                <?=$model->template ? $model->template->text : ''?>
            </div>
        </div>
        <div class="col-md-4">
            <?=\yii\helpers\Html::activeLabel($model, 'template_param_names')?>
            <div class="row">
                <div class="col-md-12">
                    <?php $key = 0; foreach($model->templateParamsNamesValues as $paramName => $paramValue): ?>
                        <div class="form-group">
                            <div class="row" id="var-<?=$key?>">
                                <div class="col-md-5">
                                    <?=\yii\helpers\Html::input('text', 'template-var['.$key.'][name]', $paramName, ['class' => 'form-control'])?>
                                </div>
                                <div class="col-md-5">
                                    <?=\yii\helpers\Html::input('text', 'template-var['.$key.'][value]', $paramValue, ['class' => 'form-control'])?>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <?=\yii\helpers\Html::a(\yii\bootstrap\Html::icon('remove'), '#', [
                                            'class' => 'but-remove',
                                            'data' => [
                                                'target' => '#var-'.$key
                                            ]
                                        ])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $key++; endforeach; ?>
                    <div class="row">
                        <div class="col-md-10">
                            <?=Html::activeCheckbox($model, 'params_from_url')?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10">
                                <?=\yii\helpers\Html::a('Создать переменную', '#', [
                                    'class' => 'btn btn-block btn-primary',
                                    'onClick' => 'addVarField(this); return false'
                                ])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="row sm-text-update-bottom">
    <div class="form-group">
        <div class="col-md-12">
            <?=\yii\helpers\Html::button('Создать текст', ['class' => 'btn btn-success submit'])?>
        </div>
    </div>
</div>
