<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Ldap\Entity;

use WMS\Ldap\Configuration;
use Zend\Ldap\Dn;
use Zend\Ldap\Node;

abstract class AbstractNodeEntity
{
    /** @var Dn */
    protected $dn;
    /** @var Node */
    protected $rawLdapNode;

    protected abstract function __construct(Node $node, Configuration $config);

    public static function fromNode(Node $node, Configuration $config)
    {
        return new static($node, $config);
    }

    /**
     * @return \Zend\Ldap\Dn
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * @param \Zend\Ldap\Dn $dn
     */
    protected function setDn($dn)
    {
        $this->dn = $dn;
    }

    /**
     * @return \Zend\Ldap\Node
     */
    public function getRawLdapNode()
    {
        return $this->rawLdapNode;
    }

    /**
     * @param \Zend\Ldap\Node $rawLdapNode
     */
    protected function setRawLdapNode($rawLdapNode)
    {
        $this->rawLdapNode = $rawLdapNode;
    }
} 