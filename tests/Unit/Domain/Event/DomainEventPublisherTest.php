<?php

declare(strict_types=1);

namespace AML\Tests\Unit\Domain\Event;

use AML\Domain\Event\DomainEventListener;
use AML\Domain\Event\DomainEventPublisher;
use PHPUnit\Framework\TestCase;

class DomainEventPublisherTest extends TestCase
{
    /** @var int */
    private const LISTENER_ID = 0;

    protected function tearDown(): void
    {
        parent::tearDown();

        DomainEventPublisher::clear();
    }

    public function test_should_add_listeners()
    {
        DomainEventPublisher::instance(new SpyDomainEventListener());

        $this->assertTrue(true);
    }

    public function test_should_get_listener_given_its_id()
    {
        $this->assertNotNull(DomainEventPublisher::instance(new SpyDomainEventListener())->ofId(self::LISTENER_ID));
    }

    public function test_should_not_found_listener_and_returns_null()
    {
        $this->assertNull(DomainEventPublisher::instance()->ofId(-1));
    }

    public function test_should_remove_listener()
    {
        DomainEventPublisher::instance(new SpyDomainEventListener())->unsubscribe(self::LISTENER_ID);

        $this->assertTrue(true);
    }

    public function test_should_publish_an_event()
    {
        $event = new FakeDomainEvent();
        /** @var DomainEventListener $listenerProphesied */
        $listenerProphesied = $this->createMock(DomainEventListener::class);
        $listenerProphesied->method('isSubscribedTo')->with($event)->willReturn(true);
        $listenerProphesied->handle($event);

        $domainEventPublisher = DomainEventPublisher::instance($listenerProphesied);
        $domainEventPublisher->publish($event);
        $id = $domainEventPublisher->subscribe($listenerProphesied);
        $this->assertEquals(1, $id);
        $domainEventListener = $domainEventPublisher->ofId($id);
        $this->assertEquals($domainEventListener, $listenerProphesied);
    }
}
