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

use Zend\Ldap\Ldap;

/**
 * Defines the different possible mapping types between
 * individual group records and its members.
 *
 * @author Andrew Moore <me@andrewmoore.ca>
 */
final class CanonicalAccountNameForm extends AbstractEnum
{
    /**
     * Canonicalize the account name to its Distinguished Name (DN)
     */
    const DN = Ldap::ACCTNAME_FORM_DN;
    /**
     * Canonicalize the account name to its username
     */
    const USERNAME = Ldap::ACCTNAME_FORM_USERNAME;
    /**
     * Canonicalize the account name to its NetBios form
     * (SHORTDOMAIN\username)
     */
    const BACKSLASH = Ldap::ACCTNAME_FORM_BACKSLASH;
    /**
     * Canonicalize the account name to its principal form
     * (username@domainname)
     */
    const PRINCIPAL = Ldap::ACCTNAME_FORM_PRINCIPAL;
}