<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 9:45
 */

namespace digitalmonk\modules\seo\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $params = [
           'texts' => $this->texts
        ];
        return $this->render('index', $params);
    }

    protected function getTexts()
    {
        return $this->module->runAction('text');
    }
    
    
}