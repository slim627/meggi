<?php
namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity()
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="company_name", type="string", nullable=true)
     */
    private $companyName;

    /**
     * @var string
     * @ORM\Column(name="unp", type="string", nullable=true)
     */
    private $UNP;

    /**
     * @var string
     * @ORM\Column(name="patronymic", type="string", nullable=true)
     */
    private $patronymic;

    /**
     * @var string
     * @ORM\Column(name="position", type="string", nullable=true)
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(name="juristic_address", type="string", nullable=true)
     */
    private $juristicAddress;

    /**
     * Расчетный счет
     * @var string
     * @ORM\Column(name="bank_details", type="string", nullable=true)
     */
    private $bankDetails;

    /**
     * @var string
     * @ORM\Column(name="bank_name", type="string", nullable=true)
     */
    private $bankName;

    /**
     * @var string
     * @ORM\Column(name="bank_code", type="string", nullable=true)
     */
    private $bankCode;

    /**
     * @var string
     * @ORM\Column(name="person", type="string", nullable=true)
     */
    private $person;

    /**
     * @var string
     * @ORM\Column(name="country_code", type="string", nullable=true)
     */
    private $countryCode;

    /**
     * @var string
     * @ORM\Column(name="operator_code", type="string", nullable=true)
     */
    private $operatorCode;

    /**
     * @var string
     * @ORM\Column(name="phone_number", type="string", nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(name="document", type="string", nullable=true)
     */
    private $document;

    /**
     * @ORM\OneToMany(targetEntity="Meggi\IndexBundle\Entity\Orders", mappedBy="user")
     */
    private $orders;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add orders
     *
     * @param \Meggi\IndexBundle\Entity\Orders $orders
     * @return User
     */
    public function addOrder(\Meggi\IndexBundle\Entity\Orders $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Meggi\IndexBundle\Entity\Orders $orders
     */
    public function removeOrder(\Meggi\IndexBundle\Entity\Orders $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return User
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set UNP
     *
     * @param string $uNP
     * @return User
     */
    public function setUNP($uNP)
    {
        $this->UNP = $uNP;

        return $this;
    }

    /**
     * Get UNP
     *
     * @return string 
     */
    public function getUNP()
    {
        return $this->UNP;
    }

    /**
     * Set patronymic
     *
     * @param string $patronymic
     * @return User
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string 
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return User
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

    /**
     * Set juristicAddress
     *
     * @param string $juristicAddress
     * @return User
     */
    public function setJuristicAddress($juristicAddress)
    {
        $this->juristicAddress = $juristicAddress;

        return $this;
    }

    /**
     * Get juristicAddress
     *
     * @return string 
     */
    public function getJuristicAddress()
    {
        return $this->juristicAddress;
    }

    /**
     * Set bankDetails
     *
     * @param string $bankDetails
     * @return User
     */
    public function setBankDetails($bankDetails)
    {
        $this->bankDetails = $bankDetails;

        return $this;
    }

    /**
     * Get bankDetails
     *
     * @return string 
     */
    public function getBankDetails()
    {
        return $this->bankDetails;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     * @return User
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string 
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set bankCode
     *
     * @param string $bankCode
     * @return User
     */
    public function setBankCode($bankCode)
    {
        $this->bankCode = $bankCode;

        return $this;
    }

    /**
     * Get bankCode
     *
     * @return string 
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * Set person
     *
     * @param string $person
     * @return User
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return string 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return User
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set operatorCode
     *
     * @param string $operatorCode
     * @return User
     */
    public function setOperatorCode($operatorCode)
    {
        $this->operatorCode = $operatorCode;

        return $this;
    }

    /**
     * Get operatorCode
     *
     * @return string 
     */
    public function getOperatorCode()
    {
        return $this->operatorCode;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set document
     *
     * @param string $document
     * @return User
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string 
     */
    public function getDocument()
    {
        return $this->document;
    }
}
