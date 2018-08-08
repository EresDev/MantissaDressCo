<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOption
 *
 * @ORM\Table(name="product_option", indexes={@ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class ProductOption
{
    /**
     * @var integer
     *
     * @ORM\Column(name="product_option_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productOptionId;

    /**
     * @var string
     *
     * @ORM\Column(name="option_title", type="string", length=100, nullable=true)
     */
    private $optionTitle;

    /**
     * @var integer
     *
     * @ORM\Column(name="available_qty", type="integer", nullable=false)
     */
    private $availableQty = '-1';

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
     * Get productOptionId
     *
     * @return integer 
     */
    public function getProductOptionId()
    {
        return $this->productOptionId;
    }

    /**
     * Set optionTitle
     *
     * @param string $optionTitle
     * @return ProductOption
     */
    public function setOptionTitle($optionTitle)
    {
        $this->optionTitle = $optionTitle;

        return $this;
    }

    /**
     * Get optionTitle
     *
     * @return string 
     */
    public function getOptionTitle()
    {
        return $this->optionTitle;
    }

    /**
     * Set availableQty
     *
     * @param integer $availableQty
     * @return ProductOption
     */
    public function setAvailableQty($availableQty)
    {
        $this->availableQty = $availableQty;

        return $this;
    }

    /**
     * Get availableQty
     *
     * @return integer 
     */
    public function getAvailableQty()
    {
        return $this->availableQty;
    }

    /**
     * Set product
     *
     * @param \Catalog\Entity\Product $product
     * @return ProductOption
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
