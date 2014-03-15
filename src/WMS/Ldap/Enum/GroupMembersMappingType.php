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
 * individual group records and its members.
 *
 * @author Andrew Moore <me@andrewmoore.ca>
 */
final class GroupMembersMappingType extends AbstractEnum
{
    /**
     * The group's members attribute contains Distingished Names
     * of individual account records
     */
    const DN = 1;
    /**
     * The group's members attribute contains the account's username
     */
    const USERNAME = 2;
    /**
     * The group's members attribute contains the account's unique id
     */
    const UNIQUE_ID = 3;
}