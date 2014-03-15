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

use WMS\Ldap\Exception\UnexpectedNodeException;
use WMS\Ldap\Helper\NodeUtil;
use WMS\Ldap\Configuration;
use Zend\Ldap\Node;

class AccountNode extends AbstractNodeEntity
{
    /** @var string|null */
    protected $displayName;
    /** @var string|null */
    protected $email;
    /** @var string|null */
    protected $firstName;

    /** @var string|null */
    protected $lastName;
    /** @var string|null */
    protected $pictureBlob;
    /** @var mixed */
    protected $uniqueId;
    /** @var string */
    protected $username;

    protected function __construct(Node $node, Configuration $config)
    {
        if (!NodeUtil::isObjectClass($node, $config->getAccountObjectClass()) || !$node->existsAttribute($config->getAccountUsernameAttribute())) {
            throw new UnexpectedNodeException(
                sprintf('Expecting node with objectClass=%s and with an attribute called %s', $config->getAccountObjectClass(), $config->getAccountUsernameAttribute())
            );
        }

        $this->setDn($node->getDn());
        $this->setRawLdapNode($node);
        $this->setUsername($node->getAttribute($config->getAccountUsernameAttribute(), 0));
        $this->setUniqueId($node->getAttribute($config->getAccountUniqueIdAttribute(), 0));
        $this->setFirstName($node->getAttribute($config->getAccountFirstNameAttribute(), 0));
        $this->setLastName($node->getAttribute($config->getAccountLastNameAttribute(), 0));
        $this->setDisplayName($node->getAttribute($config->getAccountDisplayNameAttribute(), 0));
        $this->setEmail($node->getAttribute($config->getAccountEmailAttribute(), 0));
        $this->setPictureBlob($node->getAttribute($config->getAccountPictureAttribute(), 0));
    }

    /**
     * @return null|string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param null|string $displayName
     */
    protected function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    protected function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     */
    protected function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     */
    protected function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return null|string
     */
    public function getPictureBlob()
    {
        return $this->pictureBlob;
    }

    /**
     * @param null|string $pictureBlog
     */
    protected function setPictureBlob($pictureBlog)
    {
        $this->pictureBlob = $pictureBlog;
    }

    /**
     * @return mixed
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * @param mixed $id
     */
    protected function setUniqueId($id)
    {
        $this->uniqueId = $id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    protected function setUsername($username)
    {
        $this->username = $username;
    }
} 