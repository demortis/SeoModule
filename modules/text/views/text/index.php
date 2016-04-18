<?php
/**
 * @author: Eugene
 * @date: 04.04.16
 * @time: 14:23
 */

digitalmonk\modules\seo\modules\text\assets\TextModuleAssets::register($this);

?>
<div class="sm-header">
        <h3 class="col-md-3">Тексты</h3>

        <div class="col-md-2 col-md-push-7 sm-add-text-box">
            <div class="row">
                <?=\yii\helpers\Html::a('Добавить текст', \yii\helpers\Url::toRoute('text/create'))?>
            </div>
        </div>
</div>
<div class="col-md-12">
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'summary' => 'Всего текстов: <strong>{totalCount}</strong>. Показано с <strong>{begin}</strong> по <strong>{end}</strong>.',
        'emptyText' => 'Вы еще не создали ни одной статьи',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_parts/_text_list_view', ['model' => $model]);
        },
    ]); ?>
</div>


