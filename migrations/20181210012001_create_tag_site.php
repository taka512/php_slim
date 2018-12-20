<?php

use Phpmig\Migration\Migration;

class CreateTagSite extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `tag_site` (
  `tag_id` int NOT NULL COMMENT 'タグID',
  `site_id` int NOT NULL COMMENT 'サイトID',
  `created_at` datetime NOT NULL COMMENT '登録日',
  foreign key(tag_id) references tag(id) on delete cascade,
  foreign key(site_id) references site(id) on delete cascade,
  PRIMARY KEY (`tag_id`, `site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'タグとサイトの交差テーブル'
EOL;
        $container = $this->getContainer();
        $container['pdo.master']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
