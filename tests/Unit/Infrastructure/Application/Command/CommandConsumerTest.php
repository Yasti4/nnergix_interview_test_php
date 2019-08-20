<?php

declare(strict_types=1);

namespace AML\Tests\Unit\Infrastructure\Application\Command;

use AML\Infrastructure\Application\Command\CommandConsumer;
use AML\Infrastructure\Application\Command\CommandSerializer;
use AML\Infrastructure\Queue\JobAlreadyExistsException;
use AML\Infrastructure\Queue\JobNotExistsException;
use AML\Infrastructure\Queue\NothingToConsumeException;
use AML\Infrastructure\Queue\QueueService;
use AML\Tests\Unit\Domain\Bus\FakeCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandConsumerTest extends TestCase
{
    /** @var array */
    private $queueOptions;
    /** @var MockObject|QueueService */
    private $queueServiceProphesied;
    /** @var MockObject|CommandSerializer */
    private $commandSerializerProphesied;
    /** @var FakeCommand */
    private $fakeCommand;
    /** @var CommandConsumer */
    private $commandConsumer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queueOptions = [];
        $this->queueServiceProphesied = $this->createMock(QueueService::class);
        $this->commandSerializerProphesied = $this->createMock(CommandSerializer::class);

        $this->fakeCommand = new FakeCommand('test');

        $this->commandConsumer = new CommandConsumer(
            $this->queueServiceProphesied,
            $this->commandSerializerProphesied
            );
    }

    public function test_consume_a_command()
    {
        $this->queueServiceProphesied->method('dequeue')->willReturn('{}');
        $this->commandSerializerProphesied->method('deserialize')->with('{}')->willReturn($this->fakeCommand);

        $command = $this->commandConsumer->consume();

        $this->assertInstanceOf(FakeCommand::class, $command);
    }

    public function test_consume_a_command_fails_because_job_already_exists()
    {
        $this->queueServiceProphesied->method('dequeue')
            ->willThrowException(new JobAlreadyExistsException(0));

        $this->commandSerializerProphesied->method('deserialize')->with('{}');

        $this->expectException(JobAlreadyExistsException::class);

        $this->commandConsumer->consume();
    }

    public function test_consume_a_command_fails_because_there_is_nothing_to_consume()
    {
        $this->queueServiceProphesied->method('dequeue')->willThrowException(new NothingToConsumeException());
        $this->commandSerializerProphesied->method('deserialize')->with('{}');

        $this->expectException(NothingToConsumeException::class);

        $this->commandConsumer->consume();
    }

    public function test_mark_as_consumed_should_call_queue_service()
    {
        $this->queueServiceProphesied->method('markAsConsumed')->with($this->queueOptions);

        $this->commandConsumer->markAsConsumed();
        $this->assertNull($this->getExpectedException());
    }


    public function test_mark_as_consumed_fails_because_job_not_exists()
    {
        $this->queueServiceProphesied->method('markAsConsumed')->with($this->queueOptions)
            ->willThrowException(new JobNotExistsException());

        $this->expectException(JobNotExistsException::class);

        $this->commandConsumer->markAsConsumed();
    }

    public function test_mark_as_failed_should_call_queue_service()
    {
        $this->queueServiceProphesied->method('markAsFailed')->with($this->queueOptions);
        $this->commandConsumer->markAsFailed();
        $this->assertNull($this->getExpectedException());
    }

    public function test_mark_as_failed_fails_because_job_not_exists()
    {
        $this->queueServiceProphesied->method('markAsFailed')->with($this->queueOptions)
            ->willThrowException(new JobNotExistsException());

        $this->expectException(JobNotExistsException::class);

        $this->commandConsumer->markAsFailed();
    }
}
