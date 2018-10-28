<?php

use Phpmig\Migration\Migration;

class AddUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $c = $this->getContainer();
        $data = [
            ['login_id' => 'admin', 'password' => '12345678'],
        ];
        foreach ($data as $user) {
            $c['repository.user']->insert($user);
        }
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $c = $this->getContainer();
        $c['pdo.master']->query('DELETE FROM user');
    }
}
