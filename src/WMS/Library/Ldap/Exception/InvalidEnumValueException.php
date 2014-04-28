<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Library\Ldap\Exception;

use Exception;

/**
 * Thrown when an invalid enumeration value is passed to a setter
 */
class InvalidEnumValueException extends InvalidArgumentException
{
    /**
     * @var string
     */
    protected $enumeration;
    /**
     * @var mixed
     */
    protected $invalidValue;

    /**
     * @param string    $enumeration
     * @param mixed     $invalidValue
     * @param Exception $previous
     */
    public function __construct($enumeration, $invalidValue, Exception $previous = null)
    {
        $this->enumeration = $enumeration;
        $this->invalidValue = $invalidValue;

        $message = 'An invalid value has been passed to a method that expects a valid ' . $enumeration . ' value';
        $messageMask = 'An invalid value (%s) has been passed to a method that expects a valid ' . $enumeration . ' value';

        if (is_object($invalidValue)) {
            $message = sprintf($messageMask, 'instanceof ' . get_class($invalidValue));
        } elseif (is_resource($invalidValue)) {
            $message = sprintf($messageMask, 'resource');
        } elseif ($invalidValue === null) {
            $message = sprintf($messageMask, 'null');
        } elseif (is_array($invalidValue)) {
            $message = sprintf($messageMask, 'array');
        } elseif (is_scalar($invalidValue)) {
            $message = sprintf($messageMask, var_export($invalidValue, true));
        }

        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the name of the enumeration class
     *
     * @return string
     */
    public function getEnumeration()
    {
        return $this->enumeration;
    }

    /**
     * Returns the invalid value used
     *
     * @return mixed
     */
    public function getInvalidValue()
    {
        return $this->invalidValue;
    }
} 