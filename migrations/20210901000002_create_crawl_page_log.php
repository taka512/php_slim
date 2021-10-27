<?php

use Taka512\Migration;

class CreateCrawlPageLog extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `crawl_page_log` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `crawl_target_page_id` int NOT NULL COMMENT 'クロール対象ページID',
  `crawled_at` datetime NOT NULL COMMENT 'クロール日時',
  `title` varchar(255) DEFAULT NULL COMMENT 'クロールしたページのタイトル',
  `content` text DEFAULT NULL COMMENT 'クロールしたページのコンテンツ',
  `html` text DEFAULT NULL COMMENT 'クロールしたページのHTML',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日',
  foreign key(crawl_target_page_id) references crawl_target_page(id) on delete cascade,
  INDEX idx_page_id_crawled_at(crawl_target_page_id, crawled_at),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'クロールのログ'
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
