<?php

use Taka512\Migration;

class CreateCrawlTargetPage extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `crawl_target_page` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int NOT NULL COMMENT 'サイトID',
  `url` varchar(256) NOT NULL COMMENT 'クロール対象url',
  `crawl_class` varchar(32) DEFAULT NULL COMMENT 'クロール実装クラス',
  `crawl_timing` varchar(32) DEFAULT NULL COMMENT 'クロールタイミング',
  `crawl_option` json DEFAULT NULL COMMENT 'クロールのオプション',
  `deleted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '削除日',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日',
  foreign key(site_id) references site(id) on delete cascade,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'クロール対象のページ'
EOL;
        $this->getInstance(\PDO::class)->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
