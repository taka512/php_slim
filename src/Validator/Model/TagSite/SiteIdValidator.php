<?php

namespace Taka512\Validator\Model\TagSite;

use Taka512\Repository\SiteRepository;
use Zend\Validator\AbstractValidator;

class SiteIdValidator extends AbstractValidator
{
    const INVALID_FMT = 'InvalidFmt';
    const NOT_FOUND_ID = 'NotFoundId';

    const MSG_REQUIRE = 'サイトIDの入力は必須です';
    const MSG_INVALID_FMT = 'サイトIDの書式が正しくありません';
    const MSG_NOT_FOUND_ID = '指定IDのサイトは存在しません';

    protected $siteRepository;
    protected $messageTemplates = [
        self::INVALID_FMT => self::MSG_INVALID_FMT,
        self::NOT_FOUND_ID => self::MSG_NOT_FOUND_ID,
    ];

    public function isValid($value, ?array $context = null): bool
    {
        if (empty($value)) {
            $this->error(self::INVALID_FMT);

            return false;
        }

        $site = $this->siteRepository->findOneById($value);
        if (is_null($site)) {
            $this->error(self::NOT_FOUND_ID);

            return false;
        }

        return true;
    }

    public function setSiteRepository(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }
}
