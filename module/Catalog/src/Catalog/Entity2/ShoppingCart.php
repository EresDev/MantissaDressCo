<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShoppingCart
 *
 * @ORM\Table(name="shopping_cart", indexes={@ORM\Index(name="product_id", columns={"product_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class ShoppingCart
{
    /**
     * @var integer
     *
     * @ORM\Column(name="shopping_cart_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $shoppingCartId;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity = '1';

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
     * @var \Catalog\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Catalog\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;



    /**
     * Get shoppingCartId
     *
     * @return integer 
     */
    public function getShoppingCartId()
    {
        return $this->shoppingCartId;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return ShoppingCart
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
     * Set product
     *
     * @param \Catalog\Entity\Product $product
     * @return ShoppingCart
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

    /**
     * Set user
     *
     * @param \Catalog\Entity\User $user
     * @return ShoppingCart
     */
    public function setUser(\Catalog\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Catalog\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
