<?php

use Phpmig\Migration\Migration;

class AddTag extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $c = $this->getContainer();
        $data = [
            ['name' => 'tag1'],
            ['name' => 'tag2'],
            ['name' => 'tag3'],
            ['name' => 'tag4'],
            ['name' => 'tag5'],
            ['name' => 'tag6'],
            ['name' => 'tag7'],
            ['name' => 'tag8'],
            ['name' => 'tag9'],
            ['name' => 'tag10'],
            ['name' => 'tag11'],
            ['name' => 'tag12'],
            ['name' => 'tag13'],
            ['name' => 'tag14'],
            ['name' => 'tag15'],
            ['name' => 'tag16'],
            ['name' => 'tag17'],
            ['name' => 'tag18'],
            ['name' => 'tag19'],
            ['name' => 'tag20'],
            ['name' => 'tag21'],
        ];
        foreach ($data as $tag) {
            $c['repository.tag']->insert($tag);
        }
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $c = $this->getContainer();
        $c['pdo.master']->query('set foreign_key_checks = 0');
        $c['pdo.master']->query('TRUNCATE TABLE tag');
        $c['pdo.master']->query('set foreign_key_checks = 1');
    }
}
