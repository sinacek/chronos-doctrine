<?php
/**
 * Created by PhpStorm.
 * User: suigintou
 * Date: 22.06.17
 * Time: 19:39
 */

namespace Warhuhn\Doctrine\DBAL\Types;


use Cake\Chronos\ChronosDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;

class ChronosDateType extends Type
{
    public const NAME = 'chronos_date';

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDateTypeDeclarationSQL($column);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ChronosDate) {
            return $value->format($platform->getDateFormatString());
        }

        throw InvalidType::new($value, static::class, ['null', ChronosDate::class]);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ChronosDate
    {
        if ($value === null || $value instanceof ChronosDate) {
            return null;
        }

        try {
            return ChronosDate::createFromFormat($platform->getDateFormatString(), $value);
        } catch (\InvalidArgumentException) {
            throw InvalidFormat::new(
                $value,
                static::class,
                $platform->getDateTimeFormatString()
            );
        }
    }
}
