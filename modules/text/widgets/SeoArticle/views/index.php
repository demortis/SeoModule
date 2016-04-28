<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use \digitalmonk\modules\seo\modules\text\models\SeoText;
use yii\widgets\LinkPager;

/**
 * @author: Eugene
 * @date: 19.04.16
 * @time: 9:18
 */

\digitalmonk\modules\seo\modules\text\widgets\SeoArticle\assets\SeoArticleWidgetAssets::register($this);
?>

<div class="row">
    <?php for ($r = 0, $idx = 0; $r < $rows; $r++): ?>
        <?php for ($c = 0; $c < $columns; $c++, $idx++): ?>
            <div class="col-md-<?=12/$columns?>">
                <?php if(!isset($articles[$idx])) break 2; ?>
                <article class="<?=$boxClass?>">
                    <figure class="<?=$previewImgClass?>">
                        <?php
                            $imagePath = SeoText::IMAGE_FOLDER.'/'.$articles[$idx]->id.'/preview/preview.jpg';
                            
                        ?>
                        <?=Html::a(Html::img($imagePath), \yii\helpers\Url::to($urlPrefix.$articles[$idx]->alias))?>
                    </figure>
                    <h4 class="<?=$previewHeaderClass?>">
                        <?=Html::a(StringHelper::truncate($articles[$idx]->title, 47), \yii\helpers\Url::to($urlPrefix.$articles[$idx]->alias))?>
                    </h4>
                </article>
            </div>
        <?php endfor; ?>
    <?php endfor; ?>
</div>
<?=LinkPager::widget(['pagination' => $pages]);?>
