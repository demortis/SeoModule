<?php
/**
 * @author: Eugene
 * @date: 04.04.16
 * @time: 11:13
 */

namespace digitalmonk\modules\seo\modules\text\controllers;

use digitalmonk\modules\seo\modules\text\models\SeoText;
use digitalmonk\modules\seo\modules\text\models\SeoTextTemplate;
use digitalmonk\modules\seo\modules\text\models\SeoTextType;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\UploadedFile;

class TextController extends Controller
{
    public function actionIndex()
    {
        $model = SeoText::find()->orderBy('created_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => new Pagination([
                'pageSize' => 10
            ])
        ]);

        $params = [
            'dataProvider' => $dataProvider
        ];

        return $this->renderAjax('index', $params);
    }

    public function actionForm($id)
    {
        if(!\Yii::$app->request->isAjax) return false;
        {
            $formType = SeoTextType::findOne((int)$id);
            if($formType !== null)
            {
                $model = new SeoText();
                return $this->renderAjax('_forms/' . $formType->name, ['model' => $model]);
            }
        }
    }

    public function actionCreate()
    {
        $model = new SeoText();
        $post = \Yii::$app->request->post();
        if(!empty($post) && $model->load($post)) {
            $model->scenario = constant('digitalmonk\modules\seo\modules\text\models\SeoText::' . strtoupper('SCENARIO_' . $model->textType->name));
            if ($model->validate())
            {
                if (\Yii::$app->request->post('template-var') !== null)
                {
                    $templateParamNames = [];
                    $templateParamValues = [];
                    foreach (\Yii::$app->request->post('template-var') as $templateVar) {
                        if (empty($templateVar['name'])) continue;

                        $templateParamNames[] = '/{' . $templateVar['name'] . '}/';
                        $templateParamValues[] = $templateVar['value'];
                    }
                    $model->template_param_names = Json::encode($templateParamNames);
                    $model->template_param_values = Json::encode($templateParamValues);
                } else {
                    $model->template_param_names = '';
                    $model->template_param_values = '';
                }

                if($model->save(false) && $model->getImages() && $model->save(false)) {
                    $this->redirect(Url::toRoute('/seo'));
                } else {
                    \Yii::$app->session->setFlash('danger', 'При сохранении изображений возникли ошибки');
                    $this->redirect(Url::toRoute('/seo'));
                }

            } else
                return Json::encode($model->errors);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = SeoText::findOne((int)$id);

        $post = \Yii::$app->request->post();
        if(!empty($post) && $model !== null)
        {
            if(\Yii::$app->request->isAjax && isset($post['m']))
            {
                $model->status = (int)!$model->status;
                return $model->save();
            }

            if($model->load($post) && $model->validate())
            {
                if(isset($post['template-var']) && $post['template-var'] !== null){
                    $templateParamNames = [];
                    $templateParamValues = [];
                    foreach ($post['template-var'] as $templateVar){
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

                $this->refresh();
            } return Json::encode($model->errors);

        }

        if($id !== null)
            return $this->render('_forms/'.$model->textType->name.'_update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
         $result = SeoText::findOne((int)$id);
         if($result->deleteImages() && $result->delete()) {
             \Yii::$app->session->setFlash('success', 'Текст успешно удален.');
             $this->redirect(Url::toRoute('/seo'));
         }
    }

    public function actionTemplate($id)
    {
        if($id !== null)
            return SeoTextTemplate::findOne((int)$id)->text;
    }

    public function actionImageUpload($source, $tempHash = null, $id = null)
    {
        $uploadedFile = UploadedFile::getInstanceByName('upload');
        $mime = \yii\helpers\FileHelper::getMimeType($uploadedFile->tempName);
        $file = time()."_".$uploadedFile->name;

        if($tempHash !== null)
            $folderPath = \Yii::getAlias('@webroot').\Yii::$app->getModule('seo')->imagesPath.SeoText::TEMP_IMAGE_FOLDER.'/'.$tempHash.'/'.$source;

        if($id !== null)
            $folderPath = \Yii::getAlias('@webroot').\Yii::$app->getModule('seo')->imagesPath.SeoText::IMAGE_FOLDER.'/'.$id.'/'.$source;

        if($source == 'preview')
            FileHelper::removeDirectory($folderPath);

        if(!file_exists($folderPath)){
            if(!FileHelper::createDirectory($folderPath, 0777))
                return false;
        }
        if($tempHash !== null)
            $url = \Yii::$app->urlManager->createAbsoluteUrl('/'.\Yii::$app->getModule('seo')->imagesPath.SeoText::TEMP_IMAGE_FOLDER.'/'.$tempHash.'/'.$source.'/'.$file);

        if($id !== null)
            $url = \Yii::$app->urlManager->createAbsoluteUrl('/'.\Yii::$app->getModule('seo')->imagesPath.SeoText::IMAGE_FOLDER.'/'.$id.'/'.$source.'/'.$file);

        $uploadPath = $folderPath.'/'.$file;
        //extensive suitability check before doing anything with the file…
        if ($uploadedFile == null)
        {
            $message = "No file uploaded.";
        } else if ($uploadedFile->size == 0)
        {
            $message = "The file is of zero length.";
        } else if ($mime!="image/jpeg" && $mime!="image/png")
        {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        } else if ($uploadedFile->tempName == null)
        {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        } else {
            $message = "";
            $move = $uploadedFile->saveAs($uploadPath);

            if(!$move)
            {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }

            if($source == 'preview')
            {
                $imagine = Image::getImagine();
                $imagine->open($uploadPath)->save($folderPath . '/preview.jpg');
                unlink($uploadPath);
            }
        }

        if(isset($_GET['CKEditorFuncNum']))
        {
            $funcNum = $_GET['CKEditorFuncNum'];
            return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
        }

        if($tempHash !== null)
            return \Yii::$app->urlManager->createAbsoluteUrl('/'.\Yii::$app->getModule('seo')->imagesPath.SeoText::TEMP_IMAGE_FOLDER.'/'.$tempHash.'/'.$source.'/preview.jpg?'.rand());

        if($id !== null)
            return \Yii::$app->urlManager->createAbsoluteUrl('/'.\Yii::$app->getModule('seo')->imagesPath.SeoText::IMAGE_FOLDER.'/'.$id.'/'.$source.'/preview.jpg?'.rand());

        return \Yii::$app->urlManager->createAbsoluteUrl('/'.\Yii::$app->getModule('seo')->imagesPath.SeoText::TEMP_IMAGE_FOLDER.'/'.$tempHash.'/'.$source.'/preview.jpg?'.rand());
    }
}