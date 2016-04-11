<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 15:27
 */

namespace digitalmonk\modules\seo\models;

use app\digitalmonk\modules\seo\models\SeoTextTemplate;
use app\modules\projects\models\Origin;
use yii\behaviors\TimestampBehavior;
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
 *
 * @property SeoTextTemplate $template
 */
class SeoText extends \yii\db\ActiveRecord
{
    const PUBLISHED = 1;
    const NOT_PUBLISHED = 0;

    public $type;

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
            [['url', 'position'], 'required', 'message' => 'Поле «{attribute}» обязательно к заполнению'],
            [['position', 'template_id', 'params_from_url', 'status', 'type', 'created_at', 'updated_at'], 'integer', 'message' => 'Поле «{attribute}» может содержать только число'],
            [['text'], 'string'],
            [['text'], 'trim'],
            [['url'], 'string', 'max' => 255],
            [['template_param_names', 'template_param_values'], 'string', 'max' => 510],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeoTextTemplate::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];

        if(!$this->hasAttribute('origin_id'))
            $rules[] = [['url', 'position'], 'unique', 'targetAttribute' => ['url', 'position'], 'message' => 'Данная прозиция уже занята'];

        if($this->hasAttribute('origin_id')) {
            $rules[] = [['origin_id'], 'integer', 'message' => 'Поле «{attribute}» может содержать только число'];
            $rules[] = [['origin_id'], 'required', 'message' => 'Поле «{attribute}» обязательно к заполнению'];
            $rules[] = [['url', 'position', 'origin_id'], 'unique', 'targetAttribute' => ['url', 'position', 'origin_id'], 'message' => 'Данная прозиция уже занята'];
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
            'text' => 'Статичный текст',
            'template_id' => 'Привязанный шаблон',
            'template_param_names' => 'Привязанные переменные',
            'template_param_values' => 'Template Param Values',
            'params_from_url' => 'Брать переменные из URL',
            'status' => 'Статус',
            'type' => 'Тип текста',
            'origin_id' => 'Проект'
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

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            $this->text = HtmlPurifier::process($this->text);
            return true;
        }
        return false;
    }

}