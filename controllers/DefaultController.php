<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 9:45
 */

namespace digitalmonk\modules\seo\controllers;

use yii\base\Exception;
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
        try{
            return $this->module->runAction('text');
        } catch (Exception $e){
            if($e->getName() === 'Database Exception'){
                return $this->render('error', ['error' => $e]);
            }
        }
    }
    
    
}