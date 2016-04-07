<?php
/**
 * @author: Eugene
 * @date: 04.04.16
 * @time: 12:01
 */

namespace digitalmonk\modules\seo\assets;

use yii\web\AssetBundle;

class SeoAssets extends AssetBundle
{
    public $sourcePath = '@digitalmonk/modules/seo/assets/assets';

    public $css = [
        'css/style.css'
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        '\yii\web\YiiAsset'
    ];

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
}