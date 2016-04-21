<?php
/**
 * @author: Eugene
 * @date: 13.04.16
 * @time: 10:31
 */

$assets = digitalmonk\modules\seo\modules\text\assets\TextModuleAssets::register($this);

use \yii\helpers\Html;

$hash = uniqid('temp_');
?>
<?=Html::activeHiddenInput($model, 'tempHash', ['value' => $hash]);?>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="form-group col-md-6">
                <?=Html::activeLabel($model, 'title')?>
                <?php echo \digitalmonk\widgets\TranslitWidget\TranslitWidget::widget([
                    'model' => $model,
                    'attribute' => 'title',
                    'class' => 'form-control',
                    'translitTargetClass' => 'alias',
                ]);?>
                <?=Html::error($model, 'title', ['class' => 'help-block help-block-error'])?>
            </div>
            <div class="form-group col-md-6">
                <?=Html::activeLabel($model, 'alias')?>
                <?=Html::activeTextInput($model, 'alias', [
                    'class' => 'form-control alias'
                ])?>
                <?=Html::error($model, 'alias', ['class' => 'help-block help-block-error'])?>
            </div>
        </div>
        <div class="form-group">
            <?=Html::activeLabel($model, 'text')?>
            <?=\dosamigos\ckeditor\CKEditor::widget([
                'model' => $model,
                'attribute' => 'text',
                'clientOptions' => [
                    'filebrowserUploadUrl' => 'image-upload?source=article&tempHash='.$hash,
                    'allowedContent' => true
                ]
            ]);?>
            <?=Html::error($model, 'text', ['class' => 'help-block help-block-error'])?>
        </div>
    </div>
    <div class="col-md-4">
        <?=Html::activeLabel($model, 'section_id')?>
        <?=Html::activeDropDownList($model, 'section_id', \yii\helpers\ArrayHelper::map(\digitalmonk\modules\seo\modules\text\models\SeoArticleSection::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => '-'])?>
        <br>
        <?=Html::label('Предпросмотр')?>
        <div class="article-preview-box">
                <div class="article-preview-image-box">
                    <img>
                    <?=Html::fileInput('article-preview-img')?>
                </div>
            <div class="article-preview-title-box">Заголовок</div>
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
            <?=\yii\helpers\Html::button('Создать статью', ['class' => 'btn btn-success submit'])?>
        </div>
    </div>
</div>
