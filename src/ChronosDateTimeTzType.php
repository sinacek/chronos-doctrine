<?php
/**
 * Created by PhpStorm.
 * User: suigintou
 * Date: 22.06.17
 * Time: 19:37
 */

namespace Warhuhn\Doctrine\DBAL\Types;


use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;

class ChronosDateTimeTzType extends Type
{
    const NAME = 'chronos_datetimetz';

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDateTimeTzTypeDeclarationSQL($column);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Chronos) {
            return $value->format($platform->getDateTimeTzFormatString());
        }

        throw InvalidType::new($value, static::class, ['null', Chronos::class]);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Chronos
    {
        if ($value === null || $value instanceof Chronos) {
            return null;
        }

        try {
            return Chronos::createFromFormat($platform->getDateTimeTzFormatString(), $value);
        } catch (\InvalidArgumentException) {
            throw InvalidFormat::new(
                $value,
                static::class,
                $platform->getDateTimeTzFormatString()
            );
        }
    }
}
