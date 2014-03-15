<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Ldap\Repository;

use WMS\Ldap\Enum as Enum;
use WMS\Ldap\Entity as Entity;
use WMS\Ldap\Collection as Collection;
use Zend\Ldap\Exception\LdapException;
use Zend\Ldap\Filter as Filter;

class GroupRepository extends AbstractRepository
{
    /**
     * @return Collection\GroupNodeCollection
     */
    public function findAll()
    {
        return $this->searchNodes(
            $this->buildFilter(new Filter\StringFilter($this->getConfiguration()->getGroupNameAttribute() . '=*'))
        );
    }

    protected function getSearchBaseDn()
    {
        return $this->getConfiguration()->getGroupSearchDn() ? : parent::getSearchBaseDn();
    }

    protected function getNodeCollectionClass()
    {
        return '\WMS\Ldap\Collection\GroupNodeCollection';
    }

    /**
     * @return Filter\AbstractFilter
     */
    protected function getSearchBaseFilter()
    {
        return new Filter\AndFilter(
            array(
                new Filter\StringFilter('objectClass=' . Filter\AbstractFilter::escapeValue($this->connection->getConfiguration()->getGroupObjectClass())),
                new Filter\StringFilter(preg_replace('/^\(?(.+?)\)$/', '\1', $this->connection->getConfiguration()->getGroupObjectFilter())),
            )
        );
    }

    /**
     * @param $groupName
     * @return Entity\GroupNode|null
     */
    public function findByGroupName($groupName)
    {
        return $this->searchForOneNode(
            $this->buildFilter(
                new Filter\StringFilter($this->connection->getConfiguration()->getGroupNameAttribute() . '=' . Filter\AbstractFilter::escapeValue($groupName))
            )
        );
    }

    /**
     * @param Entity\AccountNode $account
     * @return Collection\GroupNodeCollection
     */
    public function findGroupsForAccount(Entity\AccountNode $account)
    {
        if ($this->getConfiguration()->getMembershipUseAttributeFromUser() === true) {
            return $this->findGroupsForAccountUsingAccountAttribute($account);
        } elseif ($this->getConfiguration()->getMembershipUseAttributeFromGroup() === true) {
            return $this->findGroupsForAccountUsingGroupAttribute($account);
        }

        return new Collection\GroupNodeCollection(new Collection\DnIterator($this->connection, array()));
    }

    /**
     * @param Entity\AccountNode $account
     * @return Collection\GroupNodeCollection
     */
    protected function findGroupsForAccountUsingAccountAttribute(Entity\AccountNode $account)
    {
        $accountAttrValues = $account->getRawLdapNode()->getAttribute($this->getConfiguration()->getAccountMembershipAttribute());

        $filterProperty = '';

        switch ($this->getConfiguration()->getAccountMembershipAttributeMappingType()) {
            case Enum\AccountMembershipMappingType::DN:
                return new Collection\GroupNodeCollection(new Collection\DnIterator($this->connection, $accountAttrValues));
                break;
            case Enum\AccountMembershipMappingType::NAME:
                $filterProperty = $this->getConfiguration()->getGroupNameAttribute();
                break;
        }

        $filters = array();

        foreach ($accountAttrValues as $accountAttrValue) {
            $filters[] = new Filter\StringFilter($filterProperty . '=' . Filter\AbstractFilter::escapeValue($accountAttrValue));
        }

        return $this->searchNodes(
            $this->buildFilter(
                new Filter\OrFilter($filters)
            )
        );
    }

    /**
     * @param Entity\AccountNode $account
     * @return Collection\GroupNodeCollection
     */
    protected function findGroupsForAccountUsingGroupAttribute(Entity\AccountNode $account)
    {
        $findValue = $account->getDn();

        switch ($this->getConfiguration()->getGroupMembersAttributeMappingType()) {
            case Enum\GroupMembersMappingType::USERNAME:
                $findValue = $account->getUsername();
                break;

            case Enum\GroupMembersMappingType::UNIQUE_ID:
                $findValue = $account->getUniqueId();
                break;
        }

        return $this->searchNodes(
            $this->buildFilter(
                new Filter\StringFilter($this->getConfiguration()->getGroupMembersAttribute() . '=' . Filter\AbstractFilter::escapeValue($findValue))
            )
        );
    }
}