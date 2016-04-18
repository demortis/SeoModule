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
//        try {
            $params = [];
            foreach ($this->module->modules as $key => $module) {
                $params['items'][] = [
                    'label' => isset($module['params']['label']) ? $module['params']['label'] : $key,
                    'content' => $this->module->runAction(isset($module['params']['defaultAction']) ? $key.'/'.$module['params']['defaultAction'] : $key)
                ];
            }
//        } catch (\Exception $e){
//            return $this->render('error');
//        }

        return $this->render('index', $params);
    }


    
    
}