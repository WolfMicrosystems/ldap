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

use WMS\Ldap\Collection\DisconnectedZendLdapNodeCollection;
use WMS\Ldap\Connection;
use WMS\Ldap\Enum as Enum;
use WMS\Ldap\Entity\AccountNode;
use WMS\Ldap\Exception\InvalidArgumentException;
use WMS\Ldap\Exception\UnexpectedNodeException;
use Zend\Ldap\Collection;
use Zend\Ldap\Dn;
use Zend\Ldap\Filter as Filter;
use Zend\Ldap\Node;

abstract class AbstractRepository
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->setConnection($connection);
    }

    /**
     * @return DisconnectedZendLdapNodeCollection
     */
    public abstract function findAll();

    /**
     * @return \WMS\Ldap\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param \WMS\Ldap\Connection $connection
     * @throws \WMS\Ldap\Exception\InvalidArgumentException
     */
    public function setConnection(Connection $connection)
    {
        if ($connection === null) {
            throw new InvalidArgumentException('$connection must be an instance of \WMS\Ldap\Connection');
        }

        $this->connection = $connection;
    }

    /**
     * @param Filter\AbstractFilter $descriminator
     * @return Filter\AbstractFilter
     */
    protected function buildFilter(Filter\AbstractFilter $descriminator = null)
    {
        if ($descriminator === null) {
            return $this->getSearchBaseFilter();
        }

        return new Filter\AndFilter(
            array(
                $this->getSearchBaseFilter(),
                $descriminator
            )
        );
    }

    protected function getAccountSearchDn()
    {
        if ($this->connection->getConfiguration()->getAccountSearchDn()) {
            return $this->connection->getConfiguration()->getAccountSearchDn();
        }

        return $this->connection->getConfiguration()->getBaseDn();
    }

    protected function getConfiguration()
    {
        return $this->connection->getConfiguration();
    }

    /**
     * @return string
     */
    protected function getNodeCollectionClass()
    {
        return '\WMS\Ldap\Collection\DisconnectedZendLdapNodeCollection';
    }

    /**
     * @return Dn
     */
    protected function getSearchBaseDn()
    {
        return $this->getConfiguration()->getBaseDn();
    }

    /**
     * @return Filter\AbstractFilter
     */
    protected abstract function getSearchBaseFilter();

    /**
     * @param Filter\AbstractFilter $filter
     * @param int                   $searchScope
     * @param int                   $maxFilterTime
     * @return mixed
     */
    protected function searchForOneNode(Filter\AbstractFilter $filter, $searchScope = Connection::SEARCH_SCOPE_SUB, $maxFilterTime = 0)
    {
        $results = $this->searchNodes($filter, $searchScope, 1, $maxFilterTime);

        if ($results->count() === 0) {
            return null;
        }

        return $results->getFirst();
    }

    /**
     * @param Filter\AbstractFilter $filter
     * @param int                   $searchScope
     * @param int                   $maxResults
     * @param int                   $maxFilterTime
     * @return DisconnectedZendLdapNodeCollection
     */
    protected function searchNodes(Filter\AbstractFilter $filter, $searchScope = Connection::SEARCH_SCOPE_SUB, $maxResults = 0, $maxFilterTime = 0)
    {
        return $this->connection->search(
            $filter,
            $this->getSearchBaseDn(),
            $searchScope,
            array(),
            null,
            $this->getNodeCollectionClass(),
            $maxResults,
            $maxFilterTime
        );
    }
} 