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
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ユーザID',
  `login_id` varchar(32) NOT NULL COMMENT 'ログインid',
  `password` varchar(256) NOT NULL COMMENT 'パスワード',
  `del_flg` tinyint NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created_at` datetime NOT NULL COMMENT '登録日',
  `updated_at` datetime NOT NULL COMMENT '更新日',
  PRIMARY KEY (`id`),
  UNIQUE KEY uniq_login (`login_id`)
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