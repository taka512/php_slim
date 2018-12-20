<?php

namespace Taka512\Repository;

use Illuminate\Database\Eloquent\Collection;
use Taka512\Model\Tag;

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

    public function findLatestTags(int $limit = 10): Collection
    {
        return Tag::orderBy('id', 'desc')->take($limit)->get();
    }
}
