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
        $c['pdo.master']->query('PURGE TABLE tag');
    }
}
