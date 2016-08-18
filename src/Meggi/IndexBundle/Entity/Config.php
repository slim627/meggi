<?php

namespace Meggi\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="Meggi\IndexBundle\Entity\Repository\ConfigRepository")
 */
class Config
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
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @ORM\Column(name="key_value", type="text")
     */
    private $keyValue;

    public function __toString()
    {
        return $this->title ? $this->title : 'Создание';
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
     * @return Config
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
     * Set value
     *
     * @param string $value
     * @return Config
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set keyValue
     *
     * @param string $keyValue
     * @return Config
     */
    public function setKeyValue($keyValue)
    {
        $this->keyValue = $keyValue;

        return $this;
    }

    /**
     * Get keyValue
     *
     * @return string 
     */
    public function getKeyValue()
    {
        return $this->keyValue;
    }
}
