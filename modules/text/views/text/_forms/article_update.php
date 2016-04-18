<?php
/**
 * @author: Eugene
 * @date: 13.04.16
 * @time: 10:31
 */

$assets = digitalmonk\modules\seo\modules\text\assets\TextModuleAssets::register($this);

use \yii\helpers\Html;

?>
<?=\yii\widgets\Breadcrumbs::widget([
    'links' => [
        [
            'label' => 'SEO',
            'url' => ['/seo']
        ],
        'Редактировать статью'
    ]
]);?>
<div class="col-md-12 sm-text-update-header">
    <div class="col-md-3">
        <h3>Настройки статьи</h3>
    </div>
    <div class="col-md-2 col-md-push-7">
        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'seo-text-update-status'])?>
        <?=\yii\helpers\Html::hiddenInput('m')?>
        <?=$form->field($model, 'status')->widget(\digitalmonk\widgets\ToggleWidget\ToggleWidget::className())->label(false)?>

        <?php \yii\widgets\ActiveForm::end()?>
    </div>
</div>
<?php $form = \yii\widgets\ActiveForm::begin([
    'id' => 'seo-text-update'
])?>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <?php if($model->hasAttribute('origin_id')): ?>
                <div class="col-md-4">
                    <?=$form->field($model, 'origin_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\modules\projects\models\Origin::find()->all(), 'id', 'url'), ['prompt' => '-'])?>
                </div>
            <?php endif; ?>
            <div class="col-md-<?=$model->hasAttribute('origin_id') ? 8 : 12?>">
                <div class="form-group">
                    <?=Html::activeLabel($model, 'title')?>
                    <?=Html::activeTextInput($model, 'title', [
                        'class' => 'form-control'
                    ])?>
                    <?=Html::error($model, 'title', ['class' => 'help-block help-block-error'])?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <?=Html::activeLabel($model, 'text')?>
                <?=\dosamigos\ckeditor\CKEditor::widget([
                    'model' => $model,
                    'attribute' => 'text',
                    'clientOptions' => [
                        'filebrowserUploadUrl' => 'image-upload?source=article&id='.$model->id
                    ]
                ]);?>
                <?=Html::error($model, 'text', ['class' => 'help-block help-block-error'])?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?=Html::activeLabel($model, 'section_id')?>
        <?=Html::activeDropDownList($model, 'section_id', \yii\helpers\ArrayHelper::map(\digitalmonk\modules\seo\modules\text\models\SeoArticleSection::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => '-'])?>
        <br>
        <?=Html::label('Предпросмотр')?>
        <div class="article-preview-box">
            <div class="article-preview-image-box">
                <img <?=file_exists(\Yii::getAlias('@webroot').\digitalmonk\modules\seo\modules\text\models\SeoText::IMAGE_FOLDER.'/'.$model->id.'/preview/preview.jpg') ? 'src="'.\digitalmonk\modules\seo\modules\text\models\SeoText::IMAGE_FOLDER.'/'.$model->id.'/preview/preview.jpg"' : ''?>>
                <?=Html::fileInput('article-preview-img')?>
            </div>
            <div class="article-preview-title-box"><?=\yii\helpers\StringHelper::truncate($model->title, 47)?></div>
            <div class="article-info-box">
                <div class="article-info-visits-counter">
                    <span class="glyphicon glyphicon-eye-open"></span>999
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row sm-text-update-bottom">
    <div class="form-group">
        <div class="col-md-12">
            <?=\yii\helpers\Html::button('Сохранить изменения', ['class' => 'btn btn-success submit'])?>
        </div>
    </div>
</div>
<?php \yii\widgets\ActiveForm::end()?>
