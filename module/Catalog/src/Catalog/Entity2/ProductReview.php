<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductReview
 *
 * @ORM\Table(name="product_review", indexes={@ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class ProductReview
{
    /**
     * @var integer
     *
     * @ORM\Column(name="product_review_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productReviewId;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="text",  nullable=false)
     */
    private $review;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    private $approved = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="reviewer_name", type="string", length=70, nullable=false)
     */
    private $reviewerName;

    /**
     * @var string
     *
     * @ORM\Column(name="stars", type="string", nullable=true)
     */
    private $stars = '5';

    /**
     * @var \Catalog\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="Catalog\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id" , nullable=false)
     * })
     */
    private $product;



    /**
     * Get productReviewId
     *
     * @return integer 
     */
    public function getProductReviewId()
    {
        return $this->productReviewId;
    }

    /**
     * Set review
     *
     * @param string $review
     * @return ProductReview
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return ProductReview
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set reviewerName
     *
     * @param string $reviewerName
     * @return ProductReview
     */
    public function setReviewerName($reviewerName)
    {
        $this->reviewerName = $reviewerName;

        return $this;
    }

    /**
     * Get reviewerName
     *
     * @return string 
     */
    public function getReviewerName()
    {
        return $this->reviewerName;
    }

    /**
     * Set stars
     *
     * @param string $stars
     * @return ProductReview
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return string 
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set product
     *
     * @param \Catalog\Entity\Product $product
     * @return ProductReview
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
