<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="Meggi\IndexBundle\Entity\Repository\OrdersRepository")
 */
class Orders implements \JsonSerializable
{
    const STATUS_NEW       = 0; // Обрабатывается
    const STATUS_BILLED    = 1; // Выставлен счет
    const STATUS_WAIT      = 2; // Подтверждение об оплате счета отправлено, ожидание
    const STATUS_PAID      = 3; // Оплата счета подтверждена
    const STATUS_OVERDUE   = 4; // Просрочен

    const DELIVERY_BY_MYSELF = 0; // Самовывоз
    const DELIVERY_BY_COMPANY = 1; // Доставка

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="full_summ", type="integer", nullable=false)
     */
    private $fullSumm;

    /**
     * @var string
     * @ORM\Column(name="file", type="string", nullable=true)
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", length=255, nullable=true)
     */
    private $status = self::STATUS_NEW;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_archive", type="boolean", nullable=true)
     */
    private $isArchive;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime $deletedAt
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string
     * @ORM\Column(name="delivery", type="string", nullable=true)
     */
    private $delivery;

    /**
     * @var boolean
     * @ORM\Column(name="delivery_address", type="string", nullable=true)
     */
    private $deliveryAddress;

    /**
     * @var string
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    private $message;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Meggi\IndexBundle\Entity\OrderItem", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     */
    private $items;

    /**
     * @var string
     * @ORM\Column(name="payment_number", type="string", nullable=true)
     */
    private $paymentNumber;

    /**
     * @var datetime
     * @ORM\Column(name="payment_date", type="datetime", nullable=true)
     */
    private $paymentDate;

    /**
     * @var string
     * @ORM\Column(name="payment_sum", type="string", nullable=true)
     */
    private $paymentSum;

    public function __toString()
    {
        return 'Заказ № '.$this->id;
    }

    public function JsonSerialize()
    {
        $statuses = $this->getStatuses();
        $deliveries = $this->getDeliveries();
        return [
            'id'           => $this->getId(),
            'createdAt'    => $this->getCreatedAt()->format('d.m.Y'),
            'status'       => $statuses[$this->getStatus()],
            'statusNum'    => $this->getStatus(),
            'fullSumm'     => $this->format($this->getFullSumm()),
            'delivery'     => $this->getDelivery(),
            'deliveryWord' => $deliveries[$this->getDelivery()],
            'item'         => $this->getItems()->toArray(),
        ];
    }

    public function format($amount)
    {
        $amount = preg_replace('[^0-9]', '', $amount);

        return preg_replace('/(\d)(?=(\d{3})+(?!\d))/', '$1 ', $amount);
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

    public static function getStatuses()
    {
        return array(
            self::STATUS_NEW      =>  'Обрабатывается',
            self::STATUS_BILLED   =>  'Выставлен счет',
            self::STATUS_WAIT     =>  'Подтверждение об оплате счета отправлено, ожидание',
            self::STATUS_PAID     =>  'Оплата счета подтверждена',
            self::STATUS_OVERDUE  =>  'Просрочен',
        );
    }

    public static function getDeliveries()
    {
        return [
            self::DELIVERY_BY_MYSELF => 'Самовывоз (-5%)',
            self::DELIVERY_BY_COMPANY => 'Доставка',
        ];
    }

    /**
     * Remove all order items
     */
    public function clearItems()
    {
        foreach($this->getItems() as $item){
            $this->removeItem($item);
        }
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Orders
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add items
     *
     * @param \Meggi\IndexBundle\Entity\OrderItem $items
     * @return Orders
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->getItems()->map(function (OrderItem $item) {
            return $item->getProduct();
        });
    }

    /**
     * Set delivery
     *
     * @param string $delivery
     * @return Orders
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return string
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Orders
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Orders
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Orders
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Orders
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Orders
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set isArchive
     *
     * @param boolean $isArchive
     * @return Orders
     */
    public function setIsArchive($isArchive)
    {
        $this->isArchive = $isArchive;

        return $this;
    }

    /**
     * Get isArchive
     *
     * @return boolean 
     */
    public function getIsArchive()
    {
        return $this->isArchive;
    }

    /**
     * Set fullSumm
     *
     * @param integer $fullSumm
     * @return Orders
     */
    public function setFullSumm($fullSumm)
    {
        $this->fullSumm = $fullSumm;

        return $this;
    }

    /**
     * Get fullSumm
     *
     * @return integer 
     */
    public function getFullSumm()
    {
        return $this->fullSumm;
    }

    /**
     * Set deliveryAddress
     *
     * @param string $deliveryAddress
     * @return Orders
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get deliveryAddress
     *
     * @return string 
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return Orders
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set paymentNumber
     *
     * @param string $paymentNumber
     * @return Orders
     */
    public function setPaymentNumber($paymentNumber)
    {
        $this->paymentNumber = $paymentNumber;

        return $this;
    }

    /**
     * Get paymentNumber
     *
     * @return string 
     */
    public function getPaymentNumber()
    {
        return $this->paymentNumber;
    }

    /**
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     * @return Orders
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate
     *
     * @return \DateTime 
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set paymentSum
     *
     * @param string $paymentSum
     * @return Orders
     */
    public function setPaymentSum($paymentSum)
    {
        $this->paymentSum = $paymentSum;

        return $this;
    }

    /**
     * Get paymentSum
     *
     * @return string 
     */
    public function getPaymentSum()
    {
        return $this->paymentSum;
    }
}
