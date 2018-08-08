<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductAttribute
 *
 * @ORM\Table(name="product_attribute", indexes={@ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class ProductAttribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="product_attribute_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productAttributeId;

    /**
     * @var string
     *
     * @ORM\Column(name="attribute_key", type="string", length=50, nullable=true)
     */
    private $attributeKey;

    /**
     * @var string
     *
     * @ORM\Column(name="attrinute_value", type="string", length=50, nullable=true)
     */
    private $attrinuteValue;

    /**
     * @var \Catalog\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="Catalog\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     * })
     */
    private $product;



    /**
     * Get productAttributeId
     *
     * @return integer 
     */
    public function getProductAttributeId()
    {
        return $this->productAttributeId;
    }

    /**
     * Set attributeKey
     *
     * @param string $attributeKey
     * @return ProductAttribute
     */
    public function setAttributeKey($attributeKey)
    {
        $this->attributeKey = $attributeKey;

        return $this;
    }

    /**
     * Get attributeKey
     *
     * @return string 
     */
    public function getAttributeKey()
    {
        return $this->attributeKey;
    }

    /**
     * Set attrinuteValue
     *
     * @param string $attrinuteValue
     * @return ProductAttribute
     */
    public function setAttrinuteValue($attrinuteValue)
    {
        $this->attrinuteValue = $attrinuteValue;

        return $this;
    }

    /**
     * Get attrinuteValue
     *
     * @return string 
     */
    public function getAttrinuteValue()
    {
        return $this->attrinuteValue;
    }

    /**
     * Set product
     *
     * @param \Catalog\Entity\Product $product
     * @return ProductAttribute
     */
    public function setProduct(\Catalog\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Catalog\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
