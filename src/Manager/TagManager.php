<?php

namespace Taka512\Manager;

use Taka512\Repository\TagRepository;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;

class TagManager
{
    const LIMIT = 10;
    protected $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
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
