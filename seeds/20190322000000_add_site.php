<?php

use Phpmig\Migration\Migration;

class AddSite extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $c = $this->getContainer();
        $data = [
            ['name' => 'テストサイト', 'url' => 'https://google.com'],
        ];
        foreach ($data as $site) {
            $c['repository.site']->insert($site);
        }
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $c = $this->getContainer();
        $c['pdo.master']->query('set foreign_key_checks = 0');
        $c['pdo.master']->query('TRUNCATE TABLE site');
        $c['pdo.master']->query('set foreign_key_checks = 1');
    }
}
