<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 15:27
 */

namespace digitalmonk\modules\seo\modules\text\models;

use app\modules\projects\models\Origin;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use yii\helpers\HtmlPurifier;
use yii\helpers\Json;


/**
 * This is the model class for table "seo_article".
 *
 * @property string $url
 * @property integer $position
 * @property string $text
 * @property integer $template_id
 * @property string $template_param_names
 * @property string $template_param_values
 * @property integer $params_from_url
 * @property integer $inheritable
 *
 * @property SeoTextTemplate $template
 */
class SeoText extends \yii\db\ActiveRecord
{

    const IMAGE_FOLDER = '/st_images';
    const TEMP_IMAGE_FOLDER = self::IMAGE_FOLDER.'/temp';

    const PUBLISHED = 1;
    const NOT_PUBLISHED = 0;

    const SCENARIO_ARTICLE = 'article';
    const SCENARIO_TEXT = 'text';

    public $tempHash;

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['position'], 'required', 'message' => 'Поле «{attribute}» обязательно к заполнению', 'on' => self::SCENARIO_TEXT],
            [['text', 'title', 'alias'], 'required', 'message' => 'Поле «{attribute}» обязательно к заполнению', 'on' => self::SCENARIO_ARTICLE],
            [['alias'], 'unique', 'message' => 'Такой алиас уже используется'],
            [['text_type_id'], 'required', 'message' => 'Поле «{attribute}» обязательно к заполнению'],
            [['position', 'template_id', 'params_from_url', 'status', 'inheritable', 'text_type_id', 'section_id', 'created_at', 'updated_at'], 'integer', 'message' => 'Поле «{attribute}» может содержать только число'],
            [['text', 'tempHash'], 'string'],
            [['text'], 'trim'],
            [['url'], 'string', 'max' => 255],
            [['template_param_names', 'template_param_values', 'title', 'alias'], 'string', 'max' => 510],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeoTextTemplate::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];

        if(!$this->hasAttribute('origin_id'))
            $rules[] = [['url', 'position'], 'unique', 'targetAttribute' => ['url', 'position'], 'message' => 'Данная прозиция уже занята', 'on' => self::SCENARIO_TEXT];

        if($this->hasAttribute('origin_id')) {
            $rules[] = [['origin_id'], 'integer', 'message' => 'Поле «{attribute}» может содержать только число'];
            $rules[] = [['origin_id'], 'required', 'message' => 'Поле «{attribute}» обязательно к заполнению'];
            $rules[] = [['url', 'position', 'origin_id'], 'unique', 'targetAttribute' => ['url', 'position', 'origin_id'], 'message' => 'Данная прозиция уже занята', 'on' => self::SCENARIO_TEXT];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url' => 'Url страницы',
            'position' => '№ позиции',
            'title' => 'Заголовок',
            'alias' => 'Алиас',
            'text' => 'Tекст',
            'template_id' => 'Привязанный шаблон',
            'template_param_names' => 'Привязанные переменные',
            'template_param_values' => 'Template Param Values',
            'params_from_url' => 'Брать переменные из URL',
            'status' => 'Статус',
            'origin_id' => 'Проект',
            'text_type_id' => 'Тип текста',
            'section_id' => 'Раздел',
            'inheritable' => 'Наследуемый'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(SeoTextTemplate::className(), ['id' => 'template_id']);
    }

    public function getTemplateParamsNamesValues()
    {
        $templateParamNames = Json::decode($this->template_param_names) ?: [];
        $templateParamValues = Json::decode($this->template_param_values) ?: [];

        array_walk($templateParamNames, function(&$item){
            $item = preg_replace('/\/{|}\//', '', $item);
        });

        if(count($templateParamNames) === count($templateParamValues))
            return array_combine($templateParamNames, $templateParamValues);

        $result = [];
        foreach ($templateParamNames as $key => $paramName){
            $result[$paramName] = isset($templateParamValues[$key]) ? $templateParamValues[$key] : null;
        }

        return $result;
    }

    public function getFullUrl()
    {
        return $this->hasAttribute('origin_id') && isset($this->origin) ? $this->origin->url.'/'.$this->url : \Yii::$app->request->hostInfo.'/'.$this->url;
    }

    public function getOrigin()
    {
        return $this->hasOne(Origin::className(), ['id' => 'origin_id']);
    }

    public function getTextType()
    {
        return $this->hasOne(SeoTextType::className(), ['id' => 'text_type_id']);
    }

    public function getSection()
    {
        return $this->hasOne(SeoArticleSection::className(), ['id' => 'section_id']);
    }

    public function getImages()
    {

        $tempFolder = \Yii::getAlias('@webroot').\Yii::$app->getModule('seo')->imagesPath.self::TEMP_IMAGE_FOLDER.'/'.$this->tempHash;
        $staticFolder = \Yii::getAlias('@webroot').\Yii::$app->getModule('seo')->imagesPath.self::IMAGE_FOLDER.'/'.$this->id;
        if(file_exists($tempFolder))
        {
            try {
                if (!file_exists($staticFolder))
                    FileHelper::createDirectory($staticFolder);

                FileHelper::copyDirectory($tempFolder, $staticFolder);
                FileHelper::removeDirectory($tempFolder);

                $this->text = $this->convertTextUrls($this->text);

            } catch (Exception $e){

              $this->addError('text', 'При сохранении изображений возникла ошибка');
              return false;
            }
            return true;
        }
    }

    public function deleteImages()
    {
        $folderPath = \Yii::getAlias('@webroot').self::IMAGE_FOLDER.'/'.$this->id;
        if(file_exists($folderPath)){
            try {
                FileHelper::removeDirectory($folderPath);
            } catch (Exception $e){
                return false;
            }
        }
        return true;
    }

    private function convertTextUrls($text)
    {
        $dom = new \DOMDocument();
        if($dom->loadHTML($text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)){
            $elements = $dom->getElementsByTagName('img');
            foreach ($elements as $element){
                $src = $element->getAttribute('src');
                $pattern = '/temp\/temp_[a-z0-9]{13}/';
                $newSrc = preg_replace($pattern, $this->id, $src);
                $element->setAttribute('src', $newSrc);
            }
        }
        return $dom->saveHTML();
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            $this->text = trim(HtmlPurifier::process($this->text));
            return true;
        }
        return false;
    }


}