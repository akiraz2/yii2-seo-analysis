<?php

use yii\db\Schema;
use yii\db\Migration;

class m180428_185308_site_snapshot extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable('{{%site_snapshot}}',[
            'id'=> $this->primaryKey(11),
            'site_project_id'=> $this->integer(11)->null()->defaultValue(null),
            'status'=> $this->tinyInteger(4)->null()->defaultValue(0),
            'cmd'=> $this->tinyInteger(4)->null()->defaultValue(null),
            'count_pages'=> $this->integer(11)->null()->defaultValue(0),
            'robots_txt'=> $this->text()->null()->defaultValue(null),
            'sitemap_xml'=> $this->string(255)->null()->defaultValue(null),
            'error404'=> $this->integer(11)->null()->defaultValue(0),
            'error500'=> $this->integer(11)->null()->defaultValue(0),
            'redirect300'=> $this->integer(11)->null()->defaultValue(0),
            'duplicates'=> $this->integer(11)->null()->defaultValue(0),
            'created_at'=> $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
        ], $tableOptions);

        $this->createIndex('fk_project','{{%site_snapshot}}',['site_project_id'],false);
        $this->addForeignKey(
            'fk_site_snapshot_site_project_id',
            '{{%site_snapshot}}', 'site_project_id',
            '{{%site_project}}', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown()
    {
            $this->dropForeignKey('fk_site_snapshot_site_project_id', '{{%site_snapshot}}');
            $this->dropTable('{{%site_snapshot}}');
    }
}
