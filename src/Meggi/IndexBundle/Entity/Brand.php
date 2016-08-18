<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Brand
 *
 * @ORM\Table(name="brand")
 * @ORM\Entity
 */
class Brand
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="brand_title", type="string", nullable=false)
     */
    private $brandTitle;

    /**
     * @ORM\Column(name="title_color", type="string", nullable=false)
     */
    private $titleColor;

    /**
     * @ORM\Column(name="frlame_color", type="string", nullable=false)
     */
    private $frameColor;

    /**
     * @ORM\Column(name="weight", type="integer", nullable=false)
     */
    private $weight;

    /**
     * @ORM\Column(name="picture", type="string", nullable=true)
     */
    private $picture;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="Meggi\IndexBundle\Entity\Banner", mappedBy="brand", cascade={"all"}, orphanRemoval=true)
     */
    private $banners;

    /**
     * @ORM\OneToMany(targetEntity="Meggi\IndexBundle\Entity\Product", mappedBy="brand", cascade={"all"}, orphanRemoval=true)
     */
    private $products;

    public function __toString()
    {
        return $this->name ? $this->name : 'Создание';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Brand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Brand
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->banners = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add banners
     *
     * @param \Meggi\IndexBundle\Entity\Banner $banners
     * @return Brand
     */
    public function addBanner(\Meggi\IndexBundle\Entity\Banner $banners)
    {
        $this->banners[] = $banners;

        return $this;
    }

    /**
     * Remove banners
     *
     * @param \Meggi\IndexBundle\Entity\Banner $banners
     */
    public function removeBanner(\Meggi\IndexBundle\Entity\Banner $banners)
    {
        $this->banners->removeElement($banners);
    }

    /**
     * Get banners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBanners()
    {
        return $this->banners;
    }

    /**
     * Add products
     *
     * @param \Meggi\IndexBundle\Entity\Product $products
     * @return Brand
     */
    public function addProduct(\Meggi\IndexBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Meggi\IndexBundle\Entity\Product $products
     */
    public function removeProduct(\Meggi\IndexBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Brand
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set brandTitle
     *
     * @param string $brandTitle
     * @return Brand
     */
    public function setBrandTitle($brandTitle)
    {
        $this->brandTitle = $brandTitle;

        return $this;
    }

    /**
     * Get brandTitle
     *
     * @return string 
     */
    public function getBrandTitle()
    {
        return $this->brandTitle;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Brand
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set titleColor
     *
     * @param string $titleColor
     * @return Brand
     */
    public function setTitleColor($titleColor)
    {
        $this->titleColor = $titleColor;

        return $this;
    }

    /**
     * Get titleColor
     *
     * @return string 
     */
    public function getTitleColor()
    {
        return $this->titleColor;
    }

    /**
     * Set frameColor
     *
     * @param string $frameColor
     * @return Brand
     */
    public function setFrameColor($frameColor)
    {
        $this->frameColor = $frameColor;

        return $this;
    }

    /**
     * Get frameColor
     *
     * @return string 
     */
    public function getFrameColor()
    {
        return $this->frameColor;
    }
}
