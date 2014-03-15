<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Ldap\Collection;

use Zend\Ldap\Collection\DefaultIterator;
use Zend\Ldap\Dn;
use Zend\Ldap\Exception;
use Zend\Ldap;

class DnIterator extends DefaultIterator
{
    /**
     * @var Dn[]
     */
    protected $distinguishedNames = array();

    public function __construct(Ldap\Ldap $ldap, array $distinguishedNames)
    {
        $this->distinguishedNames = $distinguishedNames;
        $this->ldap = $ldap;
        reset($this->distinguishedNames);
    }

    public function close()
    {
        return true;
    }

    public function count()
    {
        return count($this->distinguishedNames);
    }

    public function current()
    {
        $currentDn = current($this->distinguishedNames);
        return $this->ldap->getNode($currentDn)->toArray();
    }

    public function key()
    {
        return current($this->distinguishedNames);
    }

    public function next()
    {
        next($this->distinguishedNames);
    }

    public function rewind()
    {
        reset($this->distinguishedNames);
    }

    public function valid()
    {
        return current($this->distinguishedNames) !== false;
    }

} 