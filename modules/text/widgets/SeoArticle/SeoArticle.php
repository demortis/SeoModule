<?php
/**
 * @author: Eugene
 * @date: 19.04.16
 * @time: 9:17
 */

namespace digitalmonk\modules\seo\modules\text\widgets\SeoArticle;

use digitalmonk\modules\seo\modules\text\models\SeoText;
use digitalmonk\modules\seo\modules\text\models\SeoTextType;
use yii\data\Pagination;

class SeoArticle extends \yii\base\Widget
{
    public $rows = 2;

    public $columns = 4;

    public $urlPrefix = '';

    public $boxClass = 'article-box';
    public $previewImgClass = 'article-preview-img';
    public $previewHeaderClass = 'article-preview-header';

    private $params = [];

    public function init()
    {
        $articles = SeoText::find()->where(['text_type_id' => SeoTextType::ARTICLE, 'status' => SeoText::PUBLISHED])
            ->orderBy('created_at DESC');
//            ->all();

        $countArticles = clone $articles;

        $pages = new Pagination(['totalCount' => $countArticles->count(), 'pageSize' => 10]);

        $pages->pageSizeParam = false;

        $models = $articles->offset($pages->offset)->limit($pages->limit)->all();

        $this->params = [
            'rows' => $this->rows,
            'columns' => $this->columns,
            'articles' => $models,
            'boxClass' => $this->boxClass,
            'previewImgClass' => $this->previewImgClass,
            'previewHeaderClass' => $this->previewHeaderClass,
            'urlPrefix' => $this->urlPrefix,
            'pages' => $pages
        ];
    }

    public function run()
    {
        return $this->render('index', $this->params);
    }
}