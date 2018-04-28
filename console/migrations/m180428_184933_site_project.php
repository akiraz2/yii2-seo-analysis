<?php

use yii\db\Schema;
use yii\db\Migration;

class m180428_184933_site_project extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%site_project}}',
            [
                'id'=> $this->primaryKey(11),
                'user_id'=> $this->integer(11)->notNull(),
                'name'=> $this->string(255)->notNull(),
                'base_url'=> $this->string(255)->notNull(),
                'ping'=> $this->integer(11)->notNull()->defaultValue(0),
                'ping_last_date'=> $this->integer(11)->null()->defaultValue(0),
                'reindex'=> $this->integer(11)->notNull()->defaultValue(0),
                'params'=> $this->text()->null()->defaultValue(null),
                'status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
                'created_at'=> $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
                'updated_at'=> $this->timestamp()->null()->defaultValue(null),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%site_project}}');
    }
}
