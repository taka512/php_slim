<?php

namespace Taka512\Form\Admin\Tag;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class DeleteInput implements InputFilterAwareInterface
{
    private $id;
    private $name;
    private $createdAt;
    private $updatedAt;
    private $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter): void
    {
        throw new \DomainException(sprintf('%s does not allow injection of an alternate input filter', __CLASS__));
    }

    public function getInputFilter(): InputFilterInterface
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $this->inputFilter = new InputFilter();

        return $this->inputFilter;
    }

    public function exchangeArray(array $data): void
    {
        $this->id = (isset($data['id']) && '' !== $data['id']) ? $data['id'] : null;
        $this->name = (isset($data['name']) && '' !== $data['name']) ? $data['name'] : null;
        $this->createdAt = (isset($data['created_at']) && '' !== $data['created_at']) ? $data['created_at'] : null;
        $this->updatedAt = (isset($data['updated_at']) && '' !== $data['updated_at']) ? $data['updated_at'] : null;
    }

    public function getArrayCopy(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
