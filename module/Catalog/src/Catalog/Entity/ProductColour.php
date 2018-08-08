<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductColour
 *
 * @ORM\Table(name="product_colour")
 * @ORM\Entity
 */
class ProductColour
{
    /**
     * @var integer
     *
     * @ORM\Column(name="product_colour_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productColourId;

    /**
     * @var string
     *
     * @ORM\Column(name="colour_name", type="string", length=50, nullable=false)
     */
    private $colourName;



    /**
     * Get productColourId
     *
     * @return integer 
     */
    public function getProductColourId()
    {
        return $this->productColourId;
    }

    /**
     * Set colourName
     *
     * @param string $colourName
     * @return ProductColour
     */
    public function setColourName($colourName)
    {
        $this->colourName = $colourName;

        return $this;
    }

    /**
     * Get colourName
     *
     * @return string 
     */
    public function getColourName()
    {
        return $this->colourName;
    }
}
