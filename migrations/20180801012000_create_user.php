<?php

use Phpmig\Migration\Migration;

class CreateUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ユーザID',
  `login_id` varchar(32) NOT NULL COMMENT 'ログインid',
  `password` varchar(256) NOT NULL COMMENT 'パスワード',
  `del_flg` tinyint NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日',
  PRIMARY KEY (`id`),
  UNIQUE KEY uniq_login (`login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'ユーザマスター'
EOL;
        $container = $this->getContainer();
        $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
