<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOptionBox
 *
 * @ORM\Table(name="product_option_box")
 * @ORM\Entity
 */
class ProductOptionBox
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Meggi\IndexBundle\Entity\Product", mappedBy="productOptionBox", cascade={"all"}, orphanRemoval=true)
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
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ProductOptionBox
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
     * Add products
     *
     * @param \Meggi\IndexBundle\Entity\Product $products
     * @return ProductOptionBox
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
}
