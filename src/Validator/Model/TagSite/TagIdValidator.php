<?php

namespace Taka512\Validator\Model\TagSite;

use Zend\Validator\AbstractValidator;
use Taka512\Repository\TagRepository;
use Taka512\Repository\TagSiteRepository;

class TagIdValidator extends AbstractValidator
{
    const INVALID_FMT = 'InvalidFmt';
    const NOT_FOUND_ID = 'NotFoundId';
    const DUPLICATE_ID = 'DuplicateId';

    const MSG_REQUIRE = 'タグidの入力は必須です';
    const MSG_INVALID_FMT = 'タグIDの書式が正しくありません';
    const MSG_NOT_FOUND_ID = '指定idのタグは存在しません';
    const MSG_DUPLICATE_ID = 'タグは既に登録済みです';

    protected $tagRepository;
    protected $tagSiteRepository;
    protected $messageTemplates = [
        self::INVALID_FMT => self::MSG_INVALID_FMT,
        self::NOT_FOUND_ID => self::MSG_NOT_FOUND_ID,
        self::DUPLICATE_ID => self::MSG_DUPLICATE_ID,
    ];

    public function isValid($value, ?array $context = null): bool
    {
        if (empty($value)) {
            $this->error(self::INVALID_FMT);

            return false;
        }

        $tag = $this->tagRepository->findOneById($value);
        if (is_null($tag)) {
            $this->error(self::NOT_FOUND_ID);

            return false;
        }

        if (empty($context['site_id'])) {
            return false;
        }

        if ($this->isDuplicated($value, $context['site_id'])) {
            $this->error(self::DUPLICATE_ID);

            return false;
        }

        return true;
    }

    public function isDuplicated($tagId, $siteId): bool
    {
        $tagSite = $this->tagSiteRepository->findOneByTagSite($tagId, $siteId);

        return isset($tagSite);
    }

    public function setTagRepository(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function setTagSiteRepository(TagSiteRepository $tagSiteRepository)
    {
        $this->tagSiteRepository = $tagSiteRepository;
    }
}
