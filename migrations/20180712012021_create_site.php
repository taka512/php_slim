<?php

use Taka512\Migration;

class CreateSite extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'サイトID',
  `name` varchar(256) NOT NULL COMMENT 'サイト名',
  `url` varchar(256) NOT NULL COMMENT 'url',
  `del_flg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'サイトマスター'
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
