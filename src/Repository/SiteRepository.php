<?php

namespace Taka512\Repository;

use Illuminate\Database\Eloquent\Collection;
use Taka512\Model\Site;

class SiteRepository
{
    public function insert(array $data): bool
    {
        $user = new Site();
        $user->setFormArray($data);

        return $user->save();
    }

    public function findOneById(int $id): ?Site
    {
        return Site::find($id);
    }

    public function findLatestSites(int $limit = 10): Collection
    {
        return Site::where('del_flg', Site::FLG_OFF)->orderBy('id', 'desc')->take($limit)->get();
    }
}
