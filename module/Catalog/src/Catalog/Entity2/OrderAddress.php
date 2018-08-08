<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderAddress
 *
 * @ORM\Table(name="order_address", uniqueConstraints={@ORM\UniqueConstraint(name="order_address", columns={"order_id"})})
 * @ORM\Entity
 */
class OrderAddress
{
    /**
     * @var integer
     *
     * @ORM\Column(name="order_address_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderAddressId;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=128, nullable=false)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=128, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=128, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=128, nullable=false)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=128, nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=52, nullable=false)
     */
    private $country;

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
     * Get orderAddressId
     *
     * @return integer 
     */
    public function getOrderAddressId()
    {
        return $this->orderAddressId;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return OrderAddress
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return OrderAddress
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return OrderAddress
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return OrderAddress
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return OrderAddress
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return OrderAddress
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set order
     *
     * @param \Catalog\Entity\Order $order
     * @return OrderAddress
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
}
