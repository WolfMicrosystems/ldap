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

/**
 * Defines the different possible mapping types between
 * individual account records and its group memberships.
 *
 * @author Andrew Moore <me@andrewmoore.ca>
 */
final class AccountMembershipMappingType extends AbstractEnum
{
    /**
     * The account membership attribute contains Distingished Names
     * of individual group records
     */
    const DN = 1;
    /**
     * The account membership attribute contains the names
     * of individual group
     */
    const NAME = 2;
}