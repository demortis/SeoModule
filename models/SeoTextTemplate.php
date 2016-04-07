<?php
/**
 * @author: Eugene
 * @date: 31.03.16
 * @time: 15:30
 */

namespace app\digitalmonk\modules\seo\models;


use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;

class SeoTextTemplate extends ActiveRecord
{
    public static function tableName()
    {
        return 'seo_text_template';
    }

    public function rules()
    {
        return [
            ['text', 'required'],
            ['text', 'string'],
            ['text', 'trim']
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => 'Текст шаблона'
        ];
    }

    public function getShortText()
    {
        return StringHelper::truncate($this->text, 50, '...');
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->text = HtmlPurifier::process($this->text);
            return true;
        }
        return false;
    }


}