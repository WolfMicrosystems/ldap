<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Ldap\Enum;

use WMS\Ldap\Exception\InvalidEnumValueException;

/**
 * Abstract enum class providing validation and useful methods.
 *
 * @author Andrew Moore <me@andrewmoore.ca>
 */
abstract class AbstractEnum
{
    /**
     * Checks if the value is valid according to the enumeration.
     *
     * @param mixed $value
     * @return bool
     * @throws \RuntimeException
     */
    public static function isValid($value)
    {
        if (__CLASS__ === get_called_class()) {
            throw new \RuntimeException('Cannot call isValid() on ' . __CLASS__);
        }

        $reflectionClass = new \ReflectionClass(get_called_class());
        $validValues = $reflectionClass->getConstants();

        if (static::isBitFlag()) {
            $bitFlagAllOn = 0;

            foreach ($validValues as $validValue) {
                $bitFlagAllOn |= $validValue;
            }

            return ($value | $bitFlagAllOn) === $bitFlagAllOn;
        }

        return in_array($value, $validValues, true);
    }

    /**
     * Checks if the value is valid according to the enumeration
     * and throws a {@link \WMS\Ldap\Exception\InvalidEnumValueException}
     * if the value is invalid.
     *
     * @param mixed $value
     * @throws \WMS\Ldap\Configuration\Exception\InvalidEnumValueException
     */
    public static function throwExceptionIfInvalid($value)
    {
        if (static::isValid($value) === false) {
            throw new InvalidEnumValueException(get_called_class(), $value);
        }
    }

    /**
     * Returns whether the enum is a bit flag or not.
     * Use by the isValid method.
     *
     * @return bool
     */
    protected static function isBitFlag()
    {
        return false;
    }

    protected function __construct()
    {
        // Prevent construction of enum class
    }
} 