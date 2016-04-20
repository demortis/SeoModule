<?php
/**
 * @author: Eugene
 * @date: 20.04.16
 * @time: 11:22
 */

namespace digitalmonk\modules\seo\modules\text\widgets\SeoFullArticle;


use digitalmonk\modules\seo\modules\text\models\SeoText;
use digitalmonk\modules\seo\modules\text\models\SeoTextType;
use yii\base\Widget;

class SeoFullArticle extends Widget
{
    public $model;

    private $params = [];

    public function init()
    {
        parent::init();
        $query = \Yii::$app->request->queryParams;
        if(isset($query['seoAlias']) && $query['seoAlias'] !== null)
        {
            $this->model = SeoText::findOne(['alias' => $query['seoAlias'], 'status' => SeoText::PUBLISHED, 'text_type_id' => SeoTextType::ARTICLE]);
            if($this->model !== null)
                $this->params = [
                    'article' => $this->model
                ];
        }

    }

    public function run()
    {
        if($this->model !== null)
            return $this->render('index', $this->params);
    }
}