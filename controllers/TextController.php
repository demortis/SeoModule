<?php
/**
 * @author: Eugene
 * @date: 04.04.16
 * @time: 11:13
 */

namespace digitalmonk\modules\seo\controllers;


use digitalmonk\modules\seo\models\SeoTextTemplate;
use digitalmonk\modules\seo\models\SeoText;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class TextController extends Controller
{
    const IMAGE_FOLDER = '/images';

    public function actionIndex()
    {
        $model = SeoText::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $model
        ]);

        $params = [
            'dataProvider' => $dataProvider
        ];

        return $this->renderPartial('index', $params);
    }

    public function actionCreate()
    {
        $model = new SeoText();

        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            if(\Yii::$app->request->post('template-var') !== null){
                $templateParamNames = [];
                $templateParamValues = [];
                foreach (\Yii::$app->request->post('template-var') as $templateVar){
                    if(empty($templateVar['name'])) continue;

                    $templateParamNames[] = '/{'.$templateVar['name'].'}/';
                    $templateParamValues[] = $templateVar['value'];
                }

                $model->template_param_names = Json::encode($templateParamNames);
                $model->template_param_values = Json::encode($templateParamValues);
            } else {
                $model->template_param_names = '';
                $model->template_param_values = '';
            }

            $model->save(false);

            $this->redirect(Url::toRoute('/seo'));
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($url, $position, $origin = null)
    {
        $criteria = [
            'url' => $url,
            'position' => $position
        ];

        if($origin !== null)
            $criteria = array_merge($criteria, ['origin_id' => (int)$origin]);

        $model = SeoText::findOne($criteria);

        if(!empty(\Yii::$app->request->post()) && $model !== null)
        {
            if(\Yii::$app->request->isAjax){
                $model->status = (int)!$model->status;
                return $model->save();
            }

            if($model->load(\Yii::$app->request->post()) && $model->validate())
            {
                if(\Yii::$app->request->post('template-var') !== null){
                    $templateParamNames = [];
                    $templateParamValues = [];
                    foreach (\Yii::$app->request->post('template-var') as $templateVar){
                        if(empty($templateVar['name'])) continue;

                        $templateParamNames[] = '/{'.$templateVar['name'].'}/';
                        $templateParamValues[] = $templateVar['value'];
                    }

                    $model->template_param_names = Json::encode($templateParamNames);
                    $model->template_param_values = Json::encode($templateParamValues);
                } else {
                    $model->template_param_names = '';
                    $model->template_param_values = '';
                }

                $isNewUrl = $model->oldAttributes['url'] != $model->url || $model->oldAttributes['position'] != $model->position;

                if($model->hasAttribute('origin_id'))
                    $isNewUrl = $isNewUrl || $model->oldAttributes['origin_id'] != $model->origin_id;

                $model->save(false);

                if(!\Yii::$app->request->isAjax)
                    \Yii::$app->session->setFlash('success', 'Настройки текста успешно обновлены');

                if($isNewUrl){
                    $params = ['',  'url' => $model->url, 'position' => $model->position];
                    if($model->hasAttribute('origin_id'))
                        $params['origin'] = $model->origin_id;

                    $this->redirect($params);
                }
            }
        }

        if($url !== null && $position !== null){

            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($url, $position, $origin = null)
    {
        if($url !== null && $position !== null) {
            $criteria = [
                'url' => $url,
                'position' => $position
            ];

            if($origin !== null)
                $criteria = array_merge($criteria, ['origin_id' => (int)$origin]);

            $result = SeoText::deleteAll($criteria);
            if($result) {
                \Yii::$app->session->setFlash('success', 'Текст успешно удален.');
                $this->redirect(Url::toRoute('/seo'));
            }
        }
    }

    public function actionGetTemplate($id){
        if($id !== null)
            return SeoTextTemplate::findOne((int)$id)->text;
    }

    public function actionImageUpload()
    {
        $uploadedFile = UploadedFile::getInstanceByName('upload');
        $mime = \yii\helpers\FileHelper::getMimeType($uploadedFile->tempName);
        $file = time()."_".$uploadedFile->name;

        $url = \Yii::$app->urlManager->createAbsoluteUrl('/'.self::IMAGE_FOLDER.'/'.$file);
        $uploadPath = \Yii::getAlias('@webroot').self::IMAGE_FOLDER.'/'.$file;
        //extensive suitability check before doing anything with the file…
        if ($uploadedFile==null)
        {
            $message = "No file uploaded.";
        }
        else if ($uploadedFile->size == 0)
        {
            $message = "The file is of zero length.";
        }
        else if ($mime!="image/jpeg" && $mime!="image/png")
        {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        }
        else if ($uploadedFile->tempName==null)
        {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        }
        else {
            $message = "";
            $move = $uploadedFile->saveAs($uploadPath);
            if(!$move)
            {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
        }
        $funcNum = $_GET['CKEditorFuncNum'] ;
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }
}