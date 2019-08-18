<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Domain\Repository\EventUrlChangedRepository;
use AML\Domain\ValueObject\EventUrlChanged;
use Doctrine\ORM\{EntityRepository, OptimisticLockException, ORMException};

class DoctrineEventUrlChangedRepository extends EntityRepository implements EventUrlChangedRepository
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
}
