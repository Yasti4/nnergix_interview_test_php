<?php

namespace AML\Infrastructure\Persistence\Doctrine\Types;

use AML\Domain\ValueObject\SearchUrl;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class SearchUrlType extends Type
{
    public const NAME = 'search_url_type';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "TEXT";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new SearchUrl($value);
    }

    /** @param SearchUrl $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value;
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
