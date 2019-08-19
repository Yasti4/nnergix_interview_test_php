<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Application\Service\InfoUrlChangedInput;
use AML\Domain\Repository\InfoUrlChangeRepository;
use AML\Domain\ValueObject\{EventUrlChanged, Page, SearchUrl};
use Doctrine\ORM\{EntityRepository, OptimisticLockException, ORMException};


class DoctrineInfoUrlChangeRepository extends EntityRepository implements InfoUrlChangeRepository
{

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function persist(EventUrlChanged $eventUrlChange): void
    {
        $this->getEntityManager()->persist($eventUrlChange);
        $this->getEntityManager()->flush();
    }

    public function findUrl(SearchUrl $url): ?Page
    {
        /** @var null|Page $page */
        $page = $this->find($url->value());
        return $page;
    }

    /** @return  EventUrlChanged[] */
    public function getAll(InfoUrlChangedInput $changedInput): array
    {
        return $this->findAll();
    }
}
