<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProduct
 *
 * @ORM\Table(name="order_product", indexes={@ORM\Index(name="order_id", columns={"order_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class OrderProduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="order_product_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderProductId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_option_title", type="string", length=100, nullable=true)
     */
    private $productOptionTitle;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity = '1';

    /**
     * @var float
     *
     * @ORM\Column(name="unit_price", type="float", precision=10, scale=0, nullable=false)
     */
    private $unitPrice;

    /**
     * @var \Catalog\Entity\Order
     *
     * @ORM\ManyToOne(targetEntity="Catalog\Entity\Order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="order_id")
     * })
     */
    private $order;

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
     * Get orderProductId
     *
     * @return integer 
     */
    public function getOrderProductId()
    {
        return $this->orderProductId;
    }

    /**
     * Set productOptionTitle
     *
     * @param string $productOptionTitle
     * @return OrderProduct
     */
    public function setProductOptionTitle($productOptionTitle)
    {
        $this->productOptionTitle = $productOptionTitle;

        return $this;
    }

    /**
     * Get productOptionTitle
     *
     * @return string 
     */
    public function getProductOptionTitle()
    {
        return $this->productOptionTitle;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return OrderProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return OrderProduct
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set order
     *
     * @param \Catalog\Entity\Order $order
     * @return OrderProduct
     */
    public function setOrder(\Catalog\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Catalog\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \Catalog\Entity\Product $product
     * @return OrderProduct
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
