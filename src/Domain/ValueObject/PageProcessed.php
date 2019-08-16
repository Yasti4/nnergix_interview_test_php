<?php

declare(strict_types=1);


namespace AML\Domain\ValueObject;


class PageProcessed
{

    public const PAGE = 'page';
    public const INTERNAL_LINKS = 'internal_links';
    public const EXTERNAL_LINKS = 'external_links';

    /** @var Page */
    private $page;
    /** @var SearchUrlCollection */
    private $internalLinks;
    /** @var SearchUrlCollection */
    private $externalLinks;

    public function __construct(
        Page $page,
        SearchUrlCollection $internalLinks,
        SearchUrlCollection $externalLinks)
    {
        $this->page = $page;
        $this->internalLinks = $internalLinks;
        $this->externalLinks = $externalLinks;
    }

    public function page(): Page
    {
        return $this->page;
    }

    public function internalLinks(): SearchUrlCollection
    {
        return $this->internalLinks;
    }

    public function externalLinks(): SearchUrlCollection
    {
        return $this->externalLinks;
    }


    public function value(): array
    {
        return [
            self::PAGE => $this->page,
            self::INTERNAL_LINKS => $this->externalLinks,
            self::EXTERNAL_LINKS => $this->externalLinks
        ];
    }

}
