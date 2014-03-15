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

use WMS\Ldap\Entity as Entity;

class AccountNodeCollection extends DisconnectedZendLdapNodeCollection
{
    /**
     * Creates the data structure for the given entry data
     *
     * @param  array $data
     * @return Entity\AccountNode
     */
    protected function createEntry(array $data)
    {
        return Entity\AccountNode::fromNode(parent::createEntry($data), $this->getConnection()->getConfiguration());
    }

    /**
     * @return Entity\AccountNode
     */
    public function getFirst()
    {
        return parent::getFirst();
    }
}
