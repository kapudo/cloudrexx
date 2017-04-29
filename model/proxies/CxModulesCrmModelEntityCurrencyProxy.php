<?php

namespace Cx\Model\Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class CxModulesCrmModelEntityCurrencyProxy extends \Cx\Modules\Crm\Model\Entity\Currency implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function getId()
    {
        $this->_load();
        return parent::getId();
    }

    public function setName($name)
    {
        $this->_load();
        return parent::setName($name);
    }

    public function getName()
    {
        $this->_load();
        return parent::getName();
    }

    public function setActive($active)
    {
        $this->_load();
        return parent::setActive($active);
    }

    public function getActive()
    {
        $this->_load();
        return parent::getActive();
    }

    public function setPos($pos)
    {
        $this->_load();
        return parent::setPos($pos);
    }

    public function getPos()
    {
        $this->_load();
        return parent::getPos();
    }

    public function setHourlyRate($hourlyRate)
    {
        $this->_load();
        return parent::setHourlyRate($hourlyRate);
    }

    public function getHourlyRate()
    {
        $this->_load();
        return parent::getHourlyRate();
    }

    public function setDefaultCurrency($defaultCurrency)
    {
        $this->_load();
        return parent::setDefaultCurrency($defaultCurrency);
    }

    public function getDefaultCurrency()
    {
        $this->_load();
        return parent::getDefaultCurrency();
    }

    public function __toString()
    {
        $this->_load();
        return parent::__toString();
    }

    public function __get($name)
    {
        $this->_load();
        return parent::__get($name);
    }

    public function getComponentController()
    {
        $this->_load();
        return parent::getComponentController();
    }

    public function setVirtual($virtual)
    {
        $this->_load();
        return parent::setVirtual($virtual);
    }

    public function isVirtual()
    {
        $this->_load();
        return parent::isVirtual();
    }

    public function validate()
    {
        $this->_load();
        return parent::validate();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'active', 'pos', 'hourly_rate', 'default_currency');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}