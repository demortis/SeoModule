<?php

use yii\db\Migration;

class m160408_080152_seo_origin extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('seo_text', 'origin_id', \yii\db\Schema::TYPE_INTEGER.'(11)');
    }

    public function safeDown()
    {
        $this->dropColumn('seo_text', 'origin_id');
    }
}
