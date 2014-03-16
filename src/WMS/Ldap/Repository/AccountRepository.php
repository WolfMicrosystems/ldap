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

use WMS\Ldap\Collection as Collection;
use WMS\Ldap\Enum as Enum;
use WMS\Ldap\Entity as Entity;
use Zend\Ldap\Exception\LdapException;
use Zend\Ldap\Filter as Filter;
use Zend\Ldap\Node;

class AccountRepository extends AbstractRepository
{
    /**
     * @param \WMS\Ldap\Entity\GroupNode $groupNode
     *
     * @return Collection\AccountNodeCollection
     */
    public function findAccountsForGroup(Entity\GroupNode $groupNode)
    {
        if ($this->getConfiguration()->getMembershipUseAttributeFromGroup() === true) {
            return $this->findAccountsForGroupUsingGroupAttribute($groupNode);
        } elseif ($this->getConfiguration()->getMembershipUseAttributeFromUser() === true) {
            return $this->findAccountsForGroupUsingAccountAttribute($groupNode);
        }

        return new Collection\AccountNodeCollection(new Collection\DnIterator($this->connection, array()));
    }

    /**
     * @return Collection\AccountNodeCollection
     */
    public function findAll()
    {
        return $this->searchNodes(
            $this->buildFilter(new Filter\StringFilter($this->getConfiguration()->getAccountUsernameAttribute() . '=*'))
        );
    }

    protected function getSearchBaseDn()
    {
        return $this->getConfiguration()->getAccountSearchDn() ? : parent::getSearchBaseDn();
    }

    protected function getNodeCollectionClass()
    {
        return '\WMS\Ldap\Collection\AccountNodeCollection';
    }

    /**
     * @return Filter\AbstractFilter
     */
    protected function getSearchBaseFilter()
    {
        return new Filter\AndFilter(
            array(
                new Filter\StringFilter('objectClass=' . Filter\AbstractFilter::escapeValue($this->connection->getConfiguration()->getAccountObjectClass())),
                new Filter\StringFilter(preg_replace('/^\(?(.+?)\)$/', '\1', $this->connection->getConfiguration()->getAccountObjectFilter())),
            )
        );
    }

    /**
     * @param $accountName
     *
     * @return Entity\AccountNode|null
     */
    public function findByAccountName($accountName)
    {
        $username = $this->connection->getCanonicalAccountName($accountName, Enum\CanonicalAccountNameForm::USERNAME);

        return $this->searchForOneNode(
            $this->buildFilter(
                new Filter\StringFilter($this->connection->getConfiguration()->getAccountUsernameAttribute() . '=' . Filter\AbstractFilter::escapeValue($username))
            )
        );
    }

    /**
     * @param Entity\GroupNode $group
     *
     * @return Collection\AccountNodeCollection
     */
    protected function findAccountsForGroupUsingAccountAttribute(Entity\GroupNode $group)
    {
        $findValue = $group->getDn();

        switch ($this->getConfiguration()->getAccountMembershipAttribute()) {
            case Enum\AccountMembershipMappingType::NAME:
                $findValue = $group->getName();
                break;
        }

        return $this->searchNodes(
            $this->buildFilter(
                new Filter\StringFilter($this->getConfiguration()->getAccountMembershipAttribute() . '=' . Filter\AbstractFilter::escapeValue($findValue))
            )
        );
    }

    /**
     * @param Entity\GroupNode $group
     *
     * @return Collection\AccountNodeCollection
     */
    protected function findAccountsForGroupUsingGroupAttribute(Entity\GroupNode $group)
    {
        $groupAttrValues = $group->getRawLdapNode()->getAttribute($this->getConfiguration()->getGroupMembersAttribute());

        $filterProperty = '';

        switch ($this->getConfiguration()->getGroupMembersAttributeMappingType()) {
            case Enum\GroupMembersMappingType::DN:
                return new Collection\GroupNodeCollection(new Collection\DnIterator($this->connection, array($groupAttrValues)));
            case Enum\GroupMembersMappingType::USERNAME:
                $filterProperty = $this->getConfiguration()->getAccountUsernameAttribute();
                break;

            case Enum\GroupMembersMappingType::UNIQUE_ID:
                $filterProperty = $this->getConfiguration()->getAccountUniqueIdAttribute();
                break;
        }

        $filters = array();

        foreach ($groupAttrValues as $groupAttrValue) {
            $filters[] = new Filter\StringFilter($filterProperty . '=' . Filter\AbstractFilter::escapeValue($groupAttrValue));
        }

        return $this->searchNodes(
            $this->buildFilter(
                new Filter\OrFilter($filters)
            )
        );
    }

    protected function getSearchAttributes()
    {
        return array(
            'dn',
            $this->getConfiguration()->getAccountUsernameAttribute(),
            $this->getConfiguration()->getAccountUniqueIdAttribute(),
            $this->getConfiguration()->getAccountDisplayNameAttribute(),
            $this->getConfiguration()->getAccountFirstNameAttribute(),
            $this->getConfiguration()->getAccountLastNameAttribute(),
            $this->getConfiguration()->getAccountEmailAttribute(),
            $this->getConfiguration()->getAccountPictureAttribute(),
        );
    }
}