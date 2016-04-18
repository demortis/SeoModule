<?php
/**
 * @author: Eugene
 * @date: 05.04.16
 * @time: 11:53
 */
    digitalmonk\modules\seo\modules\text\assets\TextModuleAssets::register($this);
?>
<div class="row">
    <div class="col-md-12">
        <?=\yii\widgets\Breadcrumbs::widget([
            'links' => [
                [
                    'label' => 'SEO',
                    'url' => ['/seo'],
                ],
                'Добавить текст',
            ],
        ]);?>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-sm-6">
        <h3>Настройки текста</h3>
    </div>
    <div class="col-md-2 col-md-push-7 col-sm-6">
        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'seo-text-update-status'])?>

        <?=$form->field($model, 'status')->widget(\digitalmonk\widgets\ToggleWidget\ToggleWidget::className())->label(false)?>

        <?php \yii\widgets\ActiveForm::end()?>
    </div>
</div>
<div class="row">
    <?php $form = \yii\widgets\ActiveForm::begin([
        'id' => 'seo-text-update'
    ])?>
        <div class="col-md-4">
            <?=$form->field($model, 'text_type_id')->dropDownList(\yii\helpers\ArrayHelper::map(\digitalmonk\modules\seo\modules\text\models\SeoTextType::find()->all(), 'id', 'description'), ['prompt' => '-'])?>
        </div>

        <?php if($model->hasAttribute('origin_id')): ?>
            <div class="col-md-3">
                <?=$form->field($model, 'origin_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\modules\projects\models\Origin::find()->all(), 'id', 'url'), ['prompt' => '-'])?>
            </div>
        <?php endif; ?>
    <div class="clearfix"></div>
    <div class="col-md-12" id="form-box">
    </div>
    <?php \yii\widgets\ActiveForm::end()?>
</div>


