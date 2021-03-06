<?php
class Tbs_Auth_Storage_MultipleIdentities implements Zend_Auth_Storage_Interface
{
    const SESSION_NAMESPACE = "MultipleIdentities";

    protected $_session;

    public function __construct()
    {
		Zend_Registry::set(self::SESSION_NAMESPACE, array());			
    }

    public function isEmpty($provider = null)
    {
        $container = $this->read();
        if (!$container) {
            return true;
        } else if ($container->isEmpty($provider)) {
            return true;
        } else {
            return false;
        }
    }

    public function read($provider = null)
    {
		$this->_session = Zend_Registry::get(self::SESSION_NAMESPACE);
        if (!isset($this->_session->identityContainer)) {
            return false;
        } else {
            $container = unserialize($this->_session->identityContainer);
            if (null !== $provider) {
                return $container->get($provider);
            } else {
                return $container;
            }
        }
    }

    public function write($container)
    {
        if (get_class($container) !== 'Tbs_Auth_Identity_Container') {
            throw new Exception('No valid identity container');
        }		
		Zend_Registry::set(self::SESSION_NAMESPACE, $container);
		$this->_session = Zend_Registry::get(self::SESSION_NAMESPACE);
        $this->_session->identityContainer = serialize($container);
    }

    public function clear($provider = null)
    {
        if (null !== $provider && false != $container = $this->read()) {
            $container->remove($provider);
            $this->write($container);
        } else {
            unset($this->_session->identityContainer);
        }
    }

}
