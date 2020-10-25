<?php

namespace Taka512\Form\Api\TagSite;

use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\NotEmpty;
use Taka512\Repository\SiteRepository;
use Taka512\Repository\TagRepository;
use Taka512\Repository\TagSiteRepository;
use Taka512\Validator\Model\TagSite\SiteIdValidator;
use Taka512\Validator\Model\TagSite\TagIdValidator;

class CreateInput implements InputFilterAwareInterface
{
    private $tagId;
    private $siteId;
    private $tagRepository;
    private $tagSiteRepository;
    private $siteRepository;
    private $inputFilter;

    public function __construct(
        TagRepository $tagRepository,
        TagSiteRepository $tagSiteRepository,
        SiteRepository $siteRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->tagSiteRepository = $tagSiteRepository;
        $this->siteRepository = $siteRepository;
    }

    public function setInputFilter(InputFilterInterface $inputFilter): void
    {
        throw new \DomainException(sprintf('%s does not allow injection of an alternate input filter', __CLASS__));
    }

    public function getInputFilter(): InputFilterInterface
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'tag_id',
            'required' => true,
            'break_on_failure' => false,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => TagIdValidator::MSG_REQUIRE,
                        ],
                    ],
                ],
                [
                    'name' => TagIdValidator::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'tagRepository' => $this->tagRepository,
                        'tagSiteRepository' => $this->tagSiteRepository,
                    ],
                ],
            ],
        ])->add([
            'name' => 'site_id',
            'required' => true,
            'break_on_failure' => false,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => SiteIdValidator::MSG_REQUIRE,
                        ],
                    ],
                ],
                [
                    'name' => SiteIdValidator::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'siteRepository' => $this->siteRepository,
                    ],
                ],
            ],
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }

    public function exchangeArray(array $data): void
    {
        $this->tagId = empty($data['tag_id']) ? null : $data['tag_id'];
        $this->siteId = empty($data['site_id']) ? null : $data['site_id'];
    }

    public function getArrayCopy(): array
    {
        return [
            'tag_id' => $this->tagId,
            'site_id' => $this->siteId,
        ];
    }
}
