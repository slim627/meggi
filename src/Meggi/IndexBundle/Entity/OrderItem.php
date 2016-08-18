<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Meggi\IndexBundle\Twig\AmountExtension;
/**
 * OrderItem
 *
 * @ORM\Table(name="order_item")
 * @ORM\Entity
 */
class OrderItem implements \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="Meggi\IndexBundle\Entity\Product", inversedBy="items")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Orders", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", onDelete="CASCADE")
     */
    private $order;

    /**
     * Колличество заказанного продукта
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    public function format($amount)
    {
        $amount = preg_replace('[^0-9]', '', $amount);

        return preg_replace('/(\d)(?=(\d{3})+(?!\d))/', '$1 ', $amount);
    }

    public function JsonSerialize()
    {
        return [
            'prodName' => $this->getProduct()->getName(),
            'article'  => $this->getProduct()->getArticle(),
            'cost'     => $this->format($this->getProduct()->getQuantityCost()),
            'fullCost' => $this->format(intval($this->getProduct()->getQuantityCost() * $this->getQuantity())),
            'quantity' => $this->getQuantity(),
        ];
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
     * Set product
     *
     * @param \Meggi\IndexBundle\Entity\Product $product
     * @return OrderItem
     */
    public function setProduct(\Meggi\IndexBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Meggi\IndexBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set order
     *
     * @param \Meggi\IndexBundle\Entity\Orders $order
     * @return OrderItem
     */
    public function setOrder(\Meggi\IndexBundle\Entity\Orders $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Meggi\IndexBundle\Entity\Orders
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return OrderItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
