<?php
/**
 * @author: Eugene
 * @date: 12.04.16
 * @time: 15:59
 */

namespace digitalmonk\modules\seo\modules\text\controllers;

use yii\db\Exception;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        try{
            return $this->module->runAction('text');
        } catch (Exception $e){
            if($e->getName() === 'Database Exception')
                return $this->renderPartial('error', ['error' => $e]);
        }
    }
}