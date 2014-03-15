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

use Zend\Ldap\Collection;
use Zend\Ldap\Node;

class DisconnectedZendLdapNodeCollection extends Collection
{
    /**
     * Creates the data structure for the given entry data
     *
     * @param  array $data
     * @return \Zend\Ldap\Node
     */
    protected function createEntry(array $data)
    {
        return Node::fromArray($data, false);
    }

    /**
     * @return \Zend\Ldap\Node
     */
    public function getFirst()
    {
        return parent::getFirst();
    }

    /**
     * @return \WMS\Ldap\Connection
     */
    protected function getConnection()
    {
        return $this->getInnerIterator()->getLDAP();
    }
}
