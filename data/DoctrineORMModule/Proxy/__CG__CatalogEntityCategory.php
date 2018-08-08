<?php

namespace DoctrineORMModule\Proxy\__CG__\Catalog\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Category extends \Catalog\Entity\Category implements \Doctrine\ORM\Proxy\Proxy
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
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'categoryId', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'title', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'description', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'image', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'enabled', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'sortOrder', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'metaTitle', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'metaDescription', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'metaKeyword', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'barcode', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'parentCategory');
        }

        return array('__isInitialized__', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'categoryId', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'title', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'description', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'image', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'enabled', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'sortOrder', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'metaTitle', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'metaDescription', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'metaKeyword', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'barcode', '' . "\0" . 'Catalog\\Entity\\Category' . "\0" . 'parentCategory');
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
    public function getCategoryId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getCategoryId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCategoryId', array());

        return parent::getCategoryId();
    }

    /**
     * {@inheritDoc}
     */
    public function setTitle($title)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTitle', array($title));

        return parent::setTitle($title);
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTitle', array());

        return parent::getTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription($description)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDescription', array($description));

        return parent::setDescription($description);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', array());

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setImage($image)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setImage', array($image));

        return parent::setImage($image);
    }

    /**
     * {@inheritDoc}
     */
    public function getImage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImage', array());

        return parent::getImage();
    }

    /**
     * {@inheritDoc}
     */
    public function setEnabled($enabled)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEnabled', array($enabled));

        return parent::setEnabled($enabled);
    }

    /**
     * {@inheritDoc}
     */
    public function getEnabled()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEnabled', array());

        return parent::getEnabled();
    }

    /**
     * {@inheritDoc}
     */
    public function setSortOrder($sortOrder)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSortOrder', array($sortOrder));

        return parent::setSortOrder($sortOrder);
    }

    /**
     * {@inheritDoc}
     */
    public function getSortOrder()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSortOrder', array());

        return parent::getSortOrder();
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaTitle($metaTitle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMetaTitle', array($metaTitle));

        return parent::setMetaTitle($metaTitle);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetaTitle', array());

        return parent::getMetaTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaDescription($metaDescription)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMetaDescription', array($metaDescription));

        return parent::setMetaDescription($metaDescription);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaDescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetaDescription', array());

        return parent::getMetaDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaKeyword($metaKeyword)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMetaKeyword', array($metaKeyword));

        return parent::setMetaKeyword($metaKeyword);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaKeyword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetaKeyword', array());

        return parent::getMetaKeyword();
    }

    /**
     * {@inheritDoc}
     */
    public function setBarcode($barcode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBarcode', array($barcode));

        return parent::setBarcode($barcode);
    }

    /**
     * {@inheritDoc}
     */
    public function getBarcode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBarcode', array());

        return parent::getBarcode();
    }

    /**
     * {@inheritDoc}
     */
    public function setParentCategory(\Catalog\Entity\Category $parentCategory = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setParentCategory', array($parentCategory));

        return parent::setParentCategory($parentCategory);
    }

    /**
     * {@inheritDoc}
     */
    public function getParentCategory()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParentCategory', array());

        return parent::getParentCategory();
    }

}
