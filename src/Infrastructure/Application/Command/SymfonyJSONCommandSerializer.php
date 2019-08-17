<?php

declare(strict_types=1);

namespace AML\Infrastructure\Application\Command;

use AML\Application\Bus\Command;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class SymfonyJSONCommandSerializer implements CommandSerializer
{
    /** @var string */
    private const FORMAT = 'json';
    /** @var string */
    private const TYPE_PROPERTY = 'type';
    /** @var string */
    private const DATA_PROPERTY = 'data';
    /** @var SerializerInterface */
    private $serializer;

    public function __construct()
    {
        $this->serializer = new class([new ObjectNormalizer()], [new JsonEncoder()]) extends SymfonySerializer
        {
        };
    }

    public function serialize(Command $command): string
    {
        return ''.json_encode([
                self::TYPE_PROPERTY => get_class($command),
                self::DATA_PROPERTY => $this->serializer->serialize($command, self::FORMAT)
            ]);
    }

    public function deserialize(string $data): Command
    {
        $data = json_decode($data, true);

        /** @var Command $command */
        $command = $this->serializer->deserialize($data[self::DATA_PROPERTY], $data[self::TYPE_PROPERTY], self::FORMAT);

        return $command;
    }

}
