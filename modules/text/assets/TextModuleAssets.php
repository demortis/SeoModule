<?php
/**
 * @author: Eugene
 * @date: 13.04.16
 * @time: 9:02
 */

namespace digitalmonk\modules\seo\modules\text\assets;


use yii\web\AssetBundle;

class TextModuleAssets extends AssetBundle
{
    public $sourcePath = '@digitalmonk/modules/seo/modules/text/assets/assets';

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
        'forceCopy' => true
    ];
}