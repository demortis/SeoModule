<div class="row">
    <div class="col-md-12 sm-text-list-item">
        <div class="row">
                    <div class="col-md-1">
                        <span class="label <?=$model->status ? 'label-success">Опубликован' : 'label-danger">Не опубликован'?></span>
                    </div>

                <div class="col-md-1 col-md-push-10">
                    <div class="sm-text-list-item-control">
                        <?=\yii\helpers\Html::a(\yii\bootstrap\Html::icon('option-horizontal'))?>
                        <div class="sm-text-list-item-menu">
                            <?php
                                $origin = $model->hasAttribute('origin_id') ? ['origin' => $model->origin_id] : [];
                            ?>
                            <?=\yii\helpers\Html::a('Редактировать', \yii\helpers\Url::toRoute(array_merge(['text/update', 'url' => $model->url, 'position' => $model->position], $origin)));?>
                            <?=\yii\helpers\Html::a('Удалить', \yii\helpers\Url::toRoute(array_merge(['text/delete', 'url' => $model->url, 'position' => $model->position], $origin)), [
                                'data' => [
                                    'action' => 'delete'
                                ]
                            ]);?>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-12 sm-text-list-item-row sm-text-list-item-text">
            <div class="col-md-12 sm-text-list-item-header">
                <div class="row">
                    <?=\yii\bootstrap\Html::icon('file')?><?=\yii\helpers\Html::activeLabel($model, 'text')?>
                </div>
            </div>
            <?=$model->text ?: '<div class="sm-empty-text">Статичный текст отсутствует</div>'?>
        </div>
        <div class="col-md-12 sm-text-list-item-row sm-text-list-item-text">
            <div class="col-md-12 sm-text-list-item-header">
                <div class="row">
                    <?=\yii\bootstrap\Html::icon('link')?><?=\yii\helpers\Html::activeLabel($model, 'template_id')?>
                </div>
            </div>
            <?=$model->template ? $model->template->text : '<div class="sm-empty-text">Шаблон отсутствует</div>'?>
        </div>
        <div class="col-md-12 sm-text-list-item-row sm-text-list-item-text">
            <div class="col-md-12 sm-text-list-item-header">
                <div class="row">
                    <?=\yii\bootstrap\Html::icon('paperclip')?><?=\yii\helpers\Html::activeLabel($model, 'template_param_names')?>
                </div>
            </div>
            <?php if($model->params_from_url): ?>
                <div class="sm-text-list-item-template-param"><span></span><span>Переменные берутсья из URL</span></div>
            <?php elseif(count($model->templateParamsNamesValues)):?>
                <?php foreach ($model->templateParamsNamesValues as $paramName => $paramValue):?>
                    <div class="sm-text-list-item-template-param"><span><?=$paramName?></span><span><?=$paramValue?></span></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sm-empty-text">Переменные отсутсвуют</div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?=\yii\bootstrap\Html::icon('globe')?> <?=\yii\helpers\Html::a($model->fullUrl, $model->fullUrl, ['target' => '_blank'])?>
            </div>
            <div class="col-md-2 col-md-push-4">
                <?=\yii\helpers\Html::activeLabel($model, 'position')?> : <?=$model->position?>
            </div>
        </div>
    </div>
</div>
