<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoginHistory
 *
 * @ORM\Table(name="login_history", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class LoginHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="history_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $historyId;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=45, nullable=false)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_time", type="datetime", nullable=false)
     */
    private $dateTime = 'CURRENT_TIMESTAMP';

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
     * Get historyId
     *
     * @return integer 
     */
    public function getHistoryId()
    {
        return $this->historyId;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return LoginHistory
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return LoginHistory
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime 
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set user
     *
     * @param \Catalog\Entity\User $user
     * @return LoginHistory
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
