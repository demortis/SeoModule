<?php
/**
 * @author: Eugene
 * @date: 06.04.16
 * @time: 15:14
 */

namespace digitalmonk\modules\seo\controllers;


use app\digitalmonk\modules\seo\models\SeoTextTemplate;
use digitalmonk\modules\seo\models\SeoText;
use yii\helpers\Json;
use yii\web\Controller;

class TextTemplateController extends Controller
{
    public function actionCreate()
    {
        $model = new SeoTextTemplate();

        if($model->load(\Yii::$app->request->post()) && $model->save())
            return Json::encode($model);

        return $this->renderAjax('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        if(($model = SeoTextTemplate::findOne((int)$id)) !== null)
        {
            if ($model->load(\Yii::$app->request->post()) && $model->save())
                return Json::encode($model);

            return $this->renderAjax('update', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        if($id !== null){
            $models = SeoText::findAll(['template_id' => $id]);

            if($models !== null){
                foreach ($models as $model) {
                    $model->template_id = null;
                    $model->save();
                }
                if(SeoTextTemplate::deleteAll(['id' => (int)$id]))
                    return Json::encode(['id' => $id]);
            }
        }
    }
}