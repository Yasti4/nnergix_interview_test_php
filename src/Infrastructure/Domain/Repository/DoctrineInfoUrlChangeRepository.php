<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Domain\Repository\InfoUrlChangeRepository;
use AML\Domain\ValueObject\{Page, SearchUrl};
use Doctrine\ORM\{EntityRepository, OptimisticLockException, ORMException};

class DoctrineInfoUrlChangeRepository extends EntityRepository implements InfoUrlChangeRepository
{

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function persist(array $urlChange): void // TODO: Change
    {
        $this->getEntityManager()->persist($urlChange);
        $this->getEntityManager()->flush();
    }

    public function findUrl(SearchUrl $url): ?Page
    {
        /** @var null|Page $page */
        $page = $this->find($url->value());
        return $page;
    }


    public function getAll(): array
    {
        $urlChanged = $this->findAll();
        return $urlChanged; // TODO: Change
    }
}
