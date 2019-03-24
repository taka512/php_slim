<?php

use Phpmig\Migration\Migration;

class AddTagSite extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $c = $this->getContainer();
        $data = [
            ['tag_id' => '1', 'site_id' => '1'],
        ];
        foreach ($data as $tagSite) {
            $c['repository.tag_site']->insert($tagSite);
        }
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $c = $this->getContainer();
        $c['pdo.master']->query('set foreign_key_checks = 0');
        $c['pdo.master']->query('TRUNCATE TABLE tag_site');
        $c['pdo.master']->query('set foreign_key_checks = 1');
    }
}
