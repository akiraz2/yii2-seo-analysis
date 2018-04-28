<?php

use yii\db\Schema;
use yii\db\Migration;

class m180428_185609_site_log extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable('{{%site_log}}',[
            'id'=> $this->primaryKey(11),
            'site_snapshot_id'=> $this->integer(11)->null()->defaultValue(null),
            'site_project_id'=> $this->integer(11)->null()->defaultValue(null),
            'category'=> $this->string(100)->null()->defaultValue(null),
            'message'=> $this->text()->notNull(),
            'created_at'=> $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
        ], $tableOptions);

        $this->createIndex('fk_project1','{{%site_log}}',['site_project_id'],false);
        $this->createIndex('fk_snapshot1','{{%site_log}}',['site_snapshot_id'],false);
        $this->addForeignKey(
            'fk_site_log_site_project_id',
            '{{%site_log}}', 'site_project_id',
            '{{%site_project}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_site_log_site_snapshot_id',
            '{{%site_log}}', 'site_snapshot_id',
            '{{%site_snapshot}}', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown()
    {
            $this->dropForeignKey('fk_site_log_site_project_id', '{{%site_log}}');
            $this->dropForeignKey('fk_site_log_site_snapshot_id', '{{%site_log}}');
            $this->dropTable('{{%site_log}}');
    }
}
