<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="`order`", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_datetime", type="datetime", nullable=false)
     */
    private $orderDatetime = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="ostatus", type="boolean", nullable=true)
     */
    private $ostatus = '0';

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
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set orderDatetime
     *
     * @param \DateTime $orderDatetime
     * @return Order
     */
    public function setOrderDatetime($orderDatetime)
    {
        $this->orderDatetime = $orderDatetime;

        return $this;
    }

    /**
     * Get orderDatetime
     *
     * @return \DateTime 
     */
    public function getOrderDatetime()
    {
        return $this->orderDatetime;
    }

    /**
     * Set ostatus
     *
     * @param boolean $ostatus
     * @return Order
     */
    public function setOstatus($ostatus)
    {
        $this->ostatus = $ostatus;

        return $this;
    }

    /**
     * Get ostatus
     *
     * @return boolean 
     */
    public function getOstatus()
    {
        return $this->ostatus;
    }

    /**
     * Set user
     *
     * @param \Catalog\Entity\User $user
     * @return Order
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
