<?php

declare(strict_types=1);

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Migration;
use Taka512\ContainerFactory;
use Taka512\Manager\EntityManager;

class AddTag extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $objectSet = $this->getInstance(NativeLoader::class)->loadFile(__DIR__.'/Tag.yml');
        $this->getInstance(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->getInstance(EntityManager::class)->truncateTables(['tag']);
    }
}
