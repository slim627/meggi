<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 *
 * @ORM\Table(name="banner")
 * @ORM\Entity
 */
class Banner
{
    const POSITION_RIGHT = 0;
    const POSITION_LEFT = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="text", nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="color", type="string", nullable=false)
     */
    private $color;

    /**
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(name="link", type="string", nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(name="position", type="string", nullable=false)
     */
    private $position;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Meggi\IndexBundle\Entity\Brand", inversedBy="banners")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

    public function __toString()
    {
        return strip_tags($this->title ) ? strip_tags($this->title ) : 'Создание';
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
     * Set title
     *
     * @param string $title
     * @return Banner
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Banner
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set color
     *
     * @param integer $color
     * @return Banner
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return integer 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Banner
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
     * Set position
     *
     * @param string $position
     * @return Banner
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    public static function getPositions()
    {
        return [
            self::POSITION_LEFT => 'left',
            self::POSITION_RIGHT => 'right',
        ];
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Banner
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set brand
     *
     * @param \Meggi\IndexBundle\Entity\Brand $brand
     * @return Banner
     */
    public function setBrand(\Meggi\IndexBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Meggi\IndexBundle\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Banner
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
}
