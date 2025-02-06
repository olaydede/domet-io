<?php

namespace App\DBAL;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Override datetime datatype to support microseconds
 */
class DateTimeMicrosecondsType extends Type
{
    public const TYPENAME = 'datetime6'; // modify to match your type name

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        if (isset($fieldDeclaration['version']) && $fieldDeclaration['version']) {
            return 'TIMESTAMP';
        }

        return 'DATETIME(6)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }

        $val = DateTime::createFromFormat('Y-m-d H:i:s.u', $value);

        if (!$val) {
            $val = date_create($value);
        }

        if (!$val) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                'Y-m-d H:i:s.u'
            );
        }

        return $val;
    }

    public function getName(): string
    {
        return self::TYPENAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s.u');
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', 'DateTime']
        );
    }
}
