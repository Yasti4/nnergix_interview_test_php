<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\ValueObject\{Page, SearchUrl};
use Doctrine\ORM\{EntityRepository, OptimisticLockException, ORMException};

class DoctrineInfoUrlRepository extends EntityRepository implements InfoUrlRepository
{

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function persist(Page $page): void
    {
        $this->getEntityManager()->persist($page);
        $this->getEntityManager()->flush();
    }

    public function findUrl(SearchUrl $url): ?Page
    {
        /** @var null|Page $page */
        $page = $this->find($url->value());
        return $page;
    }
}
