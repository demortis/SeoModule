<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 15:40
 */

namespace digitalmonk\modules\seo\modules\text\widgets\SeoText;

use digitalmonk\modules\seo\modules\text\models\SeoText as SeoTextModel;
use yii\base\Widget;
use yii\db\Exception;
use yii\helpers\Json;

class SeoText extends Widget
{
    private static $positionId = 0;

    public $position;

    public $article;

    public $paramsFromUrl = false;

    public $inheritable = false;

    public $textOnly = false;

    private $params = [];

    public function init()
    {
        parent::init();

        $this->position = $this->position ?: self::$positionId++;

        if ($this->model !== null)
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
        if (!empty($this->article))
        {
            $render = $this->render('index', $this->params);
            return $this->textOnly ? trim(strip_tags($render)) : $render;
        }
    }

    protected function getTemplate()
    {
        if (($model = $this->model) === null || $model->template === null)
            return false;

        $template = $model->template->text;

        if ($this->templateParamNames !== null)
        {
            foreach ($this->templateParamNames as $key => $paramName)
            {
                $result = @preg_replace($paramName, $this->templateParamValues[$key], $template);

                if($result !== false) $template = $result;
            }
        }

        return preg_replace('~\{.*?\}~', '', $template);
    }

    protected function getTemplateParamNames()
    {
        if ($this->paramsFromUrl)
        {
            $params = array_keys(\Yii::$app->request->queryParams);
            array_walk($params, function (&$item) {
                $item = '/{' . $item . '}/';
            });
            return $params;
        }

        if (($model = $this->model) !== null)
            return Json::decode($model->template_param_names);
    }

    protected function getTemplateParamValues()
    {
        if ($this->paramsFromUrl)
            return array_values(\Yii::$app->request->queryParams);

        if (($model = $this->model) !== null)
            return Json::decode($model->template_param_values);
    }

    protected function getUrl()
    {
        $urlParts = explode('/', \Yii::$app->request->pathInfo);
        $urlPartsLength = count($urlParts);
        for ($i = $urlPartsLength-1; $i >= 0; $i--)
        {
            $url = '';
            foreach ($urlParts as $urlPart)
            {
                $url .= $urlPart.'/';
            }
            unset($urlParts[$i]);
            $urls[] = substr($url, 0, -1);  // Убираем последний символ '/'
        }
        $urls[] = '';                       // Добавлено пустое значение для поиска статей в корне
        return $urls;
    }

    protected function getModel()
    {
        try {
            $criteria = [
                'position' => $this->position, 'url' => $this->url, 'status' => SeoTextModel::PUBLISHED
            ];

            if ((new SeoTextModel())->hasAttribute('origin_id'))
                $criteria = array_merge($criteria, ['origin_id' => \Yii::$app->id]);

            $models = SeoTextModel::findAll($criteria);
            if(!empty($models))
            {
                $actualModel = null;
                foreach ($models as $key => $model)
                {
                    if (\Yii::$app->request->pathInfo == $model->url) $actualModel = $model;
                    if (!$model->inheritable) unset($models[$key]);
                }
                return !empty($models) ? current($models) : $actualModel;
            }
        } catch (Exception $e) {
            if ($e->getName() === 'Database Exception')
                echo $this->render('error');
        }
    }

}