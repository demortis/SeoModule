<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use \digitalmonk\modules\seo\modules\text\models\SeoText;

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
                <div class="<?=$boxClass?>">
                    <div class="<?=$previewImgClass?>">
                        <?=Html::a(Html::img(SeoText::IMAGE_FOLDER.'/'.$articles[$idx]->id.'/preview/preview.jpg'), \yii\helpers\Url::to($urlPrefix.$articles[$idx]->alias))?>
                    </div>
                    <div class="<?=$previewHeaderClass?>">
                        <?=Html::a(StringHelper::truncate($articles[$idx]->title, 47), \yii\helpers\Url::to($urlPrefix.$articles[$idx]->alias))?>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    <?php endfor; ?>
</div>
