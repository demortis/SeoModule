<?php

use yii\db\Migration;

class m160408_080152_seo_origin extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('seo_text', 'origin_id', \yii\db\Schema::TYPE_INTEGER.'(11)');
        $this->dropPrimaryKey('pk_seo_text_url_position', 'seo_text');
        $this->addPrimaryKey('pk_seo_text_url_position_origin_id', 'seo_text', ['url', 'position', 'origin_id']);
    }

    public function safeDown()
    {
        $this->dropPrimaryKey('pk_seo_text_url_position_origin_id', 'seo_text');
        $this->addPrimaryKey('pk_seo_text_url_position', 'seo_text', ['url', 'position']);
        $this->dropColumn('seo_text', 'origin_id');
    }
}
