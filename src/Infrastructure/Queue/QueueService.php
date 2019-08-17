<?php

declare(strict_types=1);


namespace AML\Infrastructure\Queue;


interface QueueService
{
    /**
     * @param mixed $message
     * @param array $options Key option must be QueueOptions key enum name
     */
    public function enqueue($message, array $options = []): void;

    /**
     * @param array $options Key option must be QueueOptions key enum name
     *
     * @return mixed
     */
    public function dequeue(array $options = []);
}
