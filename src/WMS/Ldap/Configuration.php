<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WMS\Ldap;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Zend\Ldap\Dn;
use Zend\Ldap\Filter\AbstractFilter;
use Zend\Ldap\Filter\AndFilter;
use Zend\Ldap\Filter\StringFilter;
use Psr\Log\LoggerInterface;

/**
 * Configuration container for Ldap Connection
 *
 * @package FineWolf\LdapBundle\Ldap
 */
class Configuration
{
    /** @var string */
    protected $host;
    /** @var int */
    protected $port = 389;
    /** @var Dn */
    protected $baseDn;
    /** @var string|null */
    protected $domainName;
    /** @var string|null */
    protected $domainNameShort;
    /** @var bool */
    protected $bindRequiresDn = false;
    /** @var bool */
    protected $allowEmptyPassword = false;
    /** @var int */
    protected $accountCanonicalForm = Enum\CanonicalAccountNameForm::USERNAME;
    /** @var Dn|null */
    protected $accountSearchDn;
    /** @var string */
    protected $accountObjectClass;
    /** @var string */
    protected $accountObjectFilter;
    /** @var string */
    protected $accountUsernameAttribute;
    /** @var string|null */
    protected $accountUniqueIdAttribute;
    /** @var string|null */
    protected $accountFirstNameAttribute;
    /** @var string|null */
    protected $accountLastNameAttribute;
    /** @var string|null */
    protected $accountDisplayNameAttribute;
    /** @var string|null */
    protected $accountEmailAttribute;
    /** @var string|null */
    protected $accountPictureAttribute;
    /** @var Dn|null */
    protected $groupSearchDn;
    /** @var string */
    protected $groupObjectClass;
    /** @var string */
    protected $groupObjectFilter;
    /** @var string */
    protected $groupNameAttribute;
    /** @var string|null */
    protected $groupDescriptionAttribute;
    /** @var string */
    protected $groupMembersAttribute;
    /** @var int */
    protected $groupMembersAttributeMappingType = Enum\GroupMembersMappingType::DN;
    /** @var string */
    protected $accountMembershipAttribute;
    /** @var string */
    protected $accountMembershipAttributeMappingType = Enum\AccountMembershipMappingType::DN;
    /** @var bool */
    protected $membershipUseAttributeFromGroup = false;
    /** @var bool */
    protected $membershipUseAttributeFromUser = false;
    /** @var bool */
    protected $useStartTls = false;
    /** @var bool */
    protected $useSsl = false;
    /** @var string|null */
    protected $user;
    /** @var string|null */
    protected $password;
    /** @var int|null */
    protected $timeout;
    /** @var bool */
    protected $tryUsernameSplit;
    /** @var bool */
    protected $followReferals;
    /** @var LoggerInterface|null */
    protected $logger;
    /** @var callable[] */
    private $__registeredConfigChangeListeners = array();

    /**
     * @param int $accountCanonicalForm
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setAccountCanonicalForm($accountCanonicalForm)
    {
        Enum\CanonicalAccountNameForm::throwExceptionIfInvalid($accountCanonicalForm);

        $this->accountCanonicalForm = $accountCanonicalForm;
        $this->onConfigChanged();
    }

    /**
     * @return int
     */
    public function getAccountCanonicalForm()
    {
        return $this->accountCanonicalForm;
    }

    /**
     * @param null|string $accountDisplayNameAttribute
     */
    public function setAccountDisplayNameAttribute($accountDisplayNameAttribute)
    {
        $this->accountDisplayNameAttribute = $this->normalizeScalar(
            $accountDisplayNameAttribute,
            '$accountDisplayNameAttribute',
            true
        );
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getAccountDisplayNameAttribute()
    {
        return $this->accountDisplayNameAttribute;
    }

    /**
     * @param null|string $accountEmailAttribute
     */
    public function setAccountEmailAttribute($accountEmailAttribute)
    {
        $this->accountEmailAttribute = $this->normalizeScalar($accountEmailAttribute, '$accountEmailAttribute', true);
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getAccountEmailAttribute()
    {
        return $this->accountEmailAttribute;
    }

    /**
     * @param null|string $accountFirstNameAttribute
     */
    public function setAccountFirstNameAttribute($accountFirstNameAttribute)
    {
        $this->accountFirstNameAttribute = $this->normalizeScalar(
            $accountFirstNameAttribute,
            '$accountFirstNameAttribute',
            true
        );
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getAccountFirstNameAttribute()
    {
        return $this->accountFirstNameAttribute;
    }

    /**
     * @param null|string $accountLastNameAttribute
     */
    public function setAccountLastNameAttribute($accountLastNameAttribute)
    {
        $this->accountLastNameAttribute = $this->normalizeScalar(
            $accountLastNameAttribute,
            '$accountLastNameAttribute',
            true
        );
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getAccountLastNameAttribute()
    {
        return $this->accountLastNameAttribute;
    }

    /**
     * @param string $accountMembershipAttribute
     */
    public function setAccountMembershipAttribute($accountMembershipAttribute)
    {
        $this->accountMembershipAttribute = $this->normalizeScalar(
            $accountMembershipAttribute,
            '$accountMembershipAttribute'
        );
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getAccountMembershipAttribute()
    {
        return $this->accountMembershipAttribute;
    }

    /**
     * @param int $accountMembershipAttributeMappingType
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setAccountMembershipAttributeMappingType($accountMembershipAttributeMappingType)
    {
        Enum\AccountMembershipMappingType::throwExceptionIfInvalid($accountMembershipAttributeMappingType);

        $this->accountMembershipAttributeMappingType = $accountMembershipAttributeMappingType;
        $this->onConfigChanged();
    }

    /**
     * @return int
     */
    public function getAccountMembershipAttributeMappingType()
    {
        return $this->accountMembershipAttributeMappingType;
    }

    /**
     * @param string $accountObjectClass
     */
    public function setAccountObjectClass($accountObjectClass)
    {
        $this->accountObjectClass = $this->normalizeScalar($accountObjectClass, '$accountObjectClass');
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getAccountObjectClass()
    {
        return $this->accountObjectClass;
    }

    /**
     * @param string $accountObjectFilter
     */
    public function setAccountObjectFilter($accountObjectFilter)
    {
        $this->accountObjectFilter = $this->normalizeScalar($accountObjectFilter, '$accountObjectFilter');
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getAccountObjectFilter()
    {
        return $this->accountObjectFilter;
    }

    /**
     * @param null|string $accountPictureAttribute
     */
    public function setAccountPictureAttribute($accountPictureAttribute)
    {
        $this->accountPictureAttribute = $this->normalizeScalar(
            $accountPictureAttribute,
            '$accountPictureAttribute',
            true
        );
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getAccountPictureAttribute()
    {
        return $this->accountPictureAttribute;
    }

    /**
     * @param null|\Zend\Ldap\Dn|string $accountSearchDn
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setAccountSearchDn($accountSearchDn)
    {
        if ($accountSearchDn === null) {
            $this->accountSearchDn = null;
            return;
        }

        $accountSearchDn = $this->normalizeDn($accountSearchDn, '$accountSearchDn');

        if (Dn::isChildOf($accountSearchDn, $this->baseDn)) {
            $this->accountSearchDn = $accountSearchDn;
        } else {
            throw new Exception\InvalidArgumentException('$accountSearchDn must be a child of baseDn');
        }
        $this->onConfigChanged();
    }

    /**
     * @return null|\Zend\Ldap\Dn
     */
    public function getAccountSearchDn()
    {
        return $this->accountSearchDn;
    }

    /**
     * @param null|string $accountUniqueIdAttribute
     */
    public function setAccountUniqueIdAttribute($accountUniqueIdAttribute)
    {
        $this->accountUniqueIdAttribute = $this->normalizeScalar(
            $accountUniqueIdAttribute,
            '$accountUniqueIdAttribute',
            true
        );
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getAccountUniqueIdAttribute()
    {
        return $this->accountUniqueIdAttribute;
    }

    /**
     * @param string $accountUsernameAttribute
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setAccountUsernameAttribute($accountUsernameAttribute)
    {
        $this->accountUsernameAttribute = $this->normalizeScalar(
            $accountUsernameAttribute,
            '$accountUsernameAttribute'
        );
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getAccountUsernameAttribute()
    {
        return $this->accountUsernameAttribute;
    }

    /**
     * @param boolean $allowEmptyPassword
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setAllowEmptyPassword($allowEmptyPassword)
    {
        $this->allowEmptyPassword = $this->normalizeBool($allowEmptyPassword, '$allowEmptyPassword');
        $this->onConfigChanged();
    }

    /**
     * @return boolean
     */
    public function getAllowEmptyPassword()
    {
        return $this->allowEmptyPassword;
    }

    /**
     * @param \Zend\Ldap\Dn $baseDn
     */
    public function setBaseDn($baseDn)
    {
        $this->baseDn = $this->normalizeDn($baseDn, '$baseDn');

        if ($this->accountSearchDn) {
            if (!Dn::isChildOf($this->accountSearchDn, $this->baseDn)) {
                $this->accountSearchDn = null;
            }
        }

        if ($this->groupSearchDn) {
            if (!Dn::isChildOf($this->groupSearchDn, $this->baseDn)) {
                $this->groupSearchDn = null;
            }
        }

        $this->onConfigChanged();
    }

    /**
     * @return \Zend\Ldap\Dn
     */
    public function getBaseDn()
    {
        return $this->baseDn;
    }

    /**
     * @param boolean $bindRequiresDn
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setBindRequiresDn($bindRequiresDn)
    {
        $this->bindRequiresDn = $this->normalizeBool($bindRequiresDn, '$bindRequiresDn');
        $this->onConfigChanged();
    }

    /**
     * @return boolean
     */
    public function getBindRequiresDn()
    {
        return $this->bindRequiresDn;
    }

    /**
     * @param null|string $domainName
     */
    public function setDomainName($domainName)
    {
        $this->domainName = $this->normalizeScalar($domainName, '$domainName', true);
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @param null|string $domainNameShort
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setDomainNameShort($domainNameShort)
    {
        $this->domainNameShort = $this->normalizeScalar($domainNameShort, '$domainNameShort', true);
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getDomainNameShort()
    {
        return $this->domainNameShort;
    }

    /**
     * @param null|string $groupDescriptionAttribute
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setGroupDescriptionAttribute($groupDescriptionAttribute)
    {
        $this->groupDescriptionAttribute = $this->normalizeScalar(
            $groupDescriptionAttribute,
            '$groupDescriptionAttribute',
            true
        );
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getGroupDescriptionAttribute()
    {
        return $this->groupDescriptionAttribute;
    }

    /**
     * @param string $groupMembersAttribute
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setGroupMembersAttribute($groupMembersAttribute)
    {
        $this->groupMembersAttribute = $this->normalizeScalar($groupMembersAttribute, '$groupMembersAttribute');
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getGroupMembersAttribute()
    {
        return $this->groupMembersAttribute;
    }

    /**
     * @param int $groupMembersAttributeMappingType
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setGroupMembersAttributeMappingType($groupMembersAttributeMappingType)
    {
        Enum\GroupMembersMappingType::throwExceptionIfInvalid($groupMembersAttributeMappingType);
        $this->groupMembersAttributeMappingType = $groupMembersAttributeMappingType;
        $this->onConfigChanged();
    }

    /**
     * @return int
     */
    public function getGroupMembersAttributeMappingType()
    {
        return $this->groupMembersAttributeMappingType;
    }

    /**
     * @param string $groupNameAttribute
     */
    public function setGroupNameAttribute($groupNameAttribute)
    {
        $this->groupNameAttribute = $this->normalizeScalar($groupNameAttribute, '$groupNameAttribute');
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getGroupNameAttribute()
    {
        return $this->groupNameAttribute;
    }

    /**
     * @param string $groupObjectClass
     */
    public function setGroupObjectClass($groupObjectClass)
    {

        $this->groupObjectClass = $this->normalizeScalar($groupObjectClass, '$groupObjectClass');
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getGroupObjectClass()
    {
        return $this->groupObjectClass;
    }

    /**
     * @param string $groupObjectFilter
     */
    public function setGroupObjectFilter($groupObjectFilter)
    {
        $this->groupObjectFilter = $this->normalizeScalar($groupObjectFilter, '$groupObjectFilter');
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getGroupObjectFilter()
    {
        return $this->groupObjectFilter;
    }

    /**
     * @param null|\Zend\Ldap\Dn|string $groupSearchDn
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setGroupSearchDn($groupSearchDn)
    {
        if ($groupSearchDn === null) {
            $this->groupSearchDn = null;
            $this->onConfigChanged();
            return;
        }

        $groupSearchDn = $this->normalizeDn($groupSearchDn, '$groupSearchDn');

        if (Dn::isChildOf($groupSearchDn, $this->baseDn)) {
            $this->groupSearchDn = $groupSearchDn;
        } else {
            throw new Exception\InvalidArgumentException('$groupSearchDn must be a child of baseDn');
        }

        $this->onConfigChanged();
    }

    /**
     * @return null|\Zend\Ldap\Dn
     */
    public function getGroupSearchDn()
    {
        return $this->groupSearchDn;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $this->normalizeScalar($host, '$host');
        $this->onConfigChanged();
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param boolean $membershipUseAttributeFromGroup
     */
    public function setMembershipUseAttributeFromGroup($membershipUseAttributeFromGroup)
    {
        $this->membershipUseAttributeFromGroup = $this->normalizeBool(
            $membershipUseAttributeFromGroup,
            '$membershipUseAttributeFromGroup'
        );
        $this->onConfigChanged();
    }

    /**
     * @return boolean
     */
    public function getMembershipUseAttributeFromGroup()
    {
        return $this->membershipUseAttributeFromGroup;
    }

    /**
     * @param bool $membershipUseAttributeFromUser
     */
    public function setMembershipUseAttributeFromUser($membershipUseAttributeFromUser)
    {
        $this->membershipUseAttributeFromUser = $this->normalizeBool(
            $membershipUseAttributeFromUser,
            '$membershipUseAttributeFromUser'
        );
        $this->onConfigChanged();
    }

    /**
     * @return bool
     */
    public function getMembershipUseAttributeFromUser()
    {
        return $this->membershipUseAttributeFromUser;
    }

    /**
     * @param int $port
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setPort($port)
    {
        $port = (int)$port;

        if ($port < 1 || $port > 65535) {
            throw new Exception\InvalidArgumentException('$port must be between 1 and 65535');
        }

        $this->port = $port;
        $this->onConfigChanged();
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param boolean $followReferals
     */
    public function setFollowReferals($followReferals)
    {
        $this->followReferals = $this->normalizeBool($followReferals, '$followReferals');
        $this->onConfigChanged();
    }

    /**
     * @return boolean
     */
    public function getFollowReferals()
    {
        return $this->followReferals;
    }

    /**
     * @param null|string $password
     */
    public function setPassword($password)
    {
        $this->password = $this->normalizeScalar($password, '$password', true);
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param int|null $timeout
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setTimeout($timeout)
    {
        if ($timeout === null) {
            $this->timeout = null;
            $this->onConfigChanged();
            return;
        }

        $timeout = (int)$timeout;

        if ($timeout < 0) {
            throw new Exception\InvalidArgumentException('$timeout must be between positive (0+)');
        }

        $this->timeout = $timeout;
        $this->onConfigChanged();
    }

    /**
     * @return int|null
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param boolean $tryUsernameSplit
     */
    public function setTryUsernameSplit($tryUsernameSplit)
    {
        $this->tryUsernameSplit = $this->normalizeBool($tryUsernameSplit, '$tryUsernameSplit');
        $this->onConfigChanged();
    }

    /**
     * @return boolean
     */
    public function getTryUsernameSplit()
    {
        return $this->tryUsernameSplit;
    }

    /**
     * @param boolean $useSsl
     */
    public function setUseSsl($useSsl)
    {
        $this->useSsl = $this->normalizeBool($useSsl, '$useSsl');
        $this->onConfigChanged();
    }

    /**
     * @return boolean
     */
    public function getUseSsl()
    {
        return $this->useSsl;
    }

    /**
     * @param boolean $useStartTls
     */
    public function setUseStartTls($useStartTls)
    {
        $this->useStartTls = $this->normalizeBool($useStartTls, '$useStartTls');
        $this->onConfigChanged();
    }

    /**
     * @return boolean
     */
    public function getUseStartTls()
    {
        return $this->useStartTls;
    }

    /**
     * @param null|string $user
     */
    public function setUser($user)
    {
        $this->user = $this->normalizeScalar($user, '$user', true);
        $this->onConfigChanged();
    }

    /**
     * @return null|string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return null|\Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param null|\Psr\Log\LoggerInterface $logger
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setLogger($logger)
    {
        if ($this->logger !== null && ($this->logger instanceof LoggerInterface) === false) {
            throw new Exception\InvalidArgumentException('$logger must be an instance of \Psr\Log\LoggerInterface or null');
        }

        $this->logger = $logger;
        $this->onConfigChanged();
    }

    public function getZendLdapConfiguration()
    {
        $accountFilter = new AndFilter(
            array(
                new StringFilter('objectClass=' . AbstractFilter::escapeValue($this->getAccountObjectClass())),
                new StringFilter(preg_replace('/^\(?(.+?)\)$/', '\1', $this->getAccountObjectFilter())),
                new StringFilter($this->getAccountUsernameAttribute() . '=%s'),
            )
        );

        return array(
            'host'                   => $this->getHost(),
            'port'                   => $this->getPort(),
            'useSsl'                 => $this->getUseSsl(),
            'username'               => $this->getUser(),
            'password'               => $this->getPassword(),
            'bindRequiresDn'         => $this->getBindRequiresDn(),
            'baseDn'                 => $this->getBaseDn(),
            'accountCanonicalForm'   => $this->getAccountCanonicalForm(),
            'accountDomainName'      => $this->getDomainName(),
            'accountDomainNameShort' => $this->getDomainNameShort(),
            'accountFilterFormat'    => (string)$accountFilter,
            'allowEmptyPassword'     => $this->getAllowEmptyPassword(),
            'useStartTls'            => $this->getUseStartTls(),
            'optReferrals'           => $this->getFollowReferals(),
            'tryUsernameSplit'       => $this->getTryUsernameSplit(),
            'networkTimeout'         => $this->getTimeout(),
        );
    }

    private function normalizeDn($dn, $attributeName)
    {
        if ($dn instanceof Dn) {
            return $dn;
        } elseif (is_scalar($dn) && $dn !== null) {
            if (!Dn::checkDn($dn)) {
                throw new Exception\InvalidArgumentException(sprintf('%s must be a valid DN', $attributeName));
            }

            return Dn::fromString($dn);
        }

        throw new Exception\InvalidArgumentException(sprintf('%s must be a valid DN', $attributeName));
    }

    private function normalizeBool($value, $attributeName)
    {
        if (!is_bool($value)) {
            throw new Exception\InvalidArgumentException(sprintf('%s must be a boolean', $attributeName));
        }

        return $value;
    }

    private function normalizeScalar($value, $attributeName, $allowNull = false)
    {
        if (!is_scalar($value) && (($allowNull && $value !== null) || (!$allowNull && $value === null))) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s must be %s scalar',
                $attributeName,
                $allowNull ? 'a' : 'a non-null'
            ));
        }

        return (string)$value;
    }

    protected function onConfigChanged()
    {
        foreach ($this->__registeredConfigChangeListeners as $listener) {
            if (is_callable($listener)) {
                $listener($this);
            }
        }
    }

    public function registerConfigChangeListener($callable)
    {
        if (!is_callable($callable)) {
            throw new Exception\InvalidArgumentException('$callable must be callable');
        }
        $this->__registeredConfigChangeListeners[] = $callable;
    }

    public function unregisterConfigChangeListener($callable)
    {
        $indexOf = array_search($callable, $this->__registeredConfigChangeListeners, true);
        if ($indexOf !== false) {
            unset($this->__registeredConfigChangeListeners[$indexOf]);
        }
    }

    function __sleep()
    {
        $reflectionObject = new \ReflectionObject($this);
        $properties = $reflectionObject->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        $propertiesNameArray = array();

        foreach ($properties as $property) {
            if (preg_match('/^__/', $property->getName())) {
                continue;
            }

            $propertiesNameArray[] = $property->getName();
        }

        return $propertiesNameArray;
    }
}