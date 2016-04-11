<?php
/**
 * @author: Eugene
 * @date: 05.04.16
 * @time: 11:53
 */
\digitalmonk\modules\seo\assets\SeoAssets::register($this);
?>
<div class="row">
    <?=\yii\widgets\Breadcrumbs::widget([
        'links' => [
            [
                'label' => 'SEO',
                'url' => ['/seo']
            ],
            'Редактировать текст'
        ]
    ]);?>
</div>
<?php
    \yii\bootstrap\Modal::begin([
        'id' => 'template-modal',
        'header' => 'Новый шаблон',
        'size' => \yii\bootstrap\Modal::SIZE_LARGE
    ]);

    \yii\bootstrap\Modal::end();
?>

<div class="row sm-text-update-header">
    <div class="col-md-3">
        <h3>Настройки текста</h3>
    </div>
    <div class="col-md-2 col-md-push-7">
        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'seo-text-update-status'])?>

            <?=$form->field($model, 'status')->widget(\digitalmonk\widgets\ToggleWidget\ToggleWidget::className())->label(false)?>

        <?php \yii\widgets\ActiveForm::end()?>
    </div>
</div>
<?php $form = \yii\widgets\ActiveForm::begin([
    'id' => 'seo-text-update'
])?>
    <div class="row">
        <?php if($model->hasAttribute('origin_id')): ?>
            <div class="col-md-3">
                <?=$form->field($model, 'origin_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\modules\projects\models\Origin::find()->all(), 'id', 'url'), ['prompt' => '-'])?>
            </div>
        <?php endif; ?>
        <div class="col-md-<?=$model->hasAttribute('origin_id') ? 3 : 6?>">
            <?=$form->field($model, 'url')->textInput()?>
        </div>
        <div class="col-md-2">
            <?=$form->field($model, 'position')->textInput()?>
        </div>
        <div class="col-md-4">
            <?=$form->field($model, 'type')->dropDownList(['Статья'], ['disabled' => true])->label('Тип текста')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'text')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'clientOptions' => [
                    'filebrowserUploadUrl' => '/seo/text/image-upload',
                    'height' => 200
                ]
            ])?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <?=\yii\helpers\Html::activeLabel($model, 'template_id')?>
            <div class="row template-control">
                <div class="col-md-8">
                    <?=$form->field($model, 'template_id')->dropDownList(\yii\helpers\ArrayHelper::map(\digitalmonk\modules\seo\models\SeoTextTemplate::find()->all(), 'id', 'shortText'), ['prompt' => '-'])->label(false)?>
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
                                <?=$form->field($model, 'params_from_url')->checkbox()?>
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
            <?=\yii\helpers\Html::submitButton('Сохранить изменения', ['class' => 'btn btn-success'])?>
        </div>
    </div>
</div>
    <?php \yii\widgets\ActiveForm::end()?>



