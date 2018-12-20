<?php

use Phpmig\Migration\Migration;

class CreateTag extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'タグID',
  `name` varchar(64) NOT NULL COMMENT 'タグ名',
  `created_at` datetime NOT NULL COMMENT '登録日',
  `updated_at` datetime NOT NULL COMMENT '更新日',
  PRIMARY KEY (`id`),
  UNIQUE KEY uniq_name (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'タグマスター'
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
