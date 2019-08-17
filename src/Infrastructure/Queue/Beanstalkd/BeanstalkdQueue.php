<?php

declare(strict_types=1);


namespace AML\Infrastructure\Queue\Beanstalkd;

use AML\Infrastructure\Queue\{QueueOption, QueueService};
use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;

class BeanstalkdQueue implements QueueService
{

    /** @var PheanstalkInterface */
    private $beanstalkd;
    /** @var Job[] */
    private $jobs;
    /** @var string */
    private $tube;

    public function __construct(string $host, int $port, int $timeout, string $tube)
    {
        $this->beanstalkd = Pheanstalk::create($host, $port, $timeout)->useTube($tube)->watchOnly($tube);
        $this->tube = $tube;
        $this->jobs = [];
    }

    /** @param mixed $message */
    public function enqueue($message, array $options = []): void
    {
        $this->useTubeIfExists($options);

        $this->beanstalkd->useTube($this->tube)->watchOnly($this->tube)->put(
            $message,
            PheanstalkInterface::DEFAULT_PRIORITY,
            PheanstalkInterface::DEFAULT_DELAY,
            );
    }

    public function dequeue(array $options = [])
    {
        $this->useTubeIfExists($options);
        return $this->getJobData();
    }


    private function useTubeIfExists(array $options): void
    {
        $key = QueueOption::QUEUE_NAME()->getKey();

        if (isset($options[$key])) {
            $this->tube = $options[$key];
            $this->beanstalkd->useTube($this->tube)->watchOnly($this->tube);
        }
    }

    private function getJobData(): string
    {
        return $this->jobs[$this->tube]->getData();
    }

}
