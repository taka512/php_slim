<?php

declare(strict_types=1);

use Taka512\Migration;

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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日',
  PRIMARY KEY (`id`),
  UNIQUE KEY uniq_name (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'タグマスター'
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
