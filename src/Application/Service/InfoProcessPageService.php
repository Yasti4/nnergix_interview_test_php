<?php

namespace AML\Application\Service;

use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\ValueObject\Page;

class InfoProcessPageService
{

    /** @var InfoUrlRepository */
    private $infoUrlRepository;

    public function __construct(
        InfoUrlRepository $infoUrlRepository
    )
    {
        $this->infoUrlRepository = $infoUrlRepository;
    }

    /** @return Page[] */
    public function __invoke(InfoProcessPageInput $input): array
    {
        return $this->infoUrlRepository->findByReference($input->pageReference());
    }
}
