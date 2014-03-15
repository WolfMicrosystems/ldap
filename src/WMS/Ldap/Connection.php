<?php
namespace WMS\Ldap;

use Psr\Log\LoggerInterface;
use Zend\Ldap\Ldap as BaseConnection;

class Connection extends BaseConnection
{
    /** @var Configuration */
    protected $config;
    private $configChangeListener = null;

    public function __construct(Configuration $config)
    {
        parent::__construct($config->getZendLdapConfiguration());
        $this->setConfiguration($config);
    }

    /**
     * @param array|\Traversable $options
     *
     * @deprecated
     * @return Connection
     */
    public function setOptions($options)
    {
        return parent::setOptions($options);
    }

    /**
     * @return \WMS\Ldap\Configuration
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * @param \WMS\Ldap\Configuration $config
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setConfiguration(Configuration $config)
    {
        if ($config === null) {
            throw new Exception\InvalidArgumentException('$config cannot be null');
        }


        if ($this->config !== null) {
            $this->config->unregisterConfigChangeListener($this->getConfigChangeListener());
        }

        $this->config = $config;
        $this->setOptions($config->getZendLdapConfiguration());
        $this->config->registerConfigChangeListener($this->getConfigChangeListener());
    }

    private function getConfigChangeListener()
    {
        if ($this->configChangeListener === null) {
            $instance = $this;

            $this->configChangeListener = function (Configuration $config) use ($instance) {
                $instance->setOptions($config->getZendLdapConfiguration());
            };
        }

        return $this->configChangeListener;
    }

    protected function log($message, array $context = array())
    {
        if ($this->getConfiguration()->getLogger() instanceof LoggerInterface) {
            $this->getConfiguration()->getLogger()->debug($message, $context);
        }
    }
}