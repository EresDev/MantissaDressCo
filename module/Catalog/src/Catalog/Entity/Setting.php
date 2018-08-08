<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 *
 * @ORM\Table(name="setting")
 * @ORM\Entity
 */
class Setting
{
    /**
     * @var integer
     *
     * @ORM\Column(name="setting_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $settingId;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_key", type="string", length=50, nullable=true)
     */
    private $settingKey;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_value", type="string", length=1000, nullable=true)
     */
    private $settingValue;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_name", type="string", length=50, nullable=false)
     */
    private $settingName;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_type", type="string", length=50, nullable=true)
     */
    private $settingType = 'Text';



    /**
     * Get settingId
     *
     * @return integer 
     */
    public function getSettingId()
    {
        return $this->settingId;
    }

    /**
     * Set settingKey
     *
     * @param string $settingKey
     * @return Setting
     */
    public function setSettingKey($settingKey)
    {
        $this->settingKey = $settingKey;

        return $this;
    }

    /**
     * Get settingKey
     *
     * @return string 
     */
    public function getSettingKey()
    {
        return $this->settingKey;
    }

    /**
     * Set settingValue
     *
     * @param string $settingValue
     * @return Setting
     */
    public function setSettingValue($settingValue)
    {
        $this->settingValue = $settingValue;

        return $this;
    }

    /**
     * Get settingValue
     *
     * @return string 
     */
    public function getSettingValue()
    {
        return $this->settingValue;
    }

    /**
     * Set settingName
     *
     * @param string $settingName
     * @return Setting
     */
    public function setSettingName($settingName)
    {
        $this->settingName = $settingName;

        return $this;
    }

    /**
     * Get settingName
     *
     * @return string 
     */
    public function getSettingName()
    {
        return $this->settingName;
    }

    /**
     * Set settingType
     *
     * @param string $settingType
     * @return Setting
     */
    public function setSettingType($settingType)
    {
        $this->settingType = $settingType;

        return $this;
    }

    /**
     * Get settingType
     *
     * @return string 
     */
    public function getSettingType()
    {
        return $this->settingType;
    }
}
