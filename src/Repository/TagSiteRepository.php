<?php

namespace Taka512\Repository;

use Taka512\Model\TagSite;

class TagSiteRepository
{
    public function insert(array $data): TagSite
    {
        $tagSite = new TagSite();
        $tagSite->setFormArray($data);
        $now = new \DateTime();
        $tagSite->createdAt = $now->format('Y/m/d H:i:s');
        $tagSite->save();

        return $tagSite;
    }

    public function findOneByTagSite(int $tagId, int $siteId): ?TagSite
    {
        return TagSite::where('tag_id', $tagId)->where('site_id', $siteId)->first();
    }

    public function deleteBySiteId(int $siteId): int
    {
        return TagSite::where('site_id', $siteId)->delete();
    }
}
