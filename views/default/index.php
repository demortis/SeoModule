<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 9:47
 */
 \digitalmonk\modules\seo\assets\SeoAssets::register($this);
?>

<div class="col-md-12">
    <?=\yii\widgets\Breadcrumbs::widget([
        'links' => ['SEO']
    ])?>
</div>
<div class="col-md-12">
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
