<?php

use Phpmig\Migration\Migration;

class CreateSite extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = <<<EOL
CREATE TABLE `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'サイトID',
  `name` varchar(256) NOT NULL COMMENT 'サイト名',
  `url` varchar(256) NOT NULL COMMENT 'url',
  `del_flg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created_at` datetime NOT NULL COMMENT '登録日',
  `updated_at` datetime NOT NULL COMMENT '更新日',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8   
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
