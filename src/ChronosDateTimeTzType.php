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
use Doctrine\DBAL\Types\DateTimeTzType;

class ChronosDateTimeTzType extends DateTimeTzType
{
    const CHRONOS_DATETIMETZ = 'chronos_datetimetz';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::CHRONOS_DATETIMETZ;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Chronos
    {
        if ($value === null) {
            return null;
        }

        $dateTime = parent::convertToPHPValue($value, $platform);

        return Chronos::instance($dateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
