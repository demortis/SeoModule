<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 9:47
 */
 \digitalmonk\modules\seo\assets\SeoAssets::register($this);
echo \digitalmonk\modules\seo\widgets\SeoWidget\SeoWidget::widget(['textOnly' => true]);
?>
<div class="row">
    <?=\yii\widgets\Breadcrumbs::widget([
        'links' => ['SEO']
    ])?>
</div>
<div class="row">
    <h1>SEO</h1>
    <?=\yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => 'Тексты',
                'content' => $texts
            ]
        ]
    ])?>
</div>
