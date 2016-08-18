<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product implements \JsonSerializable, ContainerAwareInterface
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="article", type="string", nullable=true)
     */
    private $article;

    /**
     * @ORM\Column(name="bar_code", type="string", nullable=true)
     */
    private $barCode;

    /**
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(name="quantity_for_one", type="string", nullable=true)
     */
    private $quantityForOne;

    /**
     * @ORM\Column(name="cost", type="integer", nullable=true)
     */
    private $cost;

    /**
     * @ORM\Column(name="quantity_cost", type="string", nullable=true)
     */
    private $quantityCost;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", nullable=true)
     */
    private $picture;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_available", type="boolean", options={"default" = 1})
     */
    private $isAvailable = true;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Meggi\IndexBundle\Entity\ProductOption", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_option_id", referencedColumnName="id")
     * })
     */
    private $productOption;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Meggi\IndexBundle\Entity\ProductOptionBox", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_option_box_id", referencedColumnName="id")
     * })
     */
    private $productOptionBox;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Meggi\IndexBundle\Entity\Brand", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

    /**
     * @ORM\ManyToMany(targetEntity="Meggi\IndexBundle\Entity\Category", inversedBy="products", cascade={"persist"})
     *
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Meggi\IndexBundle\Entity\OrderItem", mappedBy="product")
     */
    private $items;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $url;

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
     * @return Product
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
     * Set description
     *
     * @param string $description
     * @return Product
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
     * Set article
     *
     * @param string $article
     * @return Product
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set barCode
     *
     * @param string $barCode
     * @return Product
     */
    public function setBarCode($barCode)
    {
        $this->barCode = $barCode;

        return $this;
    }

    /**
     * Get barCode
     *
     * @return string 
     */
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set cost
     *
     * @param string $cost
     * @return Product
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string 
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set quantityCost
     *
     * @param string $quantityCost
     * @return Product
     */
    public function setQuantityCost($quantityCost)
    {
        $this->quantityCost = $quantityCost;

        return $this;
    }

    /**
     * Get quantityCost
     *
     * @return string 
     */
    public function getQuantityCost()
    {
        return $this->quantityCost;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Product
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
     * Set productOption
     *
     * @param \Meggi\IndexBundle\Entity\ProductOption $productOption
     * @return Product
     */
    public function setProductOption(\Meggi\IndexBundle\Entity\ProductOption $productOption = null)
    {
        $this->productOption = $productOption;

        return $this;
    }

    /**
     * Get productOption
     *
     * @return \Meggi\IndexBundle\Entity\ProductOption 
     */
    public function getProductOption()
    {
        return $this->productOption;
    }

    /**
     * Set brand
     *
     * @param \Meggi\IndexBundle\Entity\Brand $brand
     * @return Product
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
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categories
     *
     * @param \Meggi\IndexBundle\Entity\Category $categories
     * @return Product
     */
    public function addCategory(\Meggi\IndexBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Meggi\IndexBundle\Entity\Category $categories
     */
    public function removeCategory(\Meggi\IndexBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    private $container = null;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function JsonSerialize()
    {
        $twigExtension = $this->container->get('itm.image.preview.twig_extension');
        $pathToImage = $twigExtension->resolvePath($this, 'picture');

        return [
            'id'      => $this->getId(),
            'name'    => $this->getName(),
            'picture' => $pathToImage,
            'cost'    => $this->getQuantityCost(),
            'article' => $this->getArticle(),
        ];
    }


    /**
     * Set url
     *
     * @param string $url
     * @return Product
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
     * Set quantityForOne
     *
     * @param string $quantityForOne
     * @return Product
     */
    public function setQuantityForOne($quantityForOne)
    {
        $this->quantityForOne = $quantityForOne;

        return $this;
    }

    /**
     * Get quantityForOne
     *
     * @return string 
     */
    public function getQuantityForOne()
    {
        return $this->quantityForOne;
    }

    /**
     * Set productOptionBox
     *
     * @param \Meggi\IndexBundle\Entity\ProductOptionBox $productOptionBox
     * @return Product
     */
    public function setProductOptionBox(\Meggi\IndexBundle\Entity\ProductOptionBox $productOptionBox = null)
    {
        $this->productOptionBox = $productOptionBox;

        return $this;
    }

    /**
     * Get productOptionBox
     *
     * @return \Meggi\IndexBundle\Entity\ProductOptionBox 
     */
    public function getProductOptionBox()
    {
        return $this->productOptionBox;
    }

    /**
     * Add items
     *
     * @param \Meggi\IndexBundle\Entity\OrderItem $items
     * @return Product
     */
    public function addItem(\Meggi\IndexBundle\Entity\OrderItem $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Meggi\IndexBundle\Entity\OrderItem $items
     */
    public function removeItem(\Meggi\IndexBundle\Entity\OrderItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set isAvailable
     *
     * @param boolean $isAvailable
     * @return Product
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return boolean 
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }
}
