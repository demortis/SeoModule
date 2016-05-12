<?php
/**
 * @author: Eugene
 * @date: 12.05.16
 * @time: 9:03
 */
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
        'id'=> 'text-items-box',
        'dataProvider' => $dataProvider,
        'summary' => 'Всего текстов: <strong>{totalCount}</strong>. Показано с <strong>{begin}</strong> по <strong>{end}</strong>.',
        'emptyText' => 'Не найдено ни одной статьи',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_parts/_text_list_view', ['model' => $model]);
        },
    ]); ?>

</div>
