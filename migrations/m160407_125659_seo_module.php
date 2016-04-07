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

        $this->createTable('seo_text', [
            'url' => Schema::TYPE_STRING.'(255) NOT NULL',
            'position' => Schema::TYPE_SMALLINT.'(2) NOT NULL',
            'text' => Schema::TYPE_TEXT,
            'template_id' => Schema::TYPE_INTEGER.'(11)',
            'template_param_names' => Schema::TYPE_STRING.'(510)',
            'template_param_values' => Schema::TYPE_STRING.'(510)',
            'params_from_url' => Schema::TYPE_BOOLEAN,
            'status' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER.'(11)',
            'updated_at' => Schema::TYPE_INTEGER.'(11)',
        ]);

        $this->addPrimaryKey('pk_seo_text_url_position', 'seo_text', ['url', 'position']);
        $this->addForeignKey('fk_seo_text_template_id', 'seo_text', 'template_id', 'seo_text_template', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_seo_text_template_id', 'seo_text');
        $this->dropPrimaryKey('pk_seo_text_url_position', 'seo_text');
        $this->dropTable('seo_text');
        $this->dropTable('seo_text_template');
    }
}
