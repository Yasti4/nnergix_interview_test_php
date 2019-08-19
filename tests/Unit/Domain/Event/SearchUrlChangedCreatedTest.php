<?php

declare(strict_types=1);

namespace AML\Tests\Unit\Domain\Event;

use AML\Domain\Event\DomainEventListener;
use AML\Domain\Event\DomainEventPublisher;
use AML\Domain\Event\SearchUrlChangedCreated;
use PHPUnit\Framework\TestCase;

class SearchUrlChangedCreatedTest extends TestCase
{

    public function test_should_publish_an_event()
    {
        $event = new SearchUrlChangedCreated('https://simplehtmldom.sourceforge.io/');
        /** @var SpyDomainEventListener $listenerProphesied */
        $listenerProphesied = new SpyDomainEventListener();
        $listenerProphesied->isSubscribedTo($event);
        $listenerProphesied->handle($event);


        $domainEventPublisher = DomainEventPublisher::instance($listenerProphesied);
        $domainEventPublisher->publish($event);
        $id = $domainEventPublisher->subscribe($listenerProphesied);
        $this->assertEquals(1, $id);
        $this->assertEquals($listenerProphesied->domainEvent(), $event);
    }

}
