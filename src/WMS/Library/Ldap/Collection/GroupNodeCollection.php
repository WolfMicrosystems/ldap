<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Library\Ldap\Collection;

use WMS\Library\Ldap\Entity as Entity;

class GroupNodeCollection extends DisconnectedZendLdapNodeCollection
{
    /**
     * Creates the data structure for the given entry data
     *
     * @param  array $data
     * @return Entity\GroupNode
     */
    protected function createEntry(array $data)
    {
        return Entity\GroupNode::fromNode(parent::createEntry($data), $this->getConnection()->getConfiguration());
    }

    /**
     * @return Entity\GroupNode
     */
    public function getFirst()
    {
        return parent::getFirst();
    }
}
