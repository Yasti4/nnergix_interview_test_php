<?php

namespace AML\Infrastructure\Persistence\Doctrine\Types;

use AML\Domain\ValueObject\SearchHeader;
use AML\Domain\ValueObject\SearchUrlHeaderCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class SearchUrlHeaderArrayType extends Type
{
    public const NAME = 'search_url_header_array_type';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "TEXT";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        /** @var array $rawHeaders */
        $rawHeaders = json_decode($value, true);

        return array_map(function ($rawHeader) {
                $rawHeader = json_decode($rawHeader, true);
                return new SearchHeader($rawHeader[SearchHeader::KEY], $rawHeader[SearchHeader::HEADER]);
            }, $rawHeaders);
    }

    /** @param SearchHeader[] $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return json_encode(array_map(function ($header) {
            /** @var SearchHeader $header */
            return json_encode($header->value());
        }, $value));
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
