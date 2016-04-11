<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 9:41
 */

namespace digitalmonk\modules\seo;

use yii\base\Module;

class SeoModule extends Module
{
    public $controllerNamespace = 'digitalmonk\modules\seo\controllers';

    public function init()
    {
        parent::init();

        \Yii::configure($this, require($this->basePath.'/config/config.php'));
    }
}