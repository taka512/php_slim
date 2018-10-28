<?php

namespace Taka512\Repository;

use Taka512\Model\Site;

class SiteRepository
{
    public function insert(array $data)
    {
        $user = new Site();
        $user->setFormArray($data);
        $user->save();
    }

    public function findOneById(int $id)
    {
        return Site::find($id);
    }

    public function findLatestSites(int $limit = 10)
    {
        return Site::where('del_flg', Site::FLG_OFF)->orderBy('id', 'desc')->take($limit)->get();
    }
}
