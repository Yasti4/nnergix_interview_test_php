<?php

declare(strict_types=1);

namespace AML\Domain\ValueObject;

use Ramsey\Uuid\{Uuid, UuidInterface};
use Webmozart\Assert\Assert;

abstract class Id
{
    /** @var UuidInterface */
    protected $id;

    private function __construct(UuidInterface $uuid = null)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->id = $uuid ?? Uuid::uuid4();
    }

    public static function generate()
    {
        return new static();
    }

    public static function fromString(string $uuid)
    {
        self::validateUuidFormat($uuid);

        return new static(Uuid::fromString($uuid));
    }

    private static function validateUuidFormat(string $uuid)
    {
        Assert::uuid($uuid, sprintf('Value %s is not a valid UUID4', $uuid));
    }

    public function toString(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
}
