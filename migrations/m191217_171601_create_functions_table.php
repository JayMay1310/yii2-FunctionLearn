<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%functions}}`.
 */
class m191217_171601_create_functions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING,
            'slug' => Schema::TYPE_STRING,
            'last_update' => Schema::TYPE_DATETIME,
        ]);
        
        $this->addForeignKey('fk-category-parent_id-category-id', '{{%category}}', 'parent_id', '{{%category}}', 'id', 'CASCADE');

        $this->createTable('{{%functions}}', [
            'id' => Schema::TYPE_PK,
            'function' => Schema::TYPE_STRING,
            'value' => Schema::TYPE_STRING,
            'link' => Schema::TYPE_STRING,
            'category_id' => Schema::TYPE_INTEGER,
            'last_update' => Schema::TYPE_DATETIME,
            'count' => Schema::TYPE_INTEGER,
        ]);

        $this->addForeignKey('fk-functions-category_id-category_id', '{{%functions}}', 'category_id', '{{%category}}', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%functions}}');
    }
}
