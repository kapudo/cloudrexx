<?php

namespace Cx\Model\Proxies\__CG__\Cx\Modules\Calendar\Model\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Category extends \Cx\Modules\Calendar\Model\Entity\Category implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }

    /**
     * {@inheritDoc}
     * @param string $name
     */
    public function __get($name)
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__get', array($name));

        return parent::__get($name);
    }





    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'id', 'pos', 'status', 'categoryNames', 'events', 'validators', 'virtual');
        }

        return array('__isInitialized__', 'id', 'pos', 'status', 'categoryNames', 'events', 'validators', 'virtual');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Category $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPos($pos)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPos', array($pos));

        return parent::setPos($pos);
    }

    /**
     * {@inheritDoc}
     */
    public function getPos()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPos', array());

        return parent::getPos();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', array($status));

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', array());

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function addCategoryName(\Cx\Modules\Calendar\Model\Entity\CategoryName $categoryName)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addCategoryName', array($categoryName));

        return parent::addCategoryName($categoryName);
    }

    /**
     * {@inheritDoc}
     */
    public function setCategoryNames($categoryNames)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCategoryNames', array($categoryNames));

        return parent::setCategoryNames($categoryNames);
    }

    /**
     * {@inheritDoc}
     */
    public function removeCategoryName(\Cx\Modules\Calendar\Model\Entity\CategoryName $categoryNames)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeCategoryName', array($categoryNames));

        return parent::removeCategoryName($categoryNames);
    }

    /**
     * {@inheritDoc}
     */
    public function getCategoryNameByLangId($langId)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCategoryNameByLangId', array($langId));

        return parent::getCategoryNameByLangId($langId);
    }

    /**
     * {@inheritDoc}
     */
    public function getCategoryNames()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCategoryNames', array());

        return parent::getCategoryNames();
    }

    /**
     * {@inheritDoc}
     */
    public function addEvent(\Cx\Modules\Calendar\Model\Entity\Event $event)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addEvent', array($event));

        return parent::addEvent($event);
    }

    /**
     * {@inheritDoc}
     */
    public function removeEvent(\Cx\Modules\Calendar\Model\Entity\Event $events)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeEvent', array($events));

        return parent::removeEvent($events);
    }

    /**
     * {@inheritDoc}
     */
    public function setEvents($events)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEvents', array($events));

        return parent::setEvents($events);
    }

    /**
     * {@inheritDoc}
     */
    public function getEvents()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEvents', array());

        return parent::getEvents();
    }

    /**
     * {@inheritDoc}
     */
    public function addEvents($event)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addEvents', array($event));

        return parent::addEvents($event);
    }

    /**
     * {@inheritDoc}
     */
    public function getComponentController()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getComponentController', array());

        return parent::getComponentController();
    }

    /**
     * {@inheritDoc}
     */
    public function setVirtual($virtual)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVirtual', array($virtual));

        return parent::setVirtual($virtual);
    }

    /**
     * {@inheritDoc}
     */
    public function isVirtual()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isVirtual', array());

        return parent::isVirtual();
    }

    /**
     * {@inheritDoc}
     */
    public function validate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'validate', array());

        return parent::validate();
    }

    /**
     * {@inheritDoc}
     */
    public function __call($methodName, $arguments)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__call', array($methodName, $arguments));

        return parent::__call($methodName, $arguments);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', array());

        return parent::__toString();
    }

}