<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOption
 *
 * @ORM\Table(name="product_option")
 * @ORM\Entity
 */
class ProductOption
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
     * @ORM\OneToMany(targetEntity="Meggi\IndexBundle\Entity\Product", mappedBy="productOption", cascade={"all"}, orphanRemoval=true)
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
     * Add products
     *
     * @param \Meggi\IndexBundle\Entity\Product $products
     * @return ProductOption
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
     * Set name
     *
     * @param string $name
     * @return ProductOption
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
}
