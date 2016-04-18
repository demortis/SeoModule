<?php

use yii\db\Migration;
use \yii\db\Schema;

class m160407_125659_seo_module extends Migration
{
    public function safeUp()
    {
        $this->createTable('seo_text_template', [
            'id' => Schema::TYPE_PK,
            'text' => Schema::TYPE_TEXT
        ]);

        $this->createTable('seo_article_section', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING.'(255) NOT NULL',
            'parent_id' => Schema::TYPE_INTEGER.'(11)'
        ]);

        $this->createTable('seo_text_type', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING.'(255) NOT NULL',
            'description' => Schema::TYPE_STRING.'(255)',
        ]);

        $this->createTable('seo_text', [
            'id' => Schema::TYPE_PK,
            'url' => Schema::TYPE_STRING.'(255) NOT NULL',
            'position' => Schema::TYPE_SMALLINT.'(2) NOT NULL',
            'title' => Schema::TYPE_STRING.'(510)',
            'text' => Schema::TYPE_TEXT,
            'template_id' => Schema::TYPE_INTEGER.'(11)',
            'template_param_names' => Schema::TYPE_STRING.'(510)',
            'template_param_values' => Schema::TYPE_STRING.'(510)',
            'params_from_url' => Schema::TYPE_BOOLEAN,
            'inheritable' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER.'(11)',
            'updated_at' => Schema::TYPE_INTEGER.'(11)',
            'text_type_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'section_id' => Schema::TYPE_INTEGER.'(11)',
        ]);

        $this->addForeignKey('fk_seo_article_section_parent_id', 'seo_article_section', 'parent_id', 'seo_article_section', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_seo_text_template_id', 'seo_text', 'template_id', 'seo_text_template', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_seo_text_type_id', 'seo_text', 'text_type_id', 'seo_text_type', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_seo_section_id', 'seo_text', 'section_id', 'seo_article_section', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_seo_article_section_parent_id', 'seo_article_section');
        $this->dropForeignKey('fk_seo_section_id', 'seo_text');
        $this->dropForeignKey('fk_seo_text_type_id', 'seo_text');
        $this->dropForeignKey('fk_seo_text_template_id', 'seo_text');
        $this->dropTable('seo_text');
        $this->dropTable('seo_text_type');
        $this->dropTable('seo_article_section');
        $this->dropTable('seo_text_template');
    }
}
