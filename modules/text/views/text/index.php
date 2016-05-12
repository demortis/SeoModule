<?php
/**
 * @author: Eugene
 * @date: 04.04.16
 * @time: 14:23
 */

\yii\widgets\PjaxAsset::register($this);
digitalmonk\modules\seo\modules\text\assets\TextModuleAssets::register($this);
$this->registerJs("
    $(document).on('keyup', '#filter-form input[type=text]', function(e) {
        $.ajax({
            url : '/seo/texts',
            data: $('#filter-form').serialize(),
            beforeSend : function(){
                history.pushState($('#filter-form').serialize(), '', '".\Yii::$app->request->hostInfo."/seo?' + $('#filter-form').serialize())
            },
            success : function(data){
                $('#text-items-pjax-box').html(data);
            }
        });
    });
"
, \yii\web\View::POS_END);
?>
<div class="row filter-row">
    <?=\yii\helpers\Html::beginForm(\yii\helpers\Url::current(), 'GET', ['id'=>'filter-form']);?>
        <div class="col-md-3">
            <label for="filter-url">Поиск по URL</label>
            <?= \yii\helpers\Html::input('text', 'FilterParams[url]', isset($_GET['FilterParams']['url']) ? \Yii::$app->request->get('FilterParams')['url'] : '', ['id' => 'filter-url', 'class' => 'form-control filter-control'])?>
        </div>
        <div class="col-md-3">
            <label for="filter-url">Поиск по № позиции</label>
            <?= \yii\helpers\Html::input('text', 'FilterParams[position]', isset($_GET['FilterParams']['position']) ? \Yii::$app->request->get('FilterParams')['position'] : '', ['id' => 'filter-position', 'class' => 'form-control filter-control'])?>
        </div>
        <div class="col-md-3">
            <label for="filter-url">Поиск по тексту</label>
            <?= \yii\helpers\Html::input('text', 'FilterParams[text]', isset($_GET['FilterParams']['text']) ? \Yii::$app->request->get('FilterParams')['text'] : '', ['id' => 'filter-position', 'class' => 'form-control filter-control'])?>
        </div>
    <?=\yii\helpers\Html::endForm(); ?>
</div>
<?php \yii\widgets\Pjax::begin([
    'id' => 'text-items-pjax-box',
    'enablePushState' => false,
])?>
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
<?php \yii\widgets\Pjax::end()?>


