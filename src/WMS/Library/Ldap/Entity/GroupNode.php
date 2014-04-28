<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Library\Ldap\Entity;

use WMS\Library\Ldap\Exception\UnexpectedNodeException;
use WMS\Library\Ldap\Configuration;
use WMS\Library\Ldap\Helper\NodeUtil;
use Zend\Ldap\Node;

class GroupNode extends AbstractNodeEntity
{
    /** @var string|null */
    protected $description;
    /** @var string */
    protected $name;

    protected function __construct(Node $node, Configuration $config)
    {
        if (!NodeUtil::isObjectClass($node, $config->getGroupObjectClass()) || !$node->existsAttribute($config->getGroupNameAttribute())) {
            throw new UnexpectedNodeException(
                sprintf('Expecting node with objectClass=%s and with an attribute called %s', $config->getGroupObjectClass(), $config->getGroupNameAttribute())
            );
        }

        $this->setDn($node->getDn());
        $this->setRawLdapNode($node);
        $this->setName($node->getAttribute($config->getGroupNameAttribute(), 0));
        $this->setDescription($node->getAttribute($config->getGroupDescriptionAttribute(), 0));
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $firstName
     */
    protected function setDescription($firstName)
    {
        $this->description = $firstName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $username
     */
    protected function setName($username)
    {
        $this->name = $username;
    }
} 