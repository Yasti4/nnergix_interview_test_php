<?php

namespace AML\Infrastructure\Persistence\Doctrine\Types;

use AML\Domain\ValueObject\SearchHeader;
use AML\Domain\ValueObject\SearchUrlHeaderCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EventUrlChangedArrayType extends Type
{
    public const NAME = 'event_url_changed_array_type';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "TEXT";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {

    }

    /** @param SearchHeader[] $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {

    }

    public function getName()
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
