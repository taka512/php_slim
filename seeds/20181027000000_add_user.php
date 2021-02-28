<?php

declare(strict_types=1);

use Nelmio\Alice\Loader\NativeLoader;
use Phpmig\Migration\Migration;
use Taka512\ContainerFactory;
use Taka512\Manager\EntityManager;
use Taka512\Model\User;

class AddUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/User.yml');
        ContainerFactory::getContainer()->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        ContainerFactory::getContainer()->get(EntityManager::class)->truncateTables(['user']);
    }
}
