<?php

namespace Taka512\Repository;

use Illuminate\Database\Eloquent\Collection;
use Taka512\Model\Tag;
use Taka512\Util\SqlUtil;

class TagRepository
{
    public function insert(array $data): ?int
    {
        $tag = new Tag();
        $tag->setFormArray($data);
        $tag->save();

        return $tag->id;
    }

    public function findOneById(int $id): ?Tag
    {
        return Tag::where('id', $id)->first();
    }

    public function findBySearchConditions(array $conditions): Collection
    {
        $builder = Tag::orderBy('id', 'desc')->offset($conditions['offset'])->limit($conditions['limit']);
        if (!empty($conditions['name'])) {
            $builder->where('name', 'LIKE', '%'.SqlUtil::escapeLike($conditions['name']).'%');
        }
        if (!empty($conditions['site_id'])) {
            $builder->join('tag_site', 'tag.id', '=', 'tag_site.tag_id')->where('tag_site.site_id', '=', $conditions['site_id']);
        }
        return $builder->get();
    }

    public function findLatestTags(int $limit = 10): Collection
    {
        return Tag::orderBy('id', 'desc')->limit($limit)->get();
    }
}
