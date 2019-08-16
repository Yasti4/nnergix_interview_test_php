<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\SearchUrl;
use Doctrine\ORM\EntityRepository;


class DoctrineInfoUrlRepository extends EntityRepository implements InfoUrlRepository
{

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(Page $page): void
    {
//        die(var_dump($page));
        $this->getEntityManager()->persist($page);
        $this->getEntityManager()->flush();
    }

    public function findUrl(SearchUrl $url): ?Page
    {
        $page = $this->find($url->value());
        return $page;
    }
}