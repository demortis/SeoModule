<?php
/**
 * @author: Eugene
 * @date: 13.04.16
 * @time: 13:58
 */

namespace digitalmonk\modules\seo\modules\text\models;

use Yii;

/**
 * This is the model class for table "seo_article_section".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parant_id
 *
 * @property SeoArticleSection $parant
 * @property SeoArticleSection[] $seoArticleSections
 */
class SeoArticleSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_article_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parant_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['parant_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeoArticleSection::className(), 'targetAttribute' => ['parant_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parant_id' => 'Parant ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParant()
    {
        return $this->hasOne(SeoArticleSection::className(), ['id' => 'parant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeoArticleSections()
    {
        return $this->hasMany(SeoArticleSection::className(), ['parant_id' => 'id']);
    }
}