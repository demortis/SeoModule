<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 15:40
 */

namespace digitalmonk\modules\seo\widgets\SeoWidget;

use digitalmonk\modules\seo\models\SeoText;
use yii\base\Widget;
use yii\db\Exception;
use yii\helpers\Json;

class SeoWidget extends Widget
{
    private static $positionId = 0;

    public $position;

    public $article;

    public $paramsFromUrl = false;

    public $textOnly = false;

    private $model;

    private $params = [];

    public function init()
    {
        parent::init();

        $this->position = $this->position ?: self::$positionId++;

        try {
            $criteria = [
                'position' => $this->position, 'url' => \Yii::$app->request->pathInfo, 'status' => SeoText::PUBLISHED
            ];

            if((new SeoText())->hasAttribute('origin_id'))
                $criteria = array_merge($criteria, ['origin_id' => \Yii::$app->id]);

            $this->model = SeoText::findOne($criteria);

        } catch (Exception $e){
            if($e->getName() === 'Database Exception')
                echo $this->render('error');
        }

        if($this->model !== null)
        {
            $model = $this->model;
            $this->paramsFromUrl = $model->params_from_url ?: $this->paramsFromUrl;
            $this->article = $model->text ?: $this->template;
        }

        $this->params = [
            'position' => $this->position,
            'article' => $this->article,
        ];
    }

    public function run()
    {

        if(!empty($this->article))
        {
            $render = $this->render('index', $this->params);
            return $this->textOnly ? strip_tags($render) : $render;
        }
    }

    protected function getTemplate()
    {
        if(($model = $this->model) === null || $model->template === null)
            return false;

        $article = $model->template->text;

        if($this->templateParamNames !== null) {
            foreach ($this->templateParamNames as $key => $paramName) {
                $article = @preg_replace($paramName, $this->templateParamValues[$key], $article);
            }
        }

        return $article;
    }

    protected function getTemplateParamNames()
    {
        if($this->paramsFromUrl)
        {
            $params = array_keys(\Yii::$app->request->queryParams);
            array_walk($params, function(&$item){
                $item = '/{'.$item.'}/';
            });

            return $params;
        }

        if(($model = $this->model) !== null);
            return Json::decode($model->template_param_names);
    }

    protected function getTemplateParamValues()
    {
        if($this->paramsFromUrl)
            return array_values(\Yii::$app->request->queryParams);

        if(($model = $this->model) !== null)
            return Json::decode($model->template_param_values);
    }
}