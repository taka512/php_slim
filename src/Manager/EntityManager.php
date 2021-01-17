<?php

namespace Taka512\Manager;

class EntityManager
{
    private $dbh;

    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    public function transaction(callable $func)
    {
        $startTran = false;
        try {
            if (!$this->dbh->inTransaction()) {
                $this->dbh->beginTransaction();
                $startTran = true;
            }
            $ret = call_user_func($func);
            if ($startTran) {
                $this->dbh->commit();
            }
        } catch (\Exception $e) {
            if ($startTran) {
                $this->dbh->rollBack();
            }
            throw $e;
        }

        return $ret;
    }

    public function rollback(callable $func)
    {
        $startTran = false;
        try {
            if (!$this->dbh->inTransaction()) {
                $this->dbh->beginTransaction();
                $startTran = true;
            }
            $ret = call_user_func($func);
            if ($startTran) {
                $this->dbh->rollBack();
            }
        } catch (\Exception $e) {
            if ($startTran) {
                $this->dbh->rollBack();
            }
            throw $e;
        }

        return $ret;
    }

    public function bulkInsertObjects(array $objects): int
    {
        return $this->transaction(function () use ($objects) {
            foreach ($objects as $object) {
                $object->save();
            }

            return count($objects);
        });
    }

    public function truncateTables(array $tables): void
    {
        $this->dbh->exec('set FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) {
            $this->dbh->exec(sprintf('TRUNCATE TABLE %s', $table));
        }
        $this->dbh->exec('set FOREIGN_KEY_CHECKS=1');
    }
}
