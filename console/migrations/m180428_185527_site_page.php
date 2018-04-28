<?php

use yii\db\Schema;
use yii\db\Migration;

class m180428_185527_site_page extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable('{{%site_page}}',[
            'id'=> $this->bigPrimaryKey(20),
            'site_snapshot_id'=> $this->integer(11)->notNull(),
            'url'=> $this->string(255)->notNull(),
            'canonical'=> $this->string(255)->null()->defaultValue(null),
            'status_code'=> $this->string(3)->null()->defaultValue(null),
            'request_time'=> $this->integer(11)->null()->defaultValue(null),
            'title'=> $this->string(255)->null()->defaultValue(null),
            'meta_description'=> $this->string(512)->null()->defaultValue(null),
            'meta_keyword'=> $this->string(255)->null()->defaultValue(null),
            'tag_h1'=> $this->string(255)->null()->defaultValue(null),
            'og_main'=> $this->integer(11)->null()->defaultValue(0),
            'og_option'=> $this->integer(11)->null()->defaultValue(0),
            'body'=> $this->text()->null()->defaultValue(null),
            'created_at'=> $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
        ], $tableOptions);

        $this->createIndex('site_snapshot_id','{{%site_page}}',['site_snapshot_id'],false);
        $this->createIndex('url','{{%site_page}}',['url'],false);
        $this->addForeignKey(
            'fk_site_page_site_snapshot_id',
            '{{%site_page}}', 'site_snapshot_id',
            '{{%site_snapshot}}', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown()
    {
            $this->dropForeignKey('fk_site_page_site_snapshot_id', '{{%site_page}}');
            $this->dropTable('{{%site_page}}');
    }
}
