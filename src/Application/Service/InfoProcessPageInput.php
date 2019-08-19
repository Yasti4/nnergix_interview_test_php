<?php

namespace AML\Application\Service;

use AML\Domain\ValueObject\PageReference;

class InfoProcessPageInput
{
    private $pageReference;

    public function __construct(string $pageReference)
    {
        $this->pageReference = PageReference::fromString($pageReference);
    }

    public function pageReference(): ?PageReference
    {
        return $this->pageReference;
    }
}
