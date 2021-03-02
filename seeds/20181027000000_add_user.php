<?php

declare(strict_types=1);

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Migration;
use Taka512\Manager\EntityManager;
use Taka512\Model\User;

class AddUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $objectSet = $this->getInstance(NativeLoader::class)->loadFile(__DIR__.'/User.yml');
        $this->getInstance(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->getInstance(EntityManager::class)->truncateTables(['user']);
    }
}
