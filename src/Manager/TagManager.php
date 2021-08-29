<?php

namespace Taka512\Manager;

use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;
use Taka512\Repository\TagRepository;

class TagManager
{
    public const LIMIT = 10;

    private $repository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->repository = $tagRepository;
    }

    public function getTagPagenate(int $page, int $limit = self::LIMIT)
    {
        $offset = ($page - 1) * $limit;
        $tags = $this->repository->findLatestTags($offset, $limit);
        $count = $this->repository->count();
        $pagenate = new Pagerfanta(new FixedAdapter($count, $tags));
        $pagenate->setCurrentPage($page)->setMaxPerPage($limit);

        return $pagenate;
    }
}
