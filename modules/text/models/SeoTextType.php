<?php
/**
 * @author: Eugene
 * @date: 13.04.16
 * @time: 8:50
 */

namespace digitalmonk\modules\seo\modules\text\models;

use Yii;

/**
 * This is the model class for table "seo_text_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property SeoText[] $seoTexts
 */
class SeoTextType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_text_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Тип текста',
            'description' => 'Описание текста'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeoTexts()
    {
        return $this->hasMany(SeoText::className(), ['text_type_id' => 'id']);
    }
}