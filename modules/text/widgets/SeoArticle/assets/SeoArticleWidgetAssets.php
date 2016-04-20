<?php
/**
 * @author: Eugene
 * @date: 19.04.16
 * @time: 11:30
 */

namespace digitalmonk\modules\seo\modules\text\widgets\SeoArticle\assets;


use yii\web\AssetBundle;

class SeoArticleWidgetAssets extends AssetBundle
{
    public $sourcePath = '@digitalmonk/modules/seo/modules/text/widgets/SeoArticle/assets/assets';

    public $css = [
        'css/style.css'
    ];

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
}